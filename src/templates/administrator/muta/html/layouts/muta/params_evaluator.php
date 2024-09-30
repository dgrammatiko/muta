<?php

/**
 * @copyright  Copyright (C) 2024 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Dgrammatiko\Template\Muta\Administrator\Helper\PreloaderHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

extract($displayData);

$preloaderClass = new PreloaderHelper;
$preloadScripts = ['core'];
$fontAwesomeUrl = 'media/templates/administrator/muta/fonts/fa-{entry}.woff2?v=';
$params         = $doc->params;
$app            = Factory::getApplication();
$input          = $app->getInput();
$user           = $app->getIdentity();
$option         = $input->get('option', '');
$view           = $input->get('view', '');
$layout         = $input->get('layout', 'default');
$task           = $input->get('task', 'display');
$wam            = $doc->getWebAssetManager();
$pem            = $doc->getPreloadManager();
$fallbackColors = [
'bs-primary'                  => '#5087E0',
'bs-link-color'               => '#5087E0',
'link-color'                  => '#5087E0',
'bs-primary-rgb'              => '80,135,224',
'bs-link-color-rgb'           => '80,135,224',
'link-color-rgb'              => '80,135,224',
'bs-link-hover-color'         => '#144AAB',
'link-hover-color'            => '#144AAB',
'template-link-hover-color'   => '#144AAB',
'bs-link-hover-color-rgb'     => '20, 74, 171',
'template-link-color'         => '#5087E0',
'template-special-color'      => '#5087E0',
'link-hover-color-rgb'        => '20, 74, 171',
'template-contrast'           => '#144AAB',
'hue'                         => '214',
'template-bg-light'           => '#f0f4fb',
'template-text-dark'          => '#495057',
'template-text-light'         => '#fff',
'template-sidebar-bg'         => 'var(--template-bg-dark-80)',
'template-sidebar-font-color' => '#fff',
'template-sidebar-link-color' => '#fff',
'template-bg-dark'            => 'hsl(var(--hue), 40%, 20%)',
'template-bg-dark-3'          => 'hsl(var(--hue), 40%, 97%)',
'template-bg-dark-5'          => 'hsl(var(--hue), 40%, 95%)',
'template-bg-dark-7'          => 'hsl(var(--hue), 40%, 93%)',
'template-bg-dark-10'         => 'hsl(var(--hue), 40%, 90%)',
'template-bg-dark-15'         => 'hsl(var(--hue), 40%, 85%)',
'template-bg-dark-20'         => 'hsl(var(--hue), 40%, 80%)',
'template-bg-dark-30'         => 'hsl(var(--hue), 40%, 70%)',
'template-bg-dark-40'         => 'hsl(var(--hue), 40%, 60%)',
'template-bg-dark-50'         => 'hsl(var(--hue), 40%, 50%)',
'template-bg-dark-60'         => 'hsl(var(--hue), 40%, 40%)',
'template-bg-dark-65'         => 'hsl(var(--hue), 40%, 35%)',
'template-bg-dark-70'         => 'hsl(var(--hue), 40%, 30%)',
'template-bg-dark-75'         => 'hsl(var(--hue), 40%, 25%)',
'template-bg-dark-80'         => 'hsl(var(--hue), 40%, 20%)',
'template-bg-dark-90'         => 'hsl(var(--hue), 40%, 10%)'
];

$doc->setMetaData('viewport', 'width=device-width, initial-scale=1');

/**
 * To set a custom favicon:
 * you could place a 32x32 PNG file named `favicon.png` in the folder `media/templates/administrator/muta/images`
 * PNG favicons are widely supported: https://caniuse.com/link-icon-png
 */
$doc->addHeadLink(HTMLHelper::_('image', 'favicon.png', '', [], true, 1), 'icon', 'rel', ['type' => 'image/png']);

$currentPage = (object) [
  'lang'              => $doc->language,
  'dir'               => $doc->direction,
  'monochrome'        => (bool) $params->get('monochrome', false),
  'a11y_mono'         => (bool) $user->getParam('a11y_mono', false),
  'a11y_contrast'     => (bool) $user->getParam('a11y_contrast', false),
  'a11y_highlight'    => (bool) $user->getParam('a11y_highlight', false),
  'a11y_font'         => (bool) $user->getParam('a11y_font', false),
  'forcedColorScheme' => (bool) $params->get('forcedColorScheme', false),
  'defaultFont'       => $params->get('defaultFont'),
  'monoFont'          => $params->get('monoFont'),
  'cpanel'            => $option === 'com_cpanel' || ($option === 'com_admin' && $view === 'help'),
  'hiddenMenu'        => $input->get('hidemainmenu', false),
  'sidebarState'      => $input->cookie->get('mutaSidebarState', ''),
  'fallbackColours'   => (array) json_decode(file_get_contents(JPATH_ADMINISTRATOR . '/templates/muta/src/Field/def.json'), true),
  'colours'           => json_decode((string) $params->get('muta-colors', '{}'), true),
  'sitename'          => $app->get('sitename'),
  'view'              => $view,
  'isGuest'           => (bool) $user->guest,
];

$htmlTagAttributes = [
  'dir'  => $currentPage->dir,
  'lang' => $currentPage->lang,
];

if ($currentPage->forcedColorScheme) {
  $htmlTagAttributes['data-forced-theme'] = '';
  $htmlTagAttributes['data-bs-theme']     = $input->cookie->get('mutaPrefersColorScheme', $user->getParam('prefers_color_scheme', 'light')) . '"';
  $htmlTagAttributes['data-theme']        = $htmlTagAttributes['data-bs-theme'];
}

$bodyClasses = '';

if ($entry === 'index') {
  $bodyClasses .= 'admin ' . $option . ' view-' . $view . ' layout-' . $layout . ($task ? ' task-' . $task : '') . ($currentPage->monochrome || $currentPage->a11y_mono ? ' monochrome' : '') . ($currentPage->a11y_contrast ? ' a11y_contrast' : '') . ($currentPage->a11y_highlight ? ' a11y_highlight' : '');
}
if ($entry === 'component') {
  $bodyClasses .= 'contentpane component';
}

$currentPage->bodyTagAttributes = ['class' => $bodyClasses];
$currentPage->htmlTagAttributes = $htmlTagAttributes;

if (($entry === 'login')) {
  $currentPage->loginLogo    = $params->get('loginLogo') ? Uri::root() . $params->get('loginLogo') : Uri::root() . 'media/templates/administrator/muta/images/logos/login.svg';
  $currentPage->loginLogoAlt = empty($params->get('loginLogoAlt')) ? '' : htmlspecialchars($params->get('loginLogoAlt', ''), ENT_COMPAT, 'UTF-8');
  $currentPage->loginLogoAlt = empty($params->get('emptyLoginLogoAlt')) && $currentPage->loginLogoAlt === '' ? false : $currentPage->loginLogoAlt;
}

$currentPage->logoBrandLarge    = $params->get('logoBrandLarge') ? Uri::root() . htmlspecialchars($params->get('logoBrandLarge'), ENT_QUOTES) : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-large.svg';
$currentPage->logoBrandSmall    = $params->get('logoBrandSmall') ? Uri::root() . htmlspecialchars($params->get('logoBrandSmall'), ENT_QUOTES) : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-small.svg';
$currentPage->logoBrandLargeAlt = empty($params->get('logoBrandLargeAlt')) ? '' : htmlspecialchars($params->get('logoBrandLargeAlt', ''), ENT_COMPAT, 'UTF-8');
$currentPage->logoBrandLargeAlt = empty($params->get('emptyLogoBrandLargeAlt')) && $currentPage->logoBrandLargeAlt === '' ? false : $currentPage->logoBrandLargeAlt;
$currentPage->logoBrandSmallAlt = empty($params->get('logoBrandSmallAlt')) ? '' : htmlspecialchars($params->get('logoBrandSmallAlt', ''), ENT_COMPAT, 'UTF-8');
$currentPage->logoBrandSmallAlt = empty($params->get('emptyLogoBrandSmallAlt')) && $currentPage->logoBrandSmallAlt === '' ? false : $currentPage->logoBrandSmallAlt;

if (empty($currentPage->defaultFont)) {
  $currentPage->defaultFont = 'system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans","Liberation Sans",Arial,sans-serif';
}
if (empty($currentPage->monoFont)) {
  $currentPage->monoFont = 'SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace';
}

$colors = array_merge($fallbackColors, $currentPage->colours ?? []);

$doc->setMetaData('theme-color', 'hsl(' . $colors['hue'] . ',40%,20%)');
$wam->registerAndUseStyle('fontawesome', 'media/templates/administrator/muta/css/vendor/fontawesome-free/fontawesome.min.css', ['version' => '6.4.0'], [], []);
$wam->useStyle('fontawesome');

Text::script('JGLOBAL_WARNCOOKIES');

// Enable assets
$wam->usePreset('template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr'))
  ->useStyle('template.active.language')
  ->useStyle('template.user')
  ->addInlineStyle(
    ':root {'
      .   implode('', array_map(function ($k, $v) {
        return '--' . $k . ':' . $v . ';';
      }, array_keys($colors), array_values($colors)))
      .   '--bs-font-sans-serif:' . $currentPage->defaultFont . ',"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";'
      .   '--bs-font-monospace:' . $currentPage->monoFont . ',"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";'
      .   '--font-sans-serif: var(--bs-font-sans-serif);'
      .   '--font-monospace: var(--bs-font-monospace);'
      . '}',
    [],
    ['id' => 'tpl-muta-colors']
  )
  ->registerStyle('template.active', '', [], [], ['template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr')]);

if ($entry === 'component') {
  $wam->disableScript('template.muta');
}

if (!in_array($entry, ['component', 'login']) && (!$currentPage->cpanel || !$currentPage->hiddenMenu)) {
  $wam->useScript('bootstrap.collapse');
}

try {
  $staticVersions = $preloaderClass->getFontVersions();
} catch (\Exception $e) {
}

if (empty($staticVersions)) {
  $staticVersions = ['regular-400' => 'b5120c', 'solid-900' => '567dd3', 'brands-400' => '98f5be'];
}

$preloaderClass->preload('style', ['template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr'), 'fontawesome', 'webcomponent.joomla-alert'], $pem, $wam, $app);
$preloaderClass->preload('script', $preloadScripts, $pem, $wam, $app);

foreach ($staticVersions as $faFont => $ver) {
  $preloaderClass->preload('font', [Uri::root() . str_replace('{entry}', $faFont, $fontAwesomeUrl) . $ver], $pem, $wam, $app);
}

$params->set('currentPage', $currentPage);
