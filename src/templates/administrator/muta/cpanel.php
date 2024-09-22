<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Dgrammatiko\Template\Muta\Administrator\Helper\ParamsEvaluatorHelper;
use Joomla\CMS\Layout\LayoutHelper;

new ParamsEvaluatorHelper(['doc' => &$this, 'entry' => 'index']); // Evaluate runtime params

echo LayoutHelper::render('muta.html5_sceleton', ['doc' => &$this, 'entry' => 'index']); // Render the template
