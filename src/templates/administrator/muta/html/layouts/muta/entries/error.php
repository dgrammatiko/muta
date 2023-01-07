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
$entry  = $displayData['entry'];
$task   = $input->get('task', 'display');

Text::script('JGLOBAL_WARNCOOKIES');

// @see administrator/templates/muta/html/layouts/status.php
$statusModules = LayoutHelper::render('muta.partials.status', ['modules' => 'status', 'prefersColorScheme' => $doc->prefersColorScheme]);
?>
<!DOCTYPE html>
<html lang="<?php echo $doc->language; ?>" dir="<?php echo $doc->direction; ?>" data-bs-theme="<?= $doc->prefersColorScheme; ?>">
  <head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
  </head>
  <body class="admin <?php echo $input->get('option', '') . ' view-' . $input->get('view', '') . ' layout-' . $input->get('layout', 'default') . ($task ? ' task-' . $task : '') . ($doc->monochrome ? ' monochrome' : ''); ?>">
    <noscript>
      <div class="alert alert-danger" role="alert">
        <?php echo Text::_('JGLOBAL_WARNJAVASCRIPT'); ?>
      </div>
    </noscript>
    <header id="header" class="header d-flex">
      <div class="header-title d-flex">
        <div class="d-flex align-items-center">
          <div class="logo">
            <?= LayoutHelper::render('joomla.html.image', ['src' => $doc->logoBrandLarge, 'alt' => $doc->logoBrandLargeAlt, 'loading' => 'eager']); ?>
            <?= LayoutHelper::render('joomla.html.image', ['src' => $doc->logoBrandSmall, 'alt' => $doc->logoBrandSmallAlt, 'class' =>'logo-collapsed', 'loading' => 'eager']); ?>
          </div>
        </div>
        <jdoc:include type="modules" name="title" />
      </div>
      <?php echo $statusModules; ?>
    </header>
    <?php echo LayoutHelper::render('muta.entries.error.' . (Factory::getUser()->guest ? 'login' : 'full'), ['params' => $params, 'doc' => $doc]); ?>
  </body>
</html>
