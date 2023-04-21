<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;

$doc    = $displayData['doc'];
$params = $displayData['params'];
$entry  = $displayData['entry'];
$app    = Factory::getApplication();
$input  = $app->getInput();
$user   = $app->getIdentity();
$wa     = $doc->getWebAssetManager();
$option = $input->get('option', '');
$view   = $input->get('view', '');

// Getting user accessibility settings
$doc->monochrome         = (bool) $params->get('monochrome');
$doc->a11y_mono          = (bool) $user->getParam('a11y_mono', '');
$doc->a11y_contrast      = (bool) $user->getParam('a11y_contrast', '');
$doc->a11y_highlight     = (bool) $user->getParam('a11y_highlight', '');
$doc->a11y_font          = (bool) $user->getParam('a11y_font', '');
$doc->forcedColorScheme  = (bool) $params->get('forcedColorScheme', false);
$doc->prefersColorScheme = !$doc->forcedColorScheme ? '' : ' data-bs-theme="' . $input->cookie->get('mutaPrefersColorScheme', $user->getParam('prefers_color_scheme', 'light')) . '"';
$doc->cpanel             = $option === 'com_cpanel' || ($option === 'com_admin' && $view === 'help');
$doc->hiddenMenu         = $input->get('hidemainmenu');
$doc->sidebarState       = $input->cookie->get('mutaSidebarState', '');
$defaultValue            = (array) json_decode(file_get_contents(JPATH_ADMINISTRATOR . '/templates/muta/src/Field/def.json'), true);

try {
  $json = json_decode($params->get('muta-colors', '{}'), true);
} catch (\Exception $e) {
  $json = [];
}
if (!$json) $json = [];
$colors = array_merge($defaultValue, $json);
$doc->setMetaData('theme-color', 'hsl('. $colors['hue']. ',40%,20%)');

// Enable assets
$wa->usePreset('template.muta.' . ($doc->direction === 'rtl' ? 'rtl' : 'ltr'))
  ->useStyle('template.active.language')
  ->useStyle('template.user')
  ->addInlineStyle(':root {' . implode('', array_map(function($k, $v) { return '--'.$k.':'.$v.';'; }, array_keys($colors), array_values($colors))). '}', [], ['id' => 'tpl-muta-colors'])
  ->registerStyle('template.active', '', [], [], ['template.muta.' . ($doc->direction === 'rtl' ? 'rtl' : 'ltr')]);

if ($entry === 'component') {
  $wa->disableScript('template.muta');
}
