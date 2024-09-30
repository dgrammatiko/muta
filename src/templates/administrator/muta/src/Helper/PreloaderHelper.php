<?php

/**
 * @copyright   (C) 2024 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */

namespace Dgrammatiko\Template\Muta\Administrator\Helper;

use Exception;

defined('_JEXEC') || die;

class PreloaderHelper
{
  public function getFontVersions() {
    if (is_file(__DIR__ . '/versions.php'))
      return require_once __DIR__ . '/versions.php';

    throw new Exception('Font version file doesn\'t exist');
  }

  public function preload($type, $assets, $pem, $wam, $app) {
    foreach ($assets as $asset) {
      if ($type === 'font') {
        $pem->preload($asset, ['as' => 'font', 'type' => 'font/woff2', 'crossorigin' => true]);
        continue;
      }
      $assetObj = $wam->getAsset($type, $asset);
      $url   = $assetObj->getUri();

      if (str_contains($url, '?')) {
        $pem->preload($url, ['as' => $type]);
        continue;
      }

      $ver = $assetObj->getVersion() !== 'auto' ? $assetObj->getVersion() : $app->getDocument()->getMediaVersion();
      $pem->preload($url . '?' . $ver, ['as' => $type]);
    }
  }
}
