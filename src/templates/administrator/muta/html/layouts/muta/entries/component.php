<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

/** @var \Joomla\CMS\Document\HtmlDocument $doc */

$doc    = $displayData['doc'];
$params = $displayData['params'];
$entry  = $displayData['entry'];
?>
<!DOCTYPE html>
<html lang="<?php echo $doc->language; ?>" dir="<?php echo $doc->direction; ?>"<?= $doc->prefersColorScheme; ?><?= $doc->forcedColorScheme ? ' data-forced-theme' : ''; ?>>

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
