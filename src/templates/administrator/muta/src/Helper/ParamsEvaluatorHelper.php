<?php

/**
 * @copyright   (C) 2023 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */

namespace Dgrammatiko\Template\Muta\Administrator\Helper;

use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') || die;

/**
 * Evaluate the state of Document for the given request
 *
 * @since  2.0.0
 */
class ParamsEvaluatorHelper extends HtmlDocument
{
  private $fontAwesomeUrl = 'media/templates/administrator/muta/fonts/fa-regular-400.woff2?v=';
  private $fallbackColors = [
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

  private $preloadScripts = ['core'];
  private $pem;
  private $wam;

  public function __construct($options) {
    $entry     = $options['entry'];
    $path      = $options['path'];
    $params    = $options['params'];
    $app       = Factory::getApplication();
    $input     = $app->getInput();
    $user      = $app->getIdentity();
    $option    = $input->get('option', '');
    $view      = $input->get('view', '');
    $layout    = $input->get('layout', 'default');
    $task      = $input->get('task', 'display');
    $this->wam = $options['wam'];
    $this->pem = $options['pem'];

    $this->setMetaData('viewport', 'width=device-width, initial-scale=1');

    /**
     * To set a custom favicon:
     * you could place a 32x32 PNG file named `favicon.png` in the folder `media/templates/administrator/muta/images`
     * PNG favicons are widely supported: https://caniuse.com/link-icon-png
     */
    $this->addHeadLink(HTMLHelper::_('image', 'favicon.png', '', [], true, 1), 'icon', 'rel', ['type' => 'image/png']);

    $currentPage = (object) [
      'lang'              => $this->language,
      'dir'               => $this->direction,
      'monochrome'        => (bool) $params->get('monochrome'),
      'a11y_mono'         => (bool) $user->getParam('a11y_mono', ''),
      'a11y_contrast'     => (bool) $user->getParam('a11y_contrast', ''),
      'a11y_highlight'    => (bool) $user->getParam('a11y_highlight', ''),
      'a11y_font'         => (bool) $user->getParam('a11y_font', ''),
      'forcedColorScheme' => (bool) $params->get('forcedColorScheme', false),
      'defaultFont'       => $params->get('defaultFont'),
      'monoFont'          => $params->get('monoFont'),
      'cpanel'            => $option === 'com_cpanel' || ($option === 'com_admin' && $view === 'help'),
      'hiddenMenu'        => $input->get('hidemainmenu'),
      'sidebarState'      => $input->cookie->get('mutaSidebarState', ''),
      'fallbackColours'   => (array) json_decode(file_get_contents(JPATH_ADMINISTRATOR . '/templates/muta/src/Field/def.json'), true),
      'colours'           => $params->get('muta-colors', '{}'),
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
      $htmlTagAttributes['data-bs-theme'] = $input->cookie->get('mutaPrefersColorScheme', $user->getParam('prefers_color_scheme', 'light')) . '"';
      $htmlTagAttributes['data-theme'] = $htmlTagAttributes['data-bs-theme'];
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
      $currentPage->loginLogo = $params->get('loginLogo') ? Uri::root() . $params->get('loginLogo') : Uri::root() . 'media/templates/administrator/muta/images/logos/login.svg';
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
    if (empty($currentPage->defaultFont)) {
      $currentPage->monoFont = 'SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace';
    }

    $colors = array_merge($currentPage->fallbackColours ?? [], $this->fallbackColors);

    $this->setMetaData('theme-color', 'hsl('. $colors['hue']. ',40%,20%)');
    $this->wam->registerAndUseStyle('fontawesome', 'media/templates/administrator/muta/css/vendor/fontawesome-free/fontawesome.min.css', ['version' => '6.4.0'], [], []);

    $this->wam->useStyle('fontawesome');

    Text::script('JGLOBAL_WARNCOOKIES');

    // Enable assets
    $this->wam->usePreset('template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr'))
      ->useStyle('template.active.language')
      ->useStyle('template.user')
      ->addInlineStyle(
        ':root {'
        .   implode('', array_map(function ($k, $v) { return '--' . $k . ':' . $v . ';'; }, array_keys($colors), array_values($colors)))
        .   '--bs-font-sans-serif:' . $currentPage->defaultFont . ',"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";'
        .   '--bs-font-monospace:' . $currentPage->monoFont . ',"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";'
        .   '--font-sans-serif: var(--bs-font-sans-serif);'
        .   '--font-monospace: var(--bs-font-monospace);'
        . '}',
        [],
        ['id' => 'tpl-muta-colors'])
      ->registerStyle('template.active', '', [], [], ['template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr')]);

    if ($entry === 'component') {
      $this->wam->disableScript('template.muta');
    }

    if (!in_array($entry, ['component', 'login']) && (!$currentPage->cpanel || !$currentPage->hiddenMenu)) {
      $this->wam->useScript('bootstrap.collapse');
    }

    $staticVersions = require_once __DIR__. '/versions.php';

    if (empty($staticVersions)) {
      $staticVersions = ['regular-400' => ''];
    }

    $this->preloader('style', ['template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr'), 'fontawesome', 'webcomponent.joomla-alert']);
    $this->preloader('script', $this->preloadScripts);
    $this->preloader('font', [Uri::root() . $this->fontAwesomeUrl . $staticVersions['regular-400']]);

    $params->set('currentPage', $currentPage);
  }

  private function preloader($type, $assets) {
    foreach ($assets as $asset) {
      if ($type === 'font') {
        $this->pem->preload($asset, ['as' => 'font', 'type' => 'font/woff2', 'crossorigin' => true]);
        continue;
      }
      $assetObj = $this->wam->getAsset($type, $asset);
      $url   = $assetObj->getUri();

      if (str_contains($url, '?')) {
        $this->pem->preload($url, ['as' => $type]);
        continue;
      }

      $ver = $assetObj->getVersion() !== 'auto' ? $assetObj->getVersion() : Factory::getApplication()->getDocument()->getMediaVersion();
      $this->pem->preload($url . '?' . $ver, ['as' => $type]);
    }
  }
}
