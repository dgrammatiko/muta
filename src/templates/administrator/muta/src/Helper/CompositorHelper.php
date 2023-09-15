<?php

/**
 * @copyright   (C) 2023 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */

namespace Dgrammatiko\Template\Muta\Administrator\Helper;

defined('_JEXEC') || die;

use Dgrammatiko\Template\Muta\Administrator\Helper\ParamsEvaluatorHelper;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Layout\LayoutHelper;

/**
 * Compositor
 *
 * @since  2.0.0
 */
class CompositorHelper extends HtmlDocument
{
  private $entry;
  private $path;
  private $doc;

  public function __construct($options) {
    $this->entry = $options['entry'];
    $this->path = $options['path'];
    $this->doc = $options['doc'];
    $wam = $options['wam'];

    // Don't mess with regex
    $this->doc->setEvaluateJDocInclude(false);

    // Evaluate runtime params
    new ParamsEvaluatorHelper(['entry' => $this->entry, 'path' => $this->path, 'params' => $this->doc->params, 'wam' => $wam]);

    $currentParams = $this->doc->params->get('currentPage');

    // Evaluate the positions, ORDER IS IMPORTANT!!!
    $positions = [
      'component' => $this->component(),
      ...$this->modules(),

      // Status position is special: @see administrator/templates/muta/html/layouts/status.php
      'status' => LayoutHelper::render('muta.partials.status', ['doc' => $this->doc, 'entry' => $this->entry, 'params' => $currentParams, 'modules' => 'status', 'renderer' => $this->doc->loadRenderer('module')]),

      // Always LAST!!
      'message' => $this->message(),
    ];

   /*
    * Render the HTML
    * @see administrator/templates/muta/html/layouts/muta/entries/{entry}.php
    */
    $body = LayoutHelper::render('muta.entries.' .  $this->entry, ['doc' => $this->doc, 'entry' => $this->entry, 'params' => $currentParams, 'renderPosition' => (object) $positions]);
    $head = $this->head();

    echo LayoutHelper::render(
      'muta.html5_sceleton',
      [
        'doc'    => $this->doc,
        'entry'  => $this->entry,
        'params' => $currentParams,
        'body'   => $body,
        'head'   => $head,
      ]);
  }

  public function head() {
    return $this->doc->getBuffer('metas', 'metas') . $this->doc->getBuffer('styles', 'styles') . $this->doc->getBuffer('scripts', 'scripts');
  }

  public function message() {
      return $this->doc->getBuffer('message', '');
  }

  public function component() {
      return $this->doc->getBuffer('component', '');
  }

  public function modules() {
    $xmlFilePath   = $this->path . '/templateDetails.xml';
    $params        = $this->doc->params->get('currentPage');
    $skipPositions = [];
    $fragments     = [];

    if (!is_file($xmlFilePath)) {
      throw new \Exception('The template is missing the definition XML file.');
    }

    if (!($templateXML = simplexml_load_file($xmlFilePath))) {
      throw new \Exception('The template definition XML file is corrupted.');
    }

    if (!$templateXML->positions || !$templateXML->positions->position) {
      throw new \Exception('The template definition XML file is missing the positions tag.');
    }

    if ($params->hiddenMenu) $skipPositions[] = 'menu';
    if ($params->cpanel) $skipPositions[] = 'toolbar';
    if (!$params->cpanel || ($params->cpanel && $params->view !== '')) $skipPositions = ['cpanel', 'icon'];
    if ($this->entry === 'component') $skipPositions = ['menu', 'cpanel', 'icon'];

    foreach((array) $templateXML->positions->position as $position) {
      if ($position === 'status' || in_array($position, $skipPositions) || !$this->doc->countModules($position)) continue;
      if (in_array('cpanel', $skipPositions) && str_starts_with($position, 'cpanel')) continue;

      $style = (in_array($this->entry, ['login']) && $position === 'sidebar') ? 'details' : 'none';

      $fragments[$position] = $this->doc->getBuffer('modules', $position, ['style' => $style]);
    }

    return $fragments;
  }
}
