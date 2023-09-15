<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use \Dgrammatiko\Template\Muta\Administrator\Helper\CompositorHelper;

new CompositorHelper([
  'entry' => basename($filename ?? __FILE__, '.php'),
  'path'  => __DIR__,
  'doc'   => $this,
  'wam'   => $this->getWebAssetManager()]);
