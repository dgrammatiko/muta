
<?php
/**
 * @copyright   (C) 2023 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') || die();

use Joomla\CMS\Installer\Adapter\TemplateAdapter;
use Joomla\CMS\Installer\InstallerScript;

class MutaInstallerScript extends InstallerScript
{
  /**
   * Minimum Joomla version
   *
   * @var string
   */
  protected $minimumJoomla = '4.3';

  /**
   * Minimum PHP version
   *
   * @var string
   */
  protected $minimumPhp = JOOMLA_MINIMUM_PHP;

  /**
   * Files for removal
   *
   * @var array
   */
  protected $deleteFiles = [
    '/administrator/templates/muta/favicon.png',
  ];

  /**
   * Folders for removal
   *
   * @var array
   */
  protected $deleteFolders = [
    '/administrator/templates/muta/fields',
  ];

  public function postflight($type, TemplateAdapter $parent)
  {
    if (($type === 'install' || $type === 'discover_install' || $type === 'upgrade')) {
      if (is_file(JPATH_ROOT . '/media/templates/administrator/muta/images/favicon.png')) {
        @copy(JPATH_ROOT . '/media/templates/administrator/muta/images/favicon.png', JPATH_ROOT . '/media/system/images/favicon.png');
      }
      if (!is_dir(JPATH_ROOT . '/layouts/muta/field')) {
        @mkdir(JPATH_ROOT . '/layouts/muta/field', 0755, true);
      }
      @copy($parent->getParent()->getPath('source') . '/html/layouts/muta/field/dgcolor.php', JPATH_ROOT . '/layouts/muta/field/dgcolor.php');
      @copy($parent->getParent()->getPath('source') . '/html/layouts/muta/field/dgfont.php', JPATH_ROOT . '/layouts/muta/field/dgfont.php');
    }

    if ($type === 'upgrade') {
      $this->removeFiles();
    }

    if ($type === 'uninstall') {
      $this->deleteFolders = ['/layouts/muta'];
      $this->deleteFiles   = ['/media/system/images/favicon.png'];

      $this->removeFiles();
    }
  }
}
