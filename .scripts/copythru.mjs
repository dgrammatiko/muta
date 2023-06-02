import { cwd, exit } from 'node:process';
import { join, sep } from 'node:path';
import { stat, existsSync } from 'node:fs';
import jetpack from 'fs-jetpack';

/** text
 * Method that will crawl the media_source folder
 * and compile any scss files to css and .min.css
 * copy any css files to the appropriate destination and
 * minify them in place
 *
 * Expects scss files to have ext: .scss
 *         css files to have ext: .css
 * Ignores scss files that their filename starts with `_`
 *
 * @param {string} path     The folder that needs to be compiled, optional
 */
async function copyThru(path) {
  if (!existsSync(join(cwd(), 'media_source'))) {
    console.log('The tools aren\'t initialized properly. Exiting');
    exit(1);
  }

  const files = [];
  const folders = [];

  if (path) {
    const stats = await stat(`${cwd()}/${path}`);

    if (stats.isDirectory()) {
      folders.push(`${cwd()}/${path}`);
    } else if (stats.isFile()) {
      files.push(`${cwd()}/${path}`);
    } else {
      logger(`Unknown path ${path}`);
      process.exit(1);
    }
  } else {
    folders.push(`${cwd()}/media_source`);
  }

  jetpack
    .find(`${cwd()}/media_source`, { matching: 'images', files: false, directories: true })
    .forEach((file) => jetpack.copy(file, file.replace(`media_source${sep}`, `media${sep}`), { overwrite: true }));
  jetpack
    .find(`${cwd()}/media_source`, { matching: 'fonts', files: false, directories: true })
    .forEach((file) => jetpack.copy(file, file.replace(`media_source${sep}`, `media${sep}`), { overwrite: true }));
};

export {copyThru};
