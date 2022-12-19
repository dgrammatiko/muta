<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var \Joomla\CMS\Document\HtmlDocument $doc */

$app    = Factory::getApplication();
$input  = $app->getInput();
$doc    = $displayData['doc'];
$params = $displayData['params'];
$option = $input->get('option', '');
$view   = $input->get('view', '');
$layout = $input->get('layout', 'default');
$task   = $input->get('task', 'display');

Text::script('JGLOBAL_WARNCOOKIES');

// @see administrator/templates/muta/html/layouts/status.php
$statusModules = LayoutHelper::render('muta.partials.status', ['modules' => 'status', 'prefersColorScheme' => $doc->prefersColorScheme]);
?>
<!DOCTYPE html>
<html lang="<?= $doc->language; ?>" dir="<?= $doc->direction; ?>" data-bs-theme="<?= $doc->prefersColorScheme; ?>">

<head>
  <jdoc:include type="metas" />
  <jdoc:include type="styles" />
  <jdoc:include type="scripts" />
</head>

<body class="admin <?= $option . ' view-' . $view . ' layout-' . $layout . ($task ? ' task-' . $task : '') . ($doc->monochrome ? ' monochrome' : ''); ?>">
  <noscript>
    <div class="alert alert-danger" role="alert"><?= Text::_('JGLOBAL_WARNJAVASCRIPT'); ?></div>
  </noscript>
  <header id="header" class="header d-flex">
    <div class="header-title d-flex">
      <div class="d-flex align-items-center">
        <div class="logo">
          <img src="<?= $doc->logoBrandLarge; ?>" <?= $doc->logoBrandLargeAlt; ?>>
          <img class="logo-collapsed" src="<?= $doc->logoBrandSmall; ?>" <?= $doc->logoBrandSmallAlt; ?>>
        </div>
      </div>
      <jdoc:include type="modules" name="title" />
    </div>
    <?= $statusModules; ?>
  </header>
  <div id="wrapper" class="wrapper flex-grow-1">
    <div class="container-fluid container-main">
      <div class="login_message">
        <jdoc:include type="message" />
      </div>
      <main class="d-flex justify-content-center align-items-center h-100">
        <div class="login">
          <div id="main-brand" class="main-brand">
            <h1><?= $app->get('sitename'); ?></h1>
            <h2><?= Text::_('TPL_MUTA_BACKEND_LOGIN'); ?></h2>
          </div>
          <div class="main-brand logo text-center">
            <img src="<?= $doc->loginLogo; ?>" <?= $doc->loginLogoAlt; ?>>
          </div>
          <jdoc:include type="component" />
          <jdoc:include type="modules" name="sidebar" style="details" />
        </div>
      </main>
    </div>
  </div>
  <jdoc:include type="modules" name="debug" style="none" />
</body>

</html>
