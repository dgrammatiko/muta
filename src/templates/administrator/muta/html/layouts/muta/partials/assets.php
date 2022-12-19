<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;

$doc    = $displayData['doc'];
$params = $displayData['params'];
$app    = Factory::getApplication();
$input  = $app->getInput();
$user   = $app->getIdentity();
$wa     = $doc->getWebAssetManager();
$option = $input->get('option', '');
$view   = $input->get('view', '');
$layout = $input->get('layout', 'default');
$task   = $input->get('task', 'display');

// Getting user accessibility settings
$doc->monochrome         = (bool) $params->get('monochrome');
$doc->a11y_mono          = (bool) $user->getParam('a11y_mono', '');
$doc->a11y_contrast      = (bool) $user->getParam('a11y_contrast', '');
$doc->a11y_highlight     = (bool) $user->getParam('a11y_highlight', '');
$doc->a11y_font          = (bool) $user->getParam('a11y_font', '');
$doc->a11yColorScheme    = $user->getParam('prefers_color_scheme', '');
$doc->prefersColorScheme = !empty($a11yColorScheme) ? $a11yColorScheme : 'light';
$doc->prefersColorScheme = $input->cookie->get('mutaPrefersColorScheme', 'light');
$doc->cpanel             = $option === 'com_cpanel' || ($option === 'com_admin' && $view === 'help');
$doc->hiddenMenu         = $input->get('hidemainmenu');
$doc->sidebarState       = $input->cookie->get('atumSidebarState', '');

// Get the hue value
preg_match('#^hsla?\(([0-9]+)[\D]+([0-9]+)[\D]+([0-9]+)[\D]+([0-9](?:.\d+)?)?\)$#i', $params->get('hue', 'hsl(214, 63%, 20%)'), $matches);

// Enable assets
$wa->usePreset('template.muta.' . ($doc->direction === 'rtl' ? 'rtl' : 'ltr'))
  ->useStyle('template.active.language')
  ->useStyle('template.user')
  ->addInlineStyle(':root {
		--hue: ' . $matches[1] . ';
		--template-bg-light: ' .    $params->get('bg-light', '#f0f4fb') . ';
		--template-text-dark: ' .   $params->get('text-dark', '#495057') . ';
		--template-text-light: ' .  $params->get('text-light', '#ffffff') . ';
		--template-link-color: ' .  $params->get('link-color', '#2a69b8') . ';
		--template-special-color: ' . $params->get('special-color', '#001B4C') . ';
	}');

$doc->setMetaData('theme-color', 'hsl('. $matches[1]. ',40%,20%)');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.muta.' . ($doc->direction === 'rtl' ? 'rtl' : 'ltr')]);
