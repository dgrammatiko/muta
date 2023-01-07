
<?php
/**
 * @copyright   (C) 2023 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') || die('Restricted access');

use Joomla\CMS\Installer\Adapter\TemplateAdapter;
use Joomla\CMS\Installer\InstallerScript;

class MutaInstallerScript extends InstallerScript
{
  protected $deleteFiles = [];
  protected $deleteFolders = [];

  public function __construct()
  {
    $this->minimumJoomla = '4.2';
    $this->minimumPhp = JOOMLA_MINIMUM_PHP;
  }

  public function postflight($type, TemplateAdapter $parent)
  {
    if (($type === 'install' || $type === 'discover_install') && is_file($parent->getParent()->getPath('source') . '/favicon.png')) {
      @copy($parent->getParent()->getPath('source') . '/favicon.png', JPATH_ROOT . '/media/system/images/favicon.png');
    }
  }
}
