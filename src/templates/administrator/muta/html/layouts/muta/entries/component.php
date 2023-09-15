<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

extract($displayData);

echo $renderPosition->message . $renderPosition->component;
