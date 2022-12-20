const { default: axios } = require('axios');
const AdmZip = require('adm-zip');
const { existsSync, readdirSync, mkdirSync } = require('fs');
const { resolve } = require('path');
const { mkdir } = require('fs').promises;
const jet = require('fs-jetpack');
const symlinkDir = require('symlink-dir')

module.exports.symLink = async () => {
  if (!existsSync('./src') || !existsSync('./media')) {
    throw new Error('There are no extensions ')
  }

  if (existsSync(`./src/components`)) {
    readdirSync(`./src/components`).forEach(ext => {
      if (existsSync(`./src/components/${ext}/admin`)) {
        symlinkDir(`${process.cwd()}/src/components/${ext}/admin`, `./www/administrator/components/com_${ext}`);
      }
      if (existsSync(`./src/components/${ext}/site`)) {
        symlinkDir(`${process.cwd()}/src/components/${ext}/site`, `./www/components/com_${ext}`);
      }
    })
  }
  if (existsSync(`./src/modules`)) {
    if (existsSync(`./src/modules/administrator`)) {
      readdirSync(`./src/modules/administrator`).forEach(ext => symlinkDir(`${process.cwd()}/src/modules/administrator/${ext}`, `./www/administrator/modules/mod_${ext}`))
    }
    if (existsSync(`./src/modules/site`)) {
      readdirSync(`./src/modules/site`).forEach(ext => symlinkDir(`${process.cwd()}/src/modules/site/${ext}`, `./www/modules/mod_${ext}`))
    }
  }
  if (existsSync(`./src/templates`)) {
    if (existsSync(`./src/templates/administrator`)) {
      readdirSync(`./src/templates/administrator`).forEach(ext => symlinkDir(`${process.cwd()}/src/templates/administrator/${ext}`, `./www/administrator/templates/${ext}`))
    }
    if (existsSync(`./src/templates/site`)) {
      readdirSync(`./src/templates/site`).forEach(ext => symlinkDir(`${process.cwd()}/src/templates/site/${ext}`, `./www/templates/${ext}`))
    }
  }
  if (existsSync(`./src/libraries`)) {
      readdirSync(`./src/libraries`).forEach(ext => symlinkDir(`${process.cwd()}/src/libraries/${ext}`, `./www/libraries/${ext}`))
  }
  if (existsSync(`./media`)) {
    readdirSync(`./media`).forEach(ext => {
      if (ext !== 'templates') {
        symlinkDir(`${process.cwd()}/media/${ext}`, `./www/media/${ext}`)
      } else {
        if (existsSync(`./media/templates/administrator`)) {
          readdirSync(`./media/templates/administrator`).forEach(exta => {
            if (!existsSync(`${process.cwd()}/media/templates/administrator/${exta}`)) mkdirSync(`${process.cwd()}/media/templates/administrator/${exta}`, {recursive: true})
            symlinkDir(`${process.cwd()}/media/templates/administrator/${exta}`, `./www/media/templates/administrator/${exta}`)
          })
        }
        if (existsSync(`./media/templates/site`)) {
          readdirSync(`./media/templates/site`).forEach(exta => {
            if (!existsSync(`${process.cwd()}/media/templates/site/${exta}`)) mkdirSync(`${process.cwd()}/media/templates/site/${exta}`, {recursive: true})
            symlinkDir(`${process.cwd()}/media/templates/site/${exta}`, `./www/media/templates/site/${exta}`)
          })
        }
      }
    })
  }

  if (existsSync(`./media/templates/administrator`)) {
      readdirSync(`./media/templates/administrator`).forEach(ext => {
        symlinkDir(`${process.cwd()}/media/templates/administrator/${ext}`, `./www/media/templates/administrator/${ext}`)
      })
  }
  if (existsSync(`./media/templates/site`)) {
      readdirSync(`./media/templates/site`).forEach(ext => {
        symlinkDir(`${process.cwd()}/media/templates/site/${ext}`, `./www/media/templates/site/${ext}`)
      })
  }
};

/**
 *   symlinkDir(`./src/modules/admin`, `./www/administrator/modules/mod_${ext}`);
  ['components', 'modules', 'plugins', 'templates', 'libraries'].forEach(parentFolder => {
    readdirSync(`./src/${parentFolder}`).forEach(ext => {
      if (parentFolder === 'components') {
        if (existsSync(`./src/${parentFolder}/${ext}/admin`)) {
          symlinkDir(`./src/${parentFolder}/${ext}/admin`, `./www/administrator/components/com_${ext}`);
        }
        if (existsSync(`./src/${parentFolder}/${ext}/site`)) {
          symlinkDir(`./src/${parentFolder}/${ext}/site`, `./www/components/com_${ext}`);
        }
      }
      if (parentFolder === 'modules') {
        readdirSync(`./src/${parentFolder}/administrator/${ext}`).forEach(mod => {
          symlinkDir(`./src/${parentFolder}/administrator/${ext}`, `./www/administrator/modules/mod_${ext}`);
        })
        if (existsSync(`./src/${parentFolder}/administrator/${ext}`)) {
        }
        if (existsSync(`./src/${parentFolder}/site/${ext}`)) {
          symlinkDir(`./src/${parentFolder}/site/${ext}`, `./www/modules/mod_${ext}`);
        }
      }
    })
  })
 */
