<?php

/**
 * @copyright   Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var \Joomla\CMS\Document\HtmlDocument $doc */

$doc    = $displayData['doc'];
$params = $displayData['params'];

if (!$doc->debug) return;

echo 'div';
echo $doc->renderBacktrace();
if ($doc->error->getPrevious()) {
  $loop = true;
  // Reference $doc->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly
  // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions
  $doc->setError($doc->_error->getPrevious());
  while ($loop === true) {
    echo '<p><strong>' . Text::_('JERROR_LAYOUT_PREVIOUS_ERROR') . '</strong></p>'
      . '<p>' . htmlspecialchars($doc->_error->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'
      . $doc->renderBacktrace();
    $loop = $doc->setError($doc->_error->getPrevious());
  }
  // Reset the main error object to the base error
  $doc->setError($doc->error);
}
echo '</div>';
