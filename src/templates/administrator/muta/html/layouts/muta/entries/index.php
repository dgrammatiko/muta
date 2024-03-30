<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);
?>
<jdoc:include type="modules" name="customtop" style="none" />
<?= LayoutHelper::render('muta.partials.header', ['doc' => $doc, 'entry' => $entry, 'params' => $params]); ?>
<div id="wrapper" class="d-flex wrapper<?= $params->hiddenMenu ? '0' : ''; ?> <?= $params->sidebarState; ?>">
  <?php if (!$params->hiddenMenu) : ?>
    <button class="navbar-toggler toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper" aria-controls="sidebar-wrapper" aria-expanded="false" aria-label="<?= Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="sidebar-wrapper" class="sidebar-wrapper sidebar-menu" <?= $params->hiddenMenu ? 'data-hidden="' . $params->hiddenMenu . '"' : ''; ?>>
      <div id="sidebarmenu" class="sidebar-sticky">
        <div class="sidebar-toggle item item-level-1">
          <a id="menu-collapse" href="#" aria-label="<?= Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
            <span id="menu-collapse-icon" class="<?= $params->sidebarState === 'closed' ? 'icon-toggle-on' : 'icon-toggle-off'; ?> icon-fw" aria-hidden="true"></span>
            <span class="sidebar-item-title"><?= Text::_('JTOGGLE_SIDEBAR_MENU'); ?></span>
          </a>
        </div>
        <jdoc:include type="modules" name="menu" style="none" />
      </div>
    </div>
  <?php endif; ?>
  <div class="container-fluid container-main">
    <?php if (!$params->cpanel) : ?>
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
      <jdoc:include type="modules" name="top" style="html5" />
      <div class="row">
        <div class="col-md-12">
          <main>
            <jdoc:include type="message"  />
            <jdoc:include type="component"  />
          </main>
        </div>
        <jdoc:include type="modules" name="bottom" style="html5" />
      </div>
    </section>
  </div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
