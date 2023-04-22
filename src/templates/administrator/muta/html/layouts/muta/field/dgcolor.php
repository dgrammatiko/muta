<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * @copyright   (C) 2023 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') || die;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 * @var   string   $dataAttribute   Miscellaneous data attributes preprocessed for HTML output
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*.
 */

Text::script('TPL_MUTA_COLORS_SETTINGS_LINK_COLOR_LABEL');
Text::script('TPL_MUTA_COLORS_SETTINGS_LINK_HOVER_COLOR_LABEL');
Text::script('TPL_MUTA_COLORS_HUE');
Text::script('JRESET');

Factory::getDocument()->getWebAssetManager()
  ->registerAndUseScript('form.field.dgcolor', 'media/templates/administrator/muta/js/dgcolor-field.js', ['version' => 'auto'], ['type'    => 'module'], ['core'])
  ->registerAndUseStyle('form.field.dgcolor', 'media/templates/administrator/muta/js/dgcolor-field.css', [], []);

?>
<dg-color-field name="<?= $name; ?>" value='<?= json_encode($val); ?>'>
  <input type="hidden" name="<?= $name; ?>" value='<?= json_encode($val); ?>'>
</dg-color-field>
