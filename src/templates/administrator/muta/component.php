<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Layout\LayoutHelper;

$currentFileName = basename(__FILE__, '.php');

/*
 * Set custom favicons
 * @see administrator/templates/muta/html/layouts/muta/favicons.php
 */
LayoutHelper::render('muta.partials.favicons', ['doc' => $this, 'params' => $this->params, 'entry' => $currentFileName]);

/*
 * Set the assets
 * @see administrator/templates/muta/html/layouts/muta/assets.php
 */
LayoutHelper::render('muta.partials.assets', ['doc' => $this, 'params' => $this->params, 'entry' => $currentFileName]);

/*
 * Render the HTML
 * @see administrator/templates/muta/html/layouts/muta/partials/login.php
 */
echo LayoutHelper::render('muta.entries.component', ['doc' => $this, 'params' => $this->params, 'entry' => $currentFileName]);
