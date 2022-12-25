const { readFile } = require('fs').promises;
const { readdirSync, existsSync } = require('fs');
const admZip = require('adm-zip');

const { version } = require('./package.json');
const noRootPath = `src/templates/administrator/muta`;

const zip = new admZip();

readdirSync(`${process.cwd()}/src/templates/administrator/muta`, { withFileTypes: true }).filter(item => !/(^|\/)\.[^/.]/g.test(item.name)).forEach(file => {
  if (file.isDirectory()) {
    zip.addLocalFolder(`${noRootPath}/${file.name}`, file.name, /^(?!\.DS_Store)/);
  } else {
    zip.addLocalFile(`${noRootPath}/${file.name}`, false);
  }
});

readdirSync(`${process.cwd()}/media/templates/administrator/muta`, { withFileTypes: true }).filter(item => !/(^|\/)\.[^/.]/g.test(item.name)).forEach(file => {
  if (file.isDirectory()) {
    zip.addLocalFolder(`${process.cwd()}/media/templates/administrator/muta/${file.name}`, `media/${file.name}`, /^(?!\.DS_Store)/);
  } else {
    zip.addLocalFile(`${process.cwd()}/media/templates/administrator/muta/${file.name}`, false);
  }
});

zip.writeZip(`dist/tpl_muta_${version}.zip`, zip.data)
