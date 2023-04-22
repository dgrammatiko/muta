<?php

/**
 * @copyright   (C) 2021 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */

namespace Dgrammatiko\Template\Muta\Administrator\Field;

defined('_JEXEC') || die;

use Joomla\CMS\Form\FormField;

/**
 * Color field.
 *
 * @since  1.0.0
 */
class DgcolorField extends FormField
{
  /**
   * The form field type.
   *
   * @var    string
   * @since  1.0.0
   */
  protected $type = 'Dgcolor';

  /**
   * Layout to render
   *
   * @var    string
   * @since  2.0.0
   */
  protected $layout = 'muta.field.dgcolor';

  /**
   * Method to get the field input markup.
   *
   * @return  string  The field input markup.
   *
   * @since   1.0.0
   */
  public function setup(\SimpleXMLElement $element, $value, $group = null)
  {
    // Bail out if this isn't a valid FormField XML element
    if ((string) $element->getName() !== 'field') {
      return false;
    }

    // Reset the input and label values
    $this->input   = null;
    $this->label   = null;
    $this->element = $element;
    $this->group   = $group;
    $this->default = isset($element['value']) ? (string) $element['value'] : $this->default;
    $this->value   = $value;
    $attributes    = [
      'multiple', 'name', 'id', 'hint', 'class', 'description', 'labelclass', 'onchange', 'onclick', 'validate', 'pattern', 'validationtext',
      'default', 'required', 'disabled', 'readonly', 'autofocus', 'hidden', 'autocomplete', 'spellcheck', 'translateHint', 'translateLabel',
      'translate_label', 'translateDescription', 'translate_description', 'size', 'showon'
    ];

    // Data attributes
    foreach ($this->element->attributes() as $key => $value) {
      if (strpos($key, 'data-') === 0) {
        $this->dataAttributes[$key] = $value;
      }
    }

    foreach ($attributes as $attributeName) {
      $this->__set($attributeName, $element[$attributeName]);
    }

    $repeat            = (string) $element['repeat'];
    $this->repeat      = ($repeat === 'true' || $repeat === 'multiple' || (!empty($this->form->repeat) && $this->form->repeat == 1));
    $this->hidden      = ($this->hidden || strtolower((string) $this->element['type']) === 'hidden');
    $this->parentclass = isset($this->element['parentclass']) ? (string) $this->element['parentclass'] : $this->parentclass;
    $this->class       = $this->required ? trim($this->class . ' required') : $this->class;
    $this->hiddenLabel = true;
    $this->hidden      = true;

    return true;
  }

  /**
   * Method to get the field input markup
   */
  protected function getInput(): string
  {
    if (empty($this->layout)) {
      throw new \UnexpectedValueException(sprintf('%s has no layout assigned.', $this->name));
    }

    return $this->getRenderer($this->layout)->render($this->getLayoutData());
  }

  /**
   * Get the data that is going to be passed to the layout
   */
  public function getLayoutData(): array
  {
    $defaultValue = (array) json_decode(file_get_contents(JPATH_ADMINISTRATOR . '/templates/muta/src/Field/def.json'), true);

    try {
      $json = json_decode($this->value, true);
    } catch (\Exception $e) {
      $json = [];
    }

    return array_merge(parent::getLayoutData(), ['val' => array_merge($defaultValue, !$json ? [] : $json)]);
  }
}
