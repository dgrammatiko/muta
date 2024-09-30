<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

extract($displayData);

Text::script('TPL_MUTA_MORE_ELEMENTS');

$doc->getWebAssetManager()
  ->registerAndUseScript('joomla-theme-switch', 'dg-toggler.js', [], ['type' => 'module']);

$renderer         = $doc->loadRenderer('module');
$themeTogglerHtml = !$params->forcedColorScheme ? '' : '<joomla-theme-switch class="ms-3 me-3 m-2" text-on="' . Text::_('JON') . '" text-off="' . Text::_('JOFF') . '" text-legend="' . Text::_('TPL_MUTA_COLORS_SETTINGS_DARK_THEME') . '"' . ($params->forcedColorScheme ? ' forced-theme' : '') . '></joomla-theme-switch>';
$moduleHtml       = [];
$allModules       = ModuleHelper::getModules('status');

foreach ($allModules as $key => $mod) {
  $out = $renderer->render($mod);
  if ($out)
    $moduleHtml[] = '<div class="header-item m-0">' . $out . '</div>';
}
?>
<div id="wrapper" class="wrapper login-bg flex-grow-1">
  <div class="container-fluid container-main">
    <div class="login_message">
      <jdoc:include type="message" />
    </div>
    <main class="d-flex justify-content-center align-items-center h-100">
      <div class="card p-5 login">
        <header id="header" class="header-login bg-body">
          <div class="header-inside">
            <div class="header-title ms-auto me-auto">
              <div class="header-items d-flex justify-content-between ms-auto me-auto gap-5 w-100">
                <?= implode(' ', $moduleHtml); ?>
              </div>
              <?= $themeTogglerHtml; ?>
              <div class="header-more d-none" id="header-more-items"></div>
            </div>
        </header>
        <div id="main-brand" class="main-brand">
          <h1 class="text-center"><?= $params->sitename; ?></h1>
          <h2 class="text-center"><?= Text::_('TPL_MUTA_BACKEND_LOGIN'); ?></h2>
        </div>
        <div class="main-brand logo text-center">
          <?= LayoutHelper::render('joomla.html.image', ['src' => $params->loginLogo, 'alt' => $params->loginLogoAlt, 'loading' => 'eager']); ?>
        </div>
        <jdoc:include type="component" />
        <jdoc:include type="modules" name="sidebar" style="details" />
      </div>
    </main>
  </div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
