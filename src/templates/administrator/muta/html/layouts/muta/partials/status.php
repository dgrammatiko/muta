<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

Text::script('TPL_MUTA_MORE_ELEMENTS');

$doc                = Factory::getDocument();
$modulePosition     = $displayData['modules'];
$prefersColorScheme = $displayData['prefersColorScheme'];

$doc->getWebAssetManager()
  ->useScript('bootstrap.dropdown')
  ->registerAndUseScript('joomla-theme-switch', 'dg-toggler.js', [], ['type' => 'module'])
  ->registerAndUseStyle('joomla-theme-switch', 'dg-toggler.css');

// @todo make this a real module
$themeToggler        = !$doc->forcedColorScheme ? '' : '<joomla-theme-switch text-on="' . Text::_('JON'). '" text-off="' . Text::_('JOFF') . '" text-legend="' . Text::_('TPL_MUTA_COLORS_SETTINGS_DARK_THEME') . '"' . ($doc->forcedColorScheme ? ' forced-theme' : '') . '></joomla-theme-switch>';
$renderer            = $doc->loadRenderer('module');
$modules             = ModuleHelper::getModules($modulePosition);
$moduleHtml          = [$themeToggler];
$moduleCollapsedHtml = [$themeToggler];

foreach ($modules as $key => $mod) {
  $out = $renderer->render($mod);

  if ($out !== '') {
    if (strpos($out, 'data-bs-toggle="modal"') !== false) {
      $dom = new \DOMDocument();
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $out);
      $els = $dom->getElementsByTagName('a');

      $moduleCollapsedHtml[] = $dom->saveHTML($els[0]); //$els[0]->nodeValue;
    } else {
      $moduleCollapsedHtml[] = $out;
    }

    $moduleHtml[] = $out;
  }
}
?>
<div class="header-items d-flex ms-auto">
  <?php
    foreach ($moduleHtml as $mod) {
      echo '<div class="header-item">' . $mod . '</div>';
    }
  ?>
  <div class="header-more d-none" id="header-more-items" >
    <button class="header-more-btn dropdown-toggle" type="button" title="<?= Text::_('TPL_MUTA_MORE_ELEMENTS'); ?>" data-bs-toggle="dropdown" aria-expanded="false">
      <div class="header-item-icon"><span class="icon-ellipsis-h" aria-hidden="true"></span></div>
      <div class="visually-hidden"><?= Text::_('TPL_muta_MORE_ELEMENTS'); ?></div>
    </button>
    <div class="header-dd-items dropdown-menu">
      <?php
        foreach ($moduleCollapsedHtml as $key => $mod) {
          echo '<div class="header-dd-item dropdown-item" data-item="' . $key . '">' . $mod . '</div>';
        }
      ?>
    </div>
  </div>
</div>
