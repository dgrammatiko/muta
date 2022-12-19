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
?>
<!DOCTYPE html>
<html lang="<?php echo $doc->language; ?>" dir="<?php echo $doc->direction; ?>" data-bs-theme="<?= $doc->prefersColorScheme; ?>">
  <head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
  </head>
  <body class="contentpane component">
    <jdoc:include type="message" />
    <jdoc:include type="component" />
  </body>
</html>
