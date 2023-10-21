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
<?= LayoutHelper::render('muta.partials.header', ['doc' => $doc, 'entry' => $entry, 'params' => $params, 'renderPosition' => $renderPosition]); ?>
<div id="wrapper" class="wrapper login-bg flex-grow-1">
  <div class="container-fluid container-main">
    <div class="login_message">
      <?= $renderPosition->message ?? ''; ?>
    </div>
    <main class="d-flex justify-content-center align-items-center h-100">
      <div class="card text-bg-light p-3 login">
        <div id="main-brand" class="main-brand">
          <h1 class="text-center"><?= $params->sitename; ?></h1>
          <h2 class="text-center"><?= Text::_('TPL_MUTA_BACKEND_LOGIN'); ?></h2>
        </div>
        <div class="main-brand logo text-center">
          <?= LayoutHelper::render('joomla.html.image', ['src' => $params->loginLogo, 'alt' => $params->loginLogoAlt, 'loading' => 'eager']); ?>
        </div>
        <?= $renderPosition->component ?? ''; ?>
        <?= $renderPosition->sidebar ?? ''; ?>
      </div>
    </main>
  </div>
</div>
<?= $renderPosition->debug ?? ''; ?>
