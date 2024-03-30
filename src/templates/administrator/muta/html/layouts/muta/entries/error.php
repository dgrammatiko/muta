<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);

echo LayoutHelper::render('muta.partials.header', ['doc' => $doc, 'entry' => $entry, 'params' => $params]);
echo $this->sublayout(($params->isGuest ? 'login' : 'full'), ['doc' => $doc, 'entry' => $entry]);
