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
$doc->sidebarState       = $input->cookie->get('mutaSidebarState', '');
$defaultValue            = '{"hue":"214","template-bg-light":"#f0f4fb","template-text-dark":"#495057","template-text-light":"#fff","template-link-color":"rgb(56, 85, 173)","template-special-color":"#0d6efd","template-sidebar-bg":"var(--template-bg-dark-80)","template-sidebar-font-color":"#fff","template-sidebar-link-color":"#fff","template-link-hover-color":"rgb(20, 45, 97)","template-contrast":"#0d6efd","template-bg-dark":"hsl(var(--hue), 40%, 20%)","template-bg-dark-3":"hsl(var(--hue), 40%, 97%)","template-bg-dark-5":"hsl(var(--hue), 40%, 95%)","template-bg-dark-7":"hsl(var(--hue), 40%, 93%)","template-bg-dark-10":"hsl(var(--hue), 40%, 90%)","template-bg-dark-15":"hsl(var(--hue), 40%, 85%)","template-bg-dark-20":"hsl(var(--hue), 40%, 80%)","template-bg-dark-30":"hsl(var(--hue), 40%, 70%)","template-bg-dark-40":"hsl(var(--hue), 40%, 60%)","template-bg-dark-50":"hsl(var(--hue), 40%, 50%)","template-bg-dark-60":"hsl(var(--hue), 40%, 40%)","template-bg-dark-65":"hsl(var(--hue), 40%, 35%)","template-bg-dark-70":"hsl(var(--hue), 40%, 30%)","template-bg-dark-75":"hsl(var(--hue), 40%, 25%)","template-bg-dark-80":"hsl(var(--hue), 40%, 20%)","template-bg-dark-90":"hsl(var(--hue), 40%, 10%)","bs-link-color-rgb":"56, 85, 173","link-color":"#3855AD","bs-link-hover-color-rgb":"20, 45, 97","bs-link-hover-color":"#142D61"}';

try {
  $json = json_decode($params->get('muta-colors', '{}'), true);
} catch (\Exception $e) {
  $json = [];
}
if (!$json) $json = [];
$colors = array_merge(json_decode($defaultValue, true), $json);
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
