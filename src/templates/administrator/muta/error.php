<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Dgrammatiko\Template\Muta\Administrator\Helper\ParamsEvaluatorHelper;
use Joomla\CMS\Layout\LayoutHelper;

$par = ['doc' => &$this, 'entry' => basename(__FILE__, '.php')];

// Evaluate runtime params
new ParamsEvaluatorHelper($par);

echo LayoutHelper::render('muta.html5_sceleton', $par);

