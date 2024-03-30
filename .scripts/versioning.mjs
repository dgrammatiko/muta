import { basename, sep } from 'node:path';
import { createHash } from 'node:crypto';
import { createReadStream, existsSync, readdirSync, readFileSync, writeFileSync } from 'node:fs';

const createHashFromFile = (filePath) => new Promise((res) => {
  const hash = createHash('md5');
  return createReadStream(filePath).on('data', (data) => hash.update(data)).on('end', () => res(hash.digest('hex')));
});

const final = {};
// We will skip these:
const exclusion = [
  '.DS_Store',
  'index.html',
  'cache',
  'vendors',
];

/**
 * Update a given joomla.assets.json entry
 *
 * @param asset
 * @param directory
 * @returns {Promise<{type}|*>}
 */
const updateAsset = async (asset, directory) => {
  if (!asset.type || !['style', 'script'].includes(asset.type) || !asset.uri) {
    final[directory].push(asset);
    return;
  }
  let subDir;
  if (asset.type === 'script') {
    subDir = 'js';
  }
  if (asset.type === 'style') {
    subDir = 'css';
  }

  let path = `${directory}/${subDir}/${basename(asset.uri)}`;

  if (!existsSync(path) && existsSync(`${directory}/${subDir}/fields/${basename(asset.uri)}`)) {
    path = `${directory}/${subDir}/fields/${basename(asset.uri)}`;
  }

  if (existsSync(`${directory}/${subDir}/${asset.uri}`)) {
    path = `${directory}/${subDir}/${asset.uri}`;
  }

  if (!existsSync(path)) {
    final[directory].push(asset);
    return;
  }

  const hash = await createHashFromFile(path);

  asset.version = hash.slice(-6);
  final[directory].push(asset);
};

/**
 * Read the joomla.assets.json and loop the assets
 *
 * @param directory
 * @returns {Promise<void>}
 */
const fixVersion = async (originalDirectory, isTemplate = false) => {
  let filesDir = !isTemplate ? `media/${originalDirectory}` : originalDirectory.replace(`src${sep}`, `media${sep}`);
  let assetDir = !isTemplate ? `media/${originalDirectory}/joomla.asset.json` : `${originalDirectory}/joomla.asset.json`;

  if (!existsSync(assetDir)) return;

  const jAssetFileContent = readFileSync(assetDir, { encoding: 'utf8' });
  let jsonData;
  try {
    jsonData = JSON.parse(jAssetFileContent);
  } catch (err) {
    throw new Error(`media\\${directory}\\joomla.asset.json is not a valid JSON file!!!`);
  }

  if (!jsonData || !jsonData.assets || !jsonData.assets.length) {
    throw new Error(`media\\${directory}\\joomla.asset.json is not a valid JSON file!!!`);
  }

  const processes = [];
  jsonData.assets.map((asset) => processes.push(updateAsset(asset, filesDir)));

  await Promise.all(processes);

  jsonData.assets = final[filesDir];

  writeFileSync(assetDir, JSON.stringify(jsonData, '', 2), { encoding: 'utf8', mode: 0o644 });
};

/**
 * Loop the media folder and add version to all .js/.css entries in all
 * the joomla.assets.json files
 *
 * @returns {Promise<void>}
 */
export async function versioning() {
  const tasks = [];
  readdirSync('media')
  .filter((dir) => ![...exclusion, 'templates'].includes(dir))
  .forEach((directory) => {
    final[directory] = [];
    tasks.push(fixVersion(directory));
  });

  if (existsSync('src/templates/administrator')) {
    readdirSync('src/templates/administrator', {withFileTypes: true})
    .filter((dir) => !exclusion.includes(dir.path))
    .forEach((dir) => {
      final[`media/templates/administrator/${dir.name}`] = [];
      tasks.push(fixVersion(`${dir.path}/${dir.name}`, true));
    });
  }
  if (existsSync('src/templates/site')) {
    readdirSync('src/templates/site', {withFileTypes: true})
    .filter((dir) => !exclusion.includes(dir.path))
    .forEach((dir) => {
      final[`media/templates/site/${dir.name}`] = [];
      tasks.push(fixVersion(`${dir.path}/${dir.name}`, true));
    });
  }

  await Promise.all(tasks);


};


versioning()
