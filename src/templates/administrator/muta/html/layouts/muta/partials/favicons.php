<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\HTML\HTMLHelper;

$doc   = $displayData['doc'];
$entry = $displayData['entry'];

$doc->setMetaData('viewport', 'width=device-width, initial-scale=1');
$doc->addHeadLink(HTMLHelper::_('image', 'favicon.png', '', [], true, 1), 'icon', 'rel', ['type' => 'image/png']);

/**
 * To set a custom favicon:
 * - you could just place a 32x32 PNG file named `favicon.png` in the folder `media/templates/administrator/muta/images`
 * - or create a Layout override of this Layout file and set it manually.
 * PNG favicons are widely supported: https://caniuse.com/link-icon-png
 */
