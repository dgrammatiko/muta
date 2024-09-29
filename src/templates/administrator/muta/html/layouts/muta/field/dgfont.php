<?php

/**
 * @copyright   (C) 20223 Dimitris Grammatikogiannis
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

if (!$displayData['readonly']) {
  Factory::getApplication()->getDocument()->getWebAssetManager()->registerAndUseScript('form.field.dgfont', 'media/templates/administrator/muta/js/dgfont-field.js', ['version' => 'auto'], ['type'    => 'module'], ['core']);
}

echo LayoutHelper::render('joomla.form.field.list', $displayData);
