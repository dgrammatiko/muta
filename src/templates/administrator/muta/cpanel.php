<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Layout\LayoutHelper;

LayoutHelper::render('muta.params_evaluator', ['doc' => &$this, 'entry' => 'index']); // Evaluate runtime params

echo LayoutHelper::render('muta.entries', ['doc' => &$this, 'entry' => 'index']); // Render the template
