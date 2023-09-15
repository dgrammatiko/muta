<?php

/**
 * @copyright   Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);
?>
<div id="wrapper" class="d-flex wrapper<?php echo $params->hiddenMenu ? '0' : ''; ?>">
  <div class="container-fluid container-main">
    <section id="content" class="content h-100">
      <main class="d-flex justify-content-center align-items-center h-100">
        <div id="element-box" class="card">
          <div class="card-body">
            <div class="main-brand d-flex align-items-center justify-content-center">
              <img src="<?php echo $params->loginLogo; ?>" <?php echo $params->loginLogoAlt; ?>>
            </div>
            <h1><?php echo Text::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?></h1>
            <?= $renderPosition->message ?? ''; ?>
            <blockquote class="blockquote">
              <span class="badge bg-secondary"><?php echo $doc->error->getCode(); ?></span>
              <?php echo htmlspecialchars($doc->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
            </blockquote>
            <?php echo LayoutHelper::render('muta.entries.error.debug', ['params' => $params, 'doc' => $doc]); ?>
          </div>
        </div>
      </main>
    </section>
  </div>
</div>
<?= $renderPosition->debug ?? ''; ?>
