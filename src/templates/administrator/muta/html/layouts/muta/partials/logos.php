<?php

/**
 * @copyright  Copyright (C) 2022 Dimitrios Grammatikogiannis. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

use Joomla\CMS\Uri\Uri;

$doc    = $displayData['doc'];
$params = $displayData['params'];
$entry  = $displayData['entry'];

$doc->logoBrandLarge  = $params->get('logoBrandLarge') ? Uri::root() . htmlspecialchars($params->get('logoBrandLarge'), ENT_QUOTES) : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-large.svg';
$doc->loginLogo = $params->get('loginLogo') ? Uri::root() . $params->get('loginLogo') : Uri::root() . 'media/templates/administrator/muta/images/logos/login.svg';
$doc->logoBrandSmall = $params->get('logoBrandSmall') ? Uri::root() . htmlspecialchars($params->get('logoBrandSmall'), ENT_QUOTES) : Uri::root() . 'media/templates/administrator/muta/images/logos/brand-small.svg';
$doc->logoBrandLargeAlt = empty($params->get('logoBrandLargeAlt')) ? '' : htmlspecialchars($params->get('logoBrandLargeAlt', ''), ENT_COMPAT, 'UTF-8');
$doc->logoBrandLargeAlt = empty($params->get('emptyLogoBrandLargeAlt')) && $doc->logoBrandLargeAlt === '' ? false : $doc->logoBrandLargeAlt;
$doc->logoBrandSmallAlt = empty($params->get('logoBrandSmallAlt')) ? '' : htmlspecialchars($params->get('logoBrandSmallAlt', ''), ENT_COMPAT, 'UTF-8');
$doc->logoBrandSmallAlt = empty($params->get('emptyLogoBrandSmallAlt')) && $doc->logoBrandSmallAlt === '' ? false : $doc->logoBrandSmallAlt;
$doc->loginLogoAlt = empty($params->get('loginLogoAlt')) ? '' : htmlspecialchars($params->get('loginLogoAlt', ''), ENT_COMPAT, 'UTF-8');
$doc->loginLogoAlt = empty($params->get('emptyLoginLogoAlt')) && $doc->loginLogoAlt === '' ? false : $doc->loginLogoAlt;
