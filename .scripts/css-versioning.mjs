import { readFile, writeFile } from 'fs/promises';
import { existsSync, readFileSync } from 'fs';
import { resolve, dirname } from 'path';
import crypto from 'crypto';
import jetpack from 'fs-jetpack';
import Postcss from 'postcss';
import UrlVersion from 'postcss-url-version';

const opts = {
  version: (imagePath, sourceCssPath) => {
    if (!sourceCssPath) {
      return (new Date()).valueOf().toString();
    }

    const directory = dirname(sourceCssPath);
    if (!(imagePath.startsWith('http') || imagePath.startsWith('//'))) {
      if (existsSync(resolve(`${directory}/${imagePath}`))) {
        const fileBuffer = readFileSync(resolve(`${directory}/${imagePath}`));
        const hashSum = crypto.createHash('md5');
        hashSum.update(fileBuffer);

        return (hashSum.digest('hex')).substring(0, 6);
      } else {
        return (new Date()).valueOf().toString();
      }
    }

    return (new Date()).valueOf().toString();
  },
};

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
