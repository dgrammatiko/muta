<?php

/**
 * @copyright   Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);

$params = $doc->params->get('currentPage');
?>
<div id="wrapper" class="d-flex wrapper<?= $params->hiddenMenu ? '0' : ''; ?>">
  <div class="container-fluid container-main">
    <section id="content" class="content h-100">
      <main class="d-flex justify-content-center align-items-center h-100">
        <div id="element-box" class="card">
          <div class="card-body">
            <div class="main-brand d-flex align-items-center justify-content-center">
              <img src="<?= $params->loginLogo; ?>" <?= $params->loginLogoAlt; ?>>
            </div>
            <h1><?= Text::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?></h1>
            <jdoc:include type="message"  />
            <blockquote class="blockquote">
              <span class="badge bg-secondary"><?= $doc->error->getCode(); ?></span>
              <?= htmlspecialchars($doc->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
            </blockquote>
            <?= LayoutHelper::render('muta.entries.error.debug', ['params' => $params, 'doc' => $doc]); ?>
          </div>
        </div>
      </main>
    </section>
  </div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
