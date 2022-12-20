#!/usr/bin/env node
const { Command } = require('commander');
const { existsSync } = require('fs');
const chalk = require('chalk');
const { fetchJoomla } = require('./tools/fetchJoomla');
// const { resolveComponent } = require('./tools/resolvers/components')
const { symLink } = require('./tools/symLinks');
const { stylesheets } = require('./tools/compilecss');
const { scripts } = require('./tools/compilejs');
const { copyThru } = require('./tools/copythru');
const { logger } = require('./tools/utils/logger.js');


(async () => {
  const program = new Command();
  program
    .option('-i, --init', 'Initialise')
    .option('-l, --link [type]', 'Link')
    .option('-b, --build [type]', 'Build')
    .option('-r, --release', 'Release')
    .option('-w, --watch [type]', 'Watch');

  program.parse(process.argv);

  if (program.link) {
    if (!existsSync) {
      logger('Initializing...');
      await fetchJoomla('4.2.5');

    }
    logger(chalk.greenBright(`linking ${program.link}`));
    symLink()
  }

  if (program.build) {
    logger(chalk.greenBright(`building ${program.build}`));
    copyThru(program.build)
    stylesheets(program.build)
    scripts(program.build)
  }

  if (program.watch) logger(`watch type ${program.watch}`);

  if (program.release === true) logger('Release');

  if (program.init === true) {
    logger('Initializing...');
    fetchJoomla('4.2.5');
  }
})()

