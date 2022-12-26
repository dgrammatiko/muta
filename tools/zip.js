const { readdirSync, existsSync, mkdirSync } = require('fs');
const admZip = require('adm-zip');

const { version } = require('../package.json');
const noRootPath = `templates/administrator/muta`;

const zip = new admZip();

module.exports.zip = () => {
  readdirSync(`${process.cwd()}/src/${noRootPath}`, { withFileTypes: true }).filter(item => !/(^|\/)\.[^/.]/g.test(item.name)).forEach(file => {
    if (file.isDirectory()) {
      zip.addLocalFolder(`src/${noRootPath}/${file.name}`, file.name, /^(?!\.DS_Store)/);
    } else {
      zip.addLocalFile(`src/${noRootPath}/${file.name}`, false);
    }
  });

  readdirSync(`${process.cwd()}/media/${noRootPath}`, { withFileTypes: true }).filter(item => !/(^|\/)\.[^/.]/g.test(item.name)).forEach(file => {
    if (file.isDirectory()) {
      zip.addLocalFolder(`${process.cwd()}/media/${noRootPath}/${file.name}`, `media/${file.name}`, /^(?!\.DS_Store)/);
    } else {
      zip.addLocalFile(`${process.cwd()}/media/${noRootPath}/${file.name}`, false);
    }
  });

  if (!existsSync('./dist')) mkdirSync('dist');
  zip.writeZip(`dist/tpl_muta_${version}.zip`, zip.data);
}

