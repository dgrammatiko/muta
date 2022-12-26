#!/usr/bin/env node
const { Command } = require('commander');
const { existsSync } = require('fs');
const { fetchJoomla } = require('./tools/fetchJoomla');
const { symLink } = require('./tools/symLinks');
const { stylesheets } = require('./tools/compilecss');
const { scripts } = require('./tools/compilejs');
const { copyThru } = require('./tools/copythru');
const { logger } = require('./tools/utils/logger.js');
const { zip } = require('./tools/zip');

const program = new Command();

(async () => {
  program
    .option('-i, --init', 'Initialise')
    .option('-l, --link [type]', 'Link')
    .option('-b, --build [type]', 'Build')
    .option('-r, --release', 'Release')
    .option('-w, --watch [type]', 'Watch');

  program.parse(process.argv);

  const options = program.opts();
  if (options.link) {
    if (!existsSync) {
      logger('Initializing...');
      await fetchJoomla('4.2.5');

    }
    logger(`linking ${options.link}`);
    symLink()
  }

  if (options.build) {
    logger(`building ${options.build}`);
    copyThru(options.build)
    stylesheets(options.build)
    scripts(options.build)
  }

  if (options.watch) logger(`watch type ${options.watch}`);

  if (options.release === true) {
    logger('Release');
    zip();
  }

  if (options.init === true) {
    logger('Initializing...');
    fetchJoomla('4.2.5');
  }
})()

