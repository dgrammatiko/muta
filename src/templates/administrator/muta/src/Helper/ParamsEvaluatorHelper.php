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
  public function __construct($options) {
    $entry = $options['entry'];
    $path = $options['path'];
    $params = $options['params'];
    $wam = $options['wam'];

    $app    = Factory::getApplication();
    $input  = $app->getInput();
    $user   = $app->getIdentity();
    $option = $input->get('option', '');
    $view   = $input->get('view', '');
    $layout = $input->get('layout', 'default');
    $task   = $input->get('task', 'display');

    $this->setMetaData('viewport', 'width=device-width, initial-scale=1');

    /**
     * To set a custom favicon:
     * - you could just place a 32x32 PNG file named `favicon.png` in the folder `media/templates/administrator/muta/images`
     * - or create a Layout override of this Layout file and set it manually.
     * PNG favicons are widely supported: https://caniuse.com/link-icon-png
     */
    $this->addHeadLink(HTMLHelper::_('image', 'favicon.png', '', [], true, 1), 'icon', 'rel', ['type' => 'image/png']);

    $currentPage = (object) [
    'lang'               => $this->language,
    'dir'                => $this->direction,
    'monochrome'         => (bool) $params->get('monochrome'),
    'a11y_mono'          => (bool) $user->getParam('a11y_mono', ''),
    'a11y_contrast'      => (bool) $user->getParam('a11y_contrast', ''),
    'a11y_highlight'     => (bool) $user->getParam('a11y_highlight', ''),
    'a11y_font'          => (bool) $user->getParam('a11y_font', ''),
    'forcedColorScheme'  => (bool) $params->get('forcedColorScheme', false),
    'defaultFont'        => $params->get('defaultFont', 'system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans","Liberation Sans",Arial,sans-serif'),
    'monoFont'           => $params->get('monoFont', 'SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace'),
    'cpanel'             => $option === 'com_cpanel' || ($option === 'com_admin' && $view === 'help'),
    'hiddenMenu'         => $input->get('hidemainmenu'),
    'sidebarState'       => $input->cookie->get('mutaSidebarState', ''),
    'fallbackColours'    => (array) json_decode(file_get_contents(JPATH_ADMINISTRATOR . '/templates/muta/src/Field/def.json'), true),
    'colours'            => $params->get('muta-colors', '{}'),
    'sitename'           => $app->get('sitename'),
    'view'               => $view,
    'isGuest'            => (bool) $user->guest,
  ];

  $htmlTagAttributes = [
    'dir'  => $currentPage->dir,
    'lang' => $currentPage->lang,
  ];

  if ($currentPage->forcedColorScheme) {
    $htmlTagAttributes['data-forced-theme'] = '';
    $htmlTagAttributes['data-bs-theme'] = $input->cookie->get('mutaPrefersColorScheme', $user->getParam('prefers_color_scheme', 'light')) . '"';
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

  $currentPage->logoBrandLarge  = $params->get('logoBrandLarge') ? Uri::root() . htmlspecialchars($params->get('logoBrandLarge'), ENT_QUOTES) : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-large.svg';
  $currentPage->logoBrandSmall = $params->get('logoBrandSmall') ? Uri::root() . htmlspecialchars($params->get('logoBrandSmall'), ENT_QUOTES) : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-small.svg';
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
  try {
    $json = json_decode($currentPage->colours, true);
  } catch (\Exception $e) {
    $json = [];
  }

  $colors = array_merge($currentPage->fallbackColours ?? [], !$json ? [] : $json);

  $this->setMetaData('theme-color', 'hsl('. $colors['hue']. ',40%,20%)');
  $wam->registerAndUseStyle('fontawesome', 'media/templates/administrator/muta/css/vendor/fontawesome-free/fontawesome.min.css', ['version' => '6.4.0'], [], []);
  $wam->useStyle('fontawesome')->version = '6.4.0';

  Text::script('JGLOBAL_WARNCOOKIES');

  // Enable assets
  $wam->usePreset('template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->useStyle('template.user')
    ->addInlineStyle(
      ':root {'
        . implode('', array_map(function($k, $v) { return '--'.$k.':'.$v.';'; }, array_keys($colors), array_values($colors)))
        . '--bs-font-sans-serif:' . $currentPage->defaultFont . ',"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";'
        . '--bs-font-monospace:' . $currentPage->monoFont . ',"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";'
      . '}',
      [],
      ['id' => 'tpl-muta-colors'])
    ->registerStyle('template.active', '', [], [], ['template.muta.' . ($currentPage->dir === 'rtl' ? 'rtl' : 'ltr')]);

  if ($entry === 'component') {
    $wam->disableScript('template.muta');
  }

  if (!in_array($entry, ['component', 'login']) && (!$currentPage->cpanel || !$currentPage->hiddenMenu)) {
    $wam->useScript('bootstrap.collapse');
  }

  $params->set('currentPage', $currentPage);
  }
}
