<?php

/**
 * @copyright   Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var \Joomla\CMS\Document\HtmlDocument $doc */

$doc    = $displayData['doc'];
$params = $displayData['params'];
?>
<div id="wrapper" class="d-flex wrapper<?php echo $doc->hiddenMenu ? '0' : ''; ?>">
  <?php if (!$doc->hiddenMenu) : ?>
    <button class="navbar-toggler toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper" aria-controls="sidebar-wrapper" aria-expanded="false" aria-label="<?php echo Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="sidebar-wrapper" class="sidebar-wrapper sidebar-menu" <?php echo $doc->hiddenMenu ? 'data-hidden="' . $doc->hiddenMenu . '"' : ''; ?>>
      <div id="sidebarmenu">
        <div class="sidebar-toggle item item-level-1">
          <a id="menu-collapse" href="#" aria-label="<?php echo Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
            <span id="menu-collapse-icon" class="icon-toggle-off icon-fw" aria-hidden="true"></span>
            <span class="sidebar-item-title"><?php echo Text::_('JTOGGLE_SIDEBAR_MENU'); ?></span>
          </a>
        </div>
        <jdoc:include type="modules" name="menu" style="none" />
      </div>
    </div>
  <?php endif; ?>
  <div class="container-fluid container-main">
    <?php if (!$doc->cpanel) : ?>
      <a class="btn btn-subhead d-md-none d-lg-none d-xl-none" data-bs-toggle="collapse" data-bs-target=".subhead-collapse"><?php echo Text::_('TPL_ATUM_TOOLBAR'); ?>
        <span class="icon-wrench"></span></a>
      <div id="subhead" class="subhead mb-3">
        <div id="container-collapse" class="container-collapse"></div>
        <div class="row">
          <div class="col-md-12">
            <jdoc:include type="modules" name="toolbar" style="none" />
          </div>
        </div>
      </div>
    <?php endif; ?>
    <section id="content" class="content">
      <jdoc:include type="message" />
      <jdoc:include type="modules" name="top" style="html5" />
      <div class="row">
        <div class="col-md-12">
          <h1><?php echo Text::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?></h1>
          <blockquote class="blockquote">
            <span class="badge bg-secondary"><?php echo $doc->error->getCode(); ?></span>
            <?php echo htmlspecialchars($doc->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
          </blockquote>
          <?php echo LayoutHelper::render('muta.entries.error.debug', ['params' => $params, 'doc' => $doc]); ?>
          <p>
            <a href="<?php echo $doc->baseurl; ?>" class="btn btn-secondary">
              <span class="icon-dashboard" aria-hidden="true"></span>
              <?php echo Text::_('JGLOBAL_TPL_CPANEL_LINK_TEXT'); ?></a>
          </p>
        </div>

        <?php if ($doc->countModules('bottom')) : ?>
          <jdoc:include type="modules" name="bottom" style="html5" />
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
