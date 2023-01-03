<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

$app    = Factory::getApplication();
$input  = $app->getInput();
$doc    = $displayData['doc'];
$params = $displayData['params'];
$entry  = $displayData['entry'];
$option = $input->get('option', '');
$view   = $input->get('view', '');
$layout = $input->get('layout', 'default');
$task   = $input->get('task', 'display');

Text::script('JGLOBAL_WARNCOOKIES');

// @see administrator/templates/muta/html/layouts/status.php
$statusModules = LayoutHelper::render('muta.partials.status', ['modules' => 'status', 'prefersColorScheme' => $doc->prefersColorScheme]);
?>
<!DOCTYPE html>
<html lang="<?= $doc->language; ?>" dir="<?= $doc->direction; ?>" <?= $doc->a11y_font ? ' class="a11y_font"' : ''; ?> data-bs-theme="<?= $doc->prefersColorScheme; ?>">
  <head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
  </head>
  <body class="admin <?= $option . ' view-' . $view . ' layout-' . $layout . ($task ? ' task-' . $task : '') . ($doc->monochrome || $doc->a11y_mono ? ' monochrome' : '') . ($doc->a11y_contrast ? ' a11y_contrast' : '') . ($doc->a11y_highlight ? ' a11y_highlight' : ''); ?>">
    <noscript>
      <div class="alert alert-danger" role="alert">
        <?= Text::_('JGLOBAL_WARNJAVASCRIPT'); ?>
      </div>
    </noscript>
    <jdoc:include type="modules" name="customtop" style="none" />
    <?php /* Header */ ?>
    <header id="header" class="header">
      <div class="header-inside">
        <div class="header-title d-flex">
          <div class="d-flex align-items-center">
            <?php /* No home link in edit mode (so users can not jump out) and control panel (for a11y reasons) */ ?>
            <?php if ($doc->hiddenMenu || $doc->cpanel) : ?>
              <div class="logo<?= $doc->sidebarState === 'closed' || $doc->hiddenMenu ? ' small' : ''; ?>">
                <?= LayoutHelper::render('joomla.html.image', ['src' => $doc->logoBrandLarge, 'alt' => $doc->logoBrandLargeAlt, 'loading' => 'eager']); ?>
                <?= LayoutHelper::render('joomla.html.image', ['src' => $doc->logoBrandSmall, 'alt' => $doc->logoBrandSmallAlt, 'class' =>'logo-collapsed', 'loading' => 'eager']); ?>
              </div>
            <?php else : ?>
              <a class="logo <?= $doc->sidebarState === 'closed' ? 'small' : ''; ?>" href="<?= Route::_('index.php'); ?>">
                <?= LayoutHelper::render('joomla.html.image', ['src' => $doc->logoBrandLarge, 'alt' => Text::_('TPL_MUTA_BACK_TO_CONTROL_PANEL'), 'loading' => 'eager']); ?>
                <?= LayoutHelper::render('joomla.html.image', ['src' => $doc->logoBrandSmall, 'alt' => Text::_('TPL_MUTA_BACK_TO_CONTROL_PANEL'), 'class' =>'logo-collapsed', 'loading' => 'eager']); ?>
              </a>
            <?php endif; ?>
          </div>
          <jdoc:include type="modules" name="title" />
        </div>
        <?= $statusModules; ?>
      </div>
    </header>
    <?php /* Wrapper */ ?>
    <div id="wrapper" class="d-flex wrapper<?= $doc->hiddenMenu ? '0' : ''; ?> <?= $doc->sidebarState; ?>">
      <?php /* Sidebar */ ?>
      <?php if (!$doc->hiddenMenu) : ?>
        <?php HTMLHelper::_('bootstrap.collapse', '.toggler-burger'); ?>
        <button class="navbar-toggler toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper" aria-controls="sidebar-wrapper" aria-expanded="false" aria-label="<?= Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div id="sidebar-wrapper" class="sidebar-wrapper sidebar-menu" <?= $doc->hiddenMenu ? 'data-hidden="' . $doc->hiddenMenu . '"' : ''; ?>>
          <div id="sidebarmenu" class="sidebar-sticky">
            <div class="sidebar-toggle item item-level-1">
              <a id="menu-collapse" href="#" aria-label="<?= Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
                <span id="menu-collapse-icon" class="<?= $doc->sidebarState === 'closed' ? 'icon-toggle-on' : 'icon-toggle-off'; ?> icon-fw" aria-hidden="true"></span>
                <span class="sidebar-item-title"><?= Text::_('JTOGGLE_SIDEBAR_MENU'); ?></span>
              </a>
            </div>
            <jdoc:include type="modules" name="menu" style="none" />
          </div>
        </div>
      <?php endif; ?>
      <?php /* container-fluid */ ?>
      <div class="container-fluid container-main">
        <?php if (!$doc->cpanel) : ?>
          <?php /* Subheader */ ?>
          <?php HTMLHelper::_('bootstrap.collapse', '.toggler-toolbar'); ?>
          <button class="navbar-toggler toggler-toolbar toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subhead-container" aria-controls="subhead-container" aria-expanded="false" aria-label="<?= Text::_('TPL_MUTA_TOOLBAR'); ?>">
            <span class="toggler-toolbar-icon"></span>
          </button>
          <div id="subhead-container" class="subhead mb-3">
            <div class="row">
              <div class="col-md-12">
                <jdoc:include type="modules" name="toolbar" style="none" />
              </div>
            </div>
          </div>
        <?php endif; ?>
        <section id="content" class="content">
          <?php /* Begin Content */ ?>
          <jdoc:include type="modules" name="top" style="html5" />
          <div class="row">
            <div class="col-md-12">
              <main>
                <jdoc:include type="message" />
                <jdoc:include type="component" />
              </main>
            </div>
            <?php if ($doc->countModules('bottom')) : ?>
              <jdoc:include type="modules" name="bottom" style="html5" />
            <?php endif; ?>
          </div>
          <?php /* End Content */ ?>
        </section>
      </div>
    </div>
    <jdoc:include type="modules" name="debug" style="none" />
  </body>
</html>
