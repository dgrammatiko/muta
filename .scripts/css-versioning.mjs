import { readFile, writeFile } from 'fs/promises';
import { existsSync, readFileSync, writeFileSync } from 'fs';
import { resolve, dirname } from 'path';
import crypto from 'crypto';
import jetpack from 'fs-jetpack';
import Postcss from 'postcss';
import UrlVersion from 'postcss-url-version';

const opts = {
  version: (imagePath, sourceCssPath) => {
    if (!sourceCssPath) {
      return (new Date()).valueOf().toString().slice(-6);
    }

    if (!(imagePath.startsWith('http') || imagePath.startsWith('//')) && existsSync(`${dirname(sourceCssPath)}/${imagePath}`)) {
      const hashSum = crypto.createHash('md5');
      hashSum.update(readFileSync(resolve(`${dirname(sourceCssPath)}/${imagePath}`)));

      return (hashSum.digest('hex')).slice(-6);
    }

    return (new Date()).valueOf().toString().slice(-6);
  },
};

// update the php entry
function replaceFontVer(css) {
  const vers = {}
  const matches = [...css.matchAll(/url\(.+fa-(?<name>.+)\.woff2\?v=(?<ver>.+)\"\)\sformat\(\"woff2\"\),/gm)];

  for (const match of matches) {
    if (!vers[match.groups.name]) {
      vers[match.groups.name] = match.groups.ver;
    }
  }

  const fileContent = `<?php
defined('_JEXEC') || die;

return [
  ${Object.keys(vers).map((key) => `'${key}' => '${vers[key]}'`).join(',\n  ')}
];`;

  writeFileSync('src/templates/administrator/muta/src/Helper/versions.php', fileContent, { encoding: "utf8", mode: 0o644, flag: 'w' });
}

/**
 * Adds a hash to the url() parts of the static css
 *
 * @param file
 * @returns {Promise<void>}
 */
const fixVersion = async (file) => {
  try {
    const cssString = await readFile(file, { encoding: 'utf8' });
    const data = await Postcss([UrlVersion(opts)]).process(cssString, { from: file });
    await writeFile(file, data.css, { encoding: 'utf8', mode: 0o644 });

    console.log(`Versioning ${file}...`);
    if (file === 'media/templates/administrator/muta/css/vendor/fontawesome-free/fontawesome.min.css') {
      replaceFontVer(data.css);
    }
  } catch (error) {
    throw new Error(error);
  }
};

/**
 * Loop the media folder and add version to all url() entries in all the css files
 *
 * @returns {Promise<void>}
 */
async function cssVersioning() {
  const cssFiles = jetpack.find('media', { matching: '/**/**/*.css' });
  await Promise.all(cssFiles.map((file) => fixVersion(file)));
};

cssVersioning();

import { getFiles } from '@dgrammatiko/compress/src/getFiles.js';
import { compressFile } from '@dgrammatiko/compress/src/compressFile.js';

getFiles('./media/templates/administrator/muta/')
  .then(async files => {
    for (const file of files) {
      await compressFile(file, false);
    }

    console.log('Done 👍');
  })
  .catch(err => process.exit(err ? 1 : 0));
