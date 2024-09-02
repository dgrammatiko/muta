<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

extract($displayData);

?>
<header id="header" class="header">
  <div class="header-inside">
    <div class="header-title d-flex">
      <div class="d-flex align-items-center">
        <?php if (in_array($entry, ['login', 'error']) || !$params->hiddenMenu || !$params->cpanel) : ?><?php /* No home link in edit mode and control panel (so users can not jump out for a11y reasons) */ ?>
        <div class="logo <?= $params->sidebarState === 'closed' || $params->hiddenMenu ? 'small' : ''; ?>">
          <?= LayoutHelper::render('joomla.html.image', ['src' => $params->logoBrandLarge, 'alt' => '', 'loading' => 'eager']); ?>
          <?= LayoutHelper::render('joomla.html.image', ['src' => $params->logoBrandSmall, 'alt' => '', 'class' => 'logo-collapsed', 'loading' => 'eager']); ?>
        </div>
      <?php else : ?>
        <a class="logo <?= $params->sidebarState === 'closed' ? 'small' : ''; ?>" href="<?= Route::_('index.php'); ?>">
          <?= LayoutHelper::render('joomla.html.image', ['src' => $params->logoBrandLarge, 'alt' => Text::_('TPL_MUTA_BACK_TO_CONTROL_PANEL'), 'loading' => 'eager']); ?>
          <?= LayoutHelper::render('joomla.html.image', ['src' => $params->logoBrandSmall, 'alt' => Text::_('TPL_MUTA_BACK_TO_CONTROL_PANEL'), 'class' => 'logo-collapsed', 'loading' => 'eager']); ?>
        </a>
      <?php endif; ?>
      </div>
      <jdoc:include type="modules" name="title" />
    </div>
    <?= LayoutHelper::render('muta.partials.status', ['doc' => $doc, 'params' => $params, 'modules' => 'status']); ?>
  </div>
</header>
