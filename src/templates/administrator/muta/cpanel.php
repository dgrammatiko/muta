<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Dgrammatiko\Template\Muta\Administrator\Helper\ParamsEvaluatorHelper;
use Joomla\CMS\Layout\LayoutHelper;

// Evaluate runtime params
new ParamsEvaluatorHelper([
  'entry' => 'index',
  'path'   => __DIR__,
  'params' => $this->params,
  'wam'    => $this->getWebAssetManager(),
  'pem'    => $this->getPreloadManager()
]);

echo LayoutHelper::render('muta.html5_sceleton', [
  'doc'    => $this,
  'entry'  => 'index',
  'params' => $this->params->get('currentPage'),
]);
