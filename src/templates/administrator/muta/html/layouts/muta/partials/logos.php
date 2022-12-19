<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Uri\Uri;

$doc    = $displayData['doc'];
$params = $displayData['params'];

$doc->logoBrandLarge  = $params->get('logoBrandLarge')
  ? Uri::root() . htmlspecialchars($params->get('logoBrandLarge'), ENT_QUOTES)
  : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-large.svg';
$doc->loginLogo = $params->get('loginLogo')
  ? Uri::root() . $params->get('loginLogo')
  : Uri::root() . 'media/templates/administrator/muta/images/logos/login.svg';
$doc->logoBrandSmall = $params->get('logoBrandSmall')
  ? Uri::root() . htmlspecialchars($params->get('logoBrandSmall'), ENT_QUOTES)
  : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-small.svg';

$doc->logoBrandLargeAlt = empty($params->get('logoBrandLargeAlt')) && empty($params->get('emptyLogoBrandLargeAlt'))
  ? 'alt=""' : 'alt="' . htmlspecialchars($params->get('logoBrandLargeAlt', ''), ENT_COMPAT, 'UTF-8') . '"';
$doc->logoBrandSmallAlt = empty($params->get('logoBrandSmallAlt')) && empty($params->get('emptyLogoBrandSmallAlt'))
  ? 'alt=""' : 'alt="' . htmlspecialchars($params->get('logoBrandSmallAlt', ''), ENT_COMPAT, 'UTF-8') . '"';
$doc->loginLogoAlt = empty($params->get('loginLogoAlt')) && empty($params->get('emptyLoginLogoAlt'))
  ? 'alt=""' : 'alt="' . htmlspecialchars($params->get('loginLogoAlt', ''), ENT_COMPAT, 'UTF-8') . '"';
