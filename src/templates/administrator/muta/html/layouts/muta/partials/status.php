<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

extract($displayData);

Text::script('TPL_MUTA_MORE_ELEMENTS');

$doc->getWebAssetManager()
  ->useScript('bootstrap.dropdown')
  ->registerAndUseScript('joomla-theme-switch', 'dg-toggler.js', [], ['type' => 'module']);

$renderer            = Factory::getDocument()->loadRenderer('module');
$themeTogglerHtml    = !$params->forcedColorScheme ? '' : '<joomla-theme-switch text-on="' . Text::_('JON'). '" text-off="' . Text::_('JOFF') . '" text-legend="' . Text::_('TPL_MUTA_COLORS_SETTINGS_DARK_THEME') . '"' . ($params->forcedColorScheme ? ' forced-theme' : '') . '></joomla-theme-switch>';
$moduleHtml          = [$themeTogglerHtml];
$moduleCollapsedHtml = [$themeTogglerHtml];
$allModules          = ModuleHelper::getModules($modules);

foreach ($allModules as $key => $mod) {
  $out = $renderer->render($mod);

  if ($out !== '') {
    if (strpos($out, 'data-bs-toggle="modal"') !== false) {
      $dom = new \DOMDocument();
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $out);
      $els = $dom->getElementsByTagName('a');

      $moduleCollapsedHtml[] = '<div class="header-dd-item dropdown-item" data-item="' . $key . '">' . $dom->saveHTML($els[0]) . '</div>';
    } else {
      $moduleCollapsedHtml[] = '<div class="header-dd-item dropdown-item" data-item="' . $key . '">' . $out . '</div>';
    }

    $moduleHtml[] = '<div class="header-item">' . $out . '</div>';
  }
}
?>
<div class="header-items d-flex ms-auto">
  <?= implode(' ', $moduleHtml); ?>
  <div class="header-more d-none" id="header-more-items" >
    <button class="header-more-btn dropdown-toggle" type="button" title="<?= Text::_('TPL_MUTA_MORE_ELEMENTS'); ?>" data-bs-toggle="dropdown" aria-expanded="false">
      <div class="header-item-icon"><span class="icon-ellipsis-h" aria-hidden="true"></span></div>
      <div class="visually-hidden"><?= Text::_('TPL_muta_MORE_ELEMENTS'); ?></div>
    </button>
    <div class="header-dd-items dropdown-menu">
      <?= implode(' ', $moduleCollapsedHtml); ?>
    </div>
  </div>
</div>
