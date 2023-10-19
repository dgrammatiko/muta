// eslint-disable-next-line import/no-unresolved
import { JoomlaEditorDecorator } from 'editor-api';

/**
 * TinyMCE Decorator for JoomlaEditor
 */
export default class TinyMCEDecorator extends JoomlaEditorDecorator {
  /**
   * @returns {string}
   */
  getValue() {
    return this.instance.getContent();
  }

  /**
   * @param {String} value
   * @returns {TinyMCEDecorator}
   */
  setValue(value) {
    this.instance.setContent(value);
    return this;
  }

  /**
   * @returns {string}
   */
  getSelection() {
    return this.instance.selection.getContent({ format: 'text' });
  }

  replaceSelection(value) {
    this.instance.execCommand('mceInsertContent', false, value);
    return this;
  }

  disable(enable) {
    this.instance.setMode(!enable ? 'readonly' : 'design');
    return this;
  }

  /**
   * Toggles the editor visibility mode. Used by Toggle button.
   * Should be implemented by editor provider.
   *
   * @param {boolean} show Optional. True to show, false to hide.
   *
   * @returns {boolean} Return True when editor become visible, and false when become hidden.
   */
  toggle(show) {
    let visible = false;
    if (show || this.instance.isHidden()) {
      this.instance.show();
      visible = true;
    } else {
      this.instance.hide();
    }
    return visible;
  }
}
