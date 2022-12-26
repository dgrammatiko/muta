const { default: axios } = require('axios');
const AdmZip = require('adm-zip');
const { existsSync } = require('fs');
const { resolve } = require('path');
const { mkdir } = require('fs').promises;

/**
 * Logic starts here
 */
const root = process.cwd();
const logger = (value) => process.stdout.write(`${value}\n`);

module.exports.fetchJoomla = async (version) => {
  if (!existsSync(resolve(root, 'www'))) {
    //https://github.com/joomla/joomla-cms/releases/download/4.1.2/Joomla_4.1.2-Stable-Full_Package.zip
    const { data } = await axios.get(`https://github.com/joomla/joomla-cms/releases/download/${version}/Joomla_${version}-Stable-Full_Package.zip`, { responseType: 'arraybuffer'});
    const zip = new AdmZip(data);
    await mkdir('www', { recursive: true });
    await zip.extractAllTo(resolve(root, 'www'), true);
  } else {
    logger('Joomla installation already exists, skipping clonning...');
  }
};
