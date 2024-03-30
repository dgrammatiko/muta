<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Dgrammatiko\Template\Muta\Administrator\Helper\ParamsEvaluatorHelper;
use Joomla\CMS\Layout\LayoutHelper;

$entry = basename(__FILE__, '.php');

// Evaluate runtime params
new ParamsEvaluatorHelper([
  'entry'  => $entry,
  'path'   => __DIR__,
  'params' => $this->params,
  'wam'    => $this->getWebAssetManager(),
  'pem'    => $this->getPreloadManager()
]);

echo LayoutHelper::render('muta.html5_sceleton', [
  'doc'    => $this,
  'entry'  => $entry,
  'params' => $this->params->get('currentPage'),
]);
