<?php

/**
 * @copyright  Copyright (C) 2023 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

extract($displayData);

$params = $doc->params->get('currentPage');

echo
'<!DOCTYPE html>' .
'<html ' . ArrayHelper::toString($params->htmlTagAttributes) . '>' .
  '<head><jdoc:include type="head" /></head>' .
  '<body ' . ArrayHelper::toString($params->bodyTagAttributes) . '>' .
    '<noscript><div class="alert alert-danger" role="alert">' . Text::_('JGLOBAL_WARNJAVASCRIPT') . '</div></noscript>' .
    $this->sublayout($entry, ['doc' => $doc, 'entry' => $entry, 'params' => $params]).
  '</body>' .
'</html>';
