const { default: axios } = require('axios');
const AdmZip = require('adm-zip');
const { existsSync } = require('fs');
const { resolve } = require('path');
const { mkdir } = require('fs').promises;
const semver = require('semver');

/**
 * Logic starts here
 */
const root = process.cwd();
const logger = (value) => process.stdout.write(`${value}\n`);

module.exports.fetchJoomla = async (version) => {
  if (existsSync(resolve(root, 'www'))) {
    logger('Joomla installation already exists, skipping clonning...');
    return;
  }

  /**
   * Possible urls
   * https://github.com/joomla/joomla-cms/releases/download/4.1.2/Joomla_4.1.2-Stable-Full_Package.zip
   * https://github.com/joomla/joomla-cms/releases/download/4.3.0-beta4/Joomla_4.3.0-beta4-Beta-Full_Package.zip
   * https://github.com/joomla/joomla-cms/releases/download/4.3.0-rc1/Joomla_4.3.0-rc1-Release_Candidate-Full_Package.zip
   */
  const ver = semver.parse(version);
  let suffix = '-Stable-Full_Package';
  if (/rc/.test(ver.prerelease[0])) {
    suffix = '-Release_Candidate-Full_Package';
  } else if (/beta/.test(ver.prerelease[0])) {
    suffix = '-Beta-Full_Package';
  }

  const url = `https://github.com/joomla/joomla-cms/releases/download/${ver.version}/Joomla_${ver.version}${suffix}.zip`;
  const { data } = await axios.get(url, { responseType: 'arraybuffer' });
  const zip = new AdmZip(data);
  await mkdir('www', { recursive: true });
  await zip.extractAllTo(resolve(root, 'www'), true);
};
