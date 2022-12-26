const jetpack = require('fs-jetpack');
const { sep } = require('path');
// const recursive = require('recursive-readdir');

const RootPath = process.cwd();

/**
 * Method that will crawl the media_source folder
 * and compile any scss files to css and .min.css
 * copy any css files to the appropriate destination and
 * minify them in place
 *
 * Expects scss files to have ext: .scss
 *         css files to have ext: .css
 * Ignores scss files that their filename starts with `_`
 *
 * @param {object} options  The options
 * @param {string} path     The folder that needs to be compiled, optional
 */
module.exports.copyThru = async (options, path) => {
  const files = [];
  let folders = [];

  if (path) {
    const stats = await stat(`${RootPath}/${path}`);

    if (stats.isDirectory()) {
      folders.push(`${RootPath}/${path}`);
    } else if (stats.isFile()) {
      files.push(`${RootPath}/${path}`);
    } else {
      // eslint-disable-next-line no-console
      console.error(`Unknown path ${path}`);
      process.exit(1);
    }
  } else {
    folders = [
      `${RootPath}/media_src`,
    ];
  }

  const fff = jetpack.find(`${RootPath}/media_src`, { matching: 'images', files: false, directories: true });

  for (const file of fff) {
    const outputFile = file.replace(`media_src${sep}`, `media${sep}`);
    jetpack.copy(file, outputFile, { overwrite: true });
  }
};
