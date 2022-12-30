<?php

/**
 * @copyright   (C) 2021 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 *
 * THIS IS NOT THE WAY TO CODE FIELDS!!! DO NOT TAKE THIS AS A TEMPLATE...
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

/**
 * Color field.
 *
 * @since  1.0.0
 */
class JFormFieldDgcolor extends FormField
{
  /**
   * The form field type.
   *
   * @var    string
   * @since  1.0.0
   */
  protected $type = 'dgcolor';

  /**
   * Method to get the field input markup.
   *
   * @return  string  The field input markup.
   *
   * @since   1.0.0
   */
  public function setup(\SimpleXMLElement $element, $value, $group = null)
  {
    // Make sure there is a valid FormField XML element.
    if ((string) $element->getName() !== 'field') {
      return false;
    }

    // Reset the input and label values.
    $this->input = null;
    $this->label = null;

    // Set the XML element object.
    $this->element = $element;

    // Set the group of the field.
    $this->group = $group;

    $attributes = array(
      'multiple', 'name', 'id', 'hint', 'class', 'description', 'labelclass', 'onchange', 'onclick', 'validate', 'pattern', 'validationtext',
      'default', 'required', 'disabled', 'readonly', 'autofocus', 'hidden', 'autocomplete', 'spellcheck', 'translateHint', 'translateLabel',
      'translate_label', 'translateDescription', 'translate_description', 'size', 'showon'
    );

    $this->default = isset($element['value']) ? (string) $element['value'] : $this->default;

    // Set the field default value.
    $this->value = $value;

    // Lets detect miscellaneous data attribute. For eg, data-*
    foreach ($this->element->attributes() as $key => $value) {
      if (strpos($key, 'data-') === 0) {
        // Data attribute key value pair
        $this->dataAttributes[$key] = $value;
      }
    }

    foreach ($attributes as $attributeName) {
      $this->__set($attributeName, $element[$attributeName]);
    }

    // Allow for repeatable elements
    // $repeat = (string) $element['repeat'];
    // $this->repeat = ($repeat === 'true' || $repeat === 'multiple' || (!empty($this->form->repeat) && $this->form->repeat == 1));

    // Set the visibility.
    $this->hidden = ($this->hidden || strtolower((string) $this->element['type']) === 'hidden');

    // $this->layout = !empty($this->element['layout']) ? (string) $this->element['layout'] : $this->layout;

    $this->parentclass = isset($this->element['parentclass']) ? (string) $this->element['parentclass'] : $this->parentclass;

    // Add required to class list if field is required.
    if ($this->required) {
      $this->class = trim($this->class . ' required');
    }

    // Hide the label
    $this->hiddenLabel = true;
    $this->hidden = true;

    return true;
  }

  /**
   * Method to get the field input markup
   *
   * @return  string  The field input markup.
   */
  public function getInput()
  {
    $defaultValue = (array) json_decode(file_get_contents(JPATH_ADMINISTRATOR . '/templates/muta/fields/def.json'), true);

    try {
      $json = json_decode($this->value, true);
    } catch (\Exception $e) {
      $json = [];
    }
    if (!$json) $json = [];
    $val = array_merge($defaultValue, $json);

    Text::script('TPL_MUTA_COLORS_SETTINGS_LINK_COLOR_LABEL');
    Text::script('TPL_MUTA_COLORS_SETTINGS_LINK_HOVER_COLOR_LABEL');
    Text::script('TPL_MUTA_COLORS_HUE');

    Factory::getDocument()->getWebAssetManager()
    ->registerAndUseScript(
      'form.field.dgcolor',
      'media/templates/administrator/muta/js/dgcolor-field.js',
      ['version' => 'auto'],
      ['type' => 'module'],
      ['core']
    )->registerAndUseStyle(
      'form.field.dgcolor',
      'media/templates/administrator/muta/js/dgcolor-field.css',
      [],
      []
    );

    return '<dg-color-field name="' . $this->name . '" value=\'' . json_encode($val) . '\'><input type="hidden" name="' . $this->name . '" value=\'' . json_encode($val) . '\'></dg-color-field>';
  }
}
