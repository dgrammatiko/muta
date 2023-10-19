/**
 * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// eslint-disable-next-line import/no-unresolved
import { JoomlaEditor } from 'editor-api';
import TinyMCEDecorator from './_tinyDecorator.mjs';

if (!Joomla) throw new Error('The API wasn\'t initiated properly');

const evNames = ['render', 'initOptions', 'onPostRender', 'debounce', 'onLoad', 'listenIframeReload', 'onPrefersColorScheme', 'systemQuery', 'onFocus'];
const pluginOptions = Joomla.getOptions ? Joomla.getOptions('plg_editor_tinymce', {}) : (Joomla.optionsStorage.plg_editor_tinymce || {});
const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const forced = () => document.documentElement.hasAttribute('data-forced-theme');
const onBeforeSubmit = (editor) => editor.on('submit', () => {
  if (editor.isHidden()) {
    editor.show();
  }
}, true);

class Editor {
  constructor(element) {
    // Check whether the editor already has been set
    if (JoomlaEditor.get(element.id)) return;

    this.element = element;
    this.editorOptions = {};
    const theme = document.documentElement.getAttribute('data-bs-theme');
    /* eslint-disable-next-line no-nested-ternary */
    this.theme = !forced() ? darkModeMediaQuery.matches === true ? 'dark' : 'light' : theme;
    evNames.forEach((e) => { this[e] = this[e].bind(this); });

    this.initOptions();
    this.render();

    // Check for color-scheme changes in OS
    window.addEventListener('joomla:toggle-theme', this.onPrefersColorScheme);
    darkModeMediaQuery.addListener(this.systemQuery);
  }

  initOptions() {
    const name = this.element ? this.element.getAttribute('name').replace(/\[\]|\]/g, '').split('[').pop() : 'default'; // Get Editor name
    const tinyMCEOptions = pluginOptions ? pluginOptions.tinyMCE || {} : {};
    const defaultOptions = tinyMCEOptions.default || {};
    // Check specific options by the name
    let options = tinyMCEOptions[name] ? tinyMCEOptions[name] : defaultOptions;
    // Avoid an unexpected changes, and copy the options object
    options = options.joomlaMergeDefaults ? { ...defaultOptions, ...options } : { ...options };
    // Ensure tinymce is initialised in readonly mode if the textarea has readonly applied
    let readOnlyMode = false;

    if (this.element) {
      // We already have the Target, so reset the selector and assign given element as target
      options.selector = null;
      options.target = this.element;
      readOnlyMode = this.element.readOnly;
    }

    options.setup = (editor) => editor.mode.set(readOnlyMode ? 'readonly' : 'design');
    options.init_instance_callback = onBeforeSubmit;
    this.editorOptions = options;
  }

  render() {
    if (this.editor) {
      this.editor.remove();
      JoomlaEditor.unregister(this.jEditor);
    }
    this.editor = null;
    this.jEditor = null;
    const options = {
      ...this.editorOptions,
      ...{
        // tinyMCE themes docs: https://www.tiny.cloud/docs/general-configuration-guide/customize-ui/
        skin: this.theme === 'light' ? this.editorOptions.skin : 'oxide-dark',
        content_css: `${this.editorOptions.content_css}${this.theme === 'light' ? '' : ',dark'}`,
      },
    };

    this.editor = new tinyMCE.Editor(this.element.id, options, tinymce.EditorManager);
    this.jEditor = new TinyMCEDecorator(this.editor, 'tinymce', this.element.id);

    // Work around iframe behavior, when iframe element changes location in DOM and losing its content.
    // @todo v6 use a promise based approach
    if (!this.editor.inline) {
      this.isReady = false;
      this.isRendered = false;
      this.editor.on('load', this.onLoad);
      this.editor.on('PostRender', this.onPostRender);
    }

    this.editor.on('focus', this.onFocus);
    requestAnimationFrame(() => {
      this.editor.render();
      // Register the editor's instance to JoomlaEditor
      JoomlaEditor.register(this.jEditor);
    });
  }

  onFocus() {
    JoomlaEditor.setActive(this.jEditor);
  }

  listenIframeReload() {
    this.editor.getContentAreaContainer().querySelector('iframe').addEventListener('load', this.debounce);
  }

  onPostRender() {
    this.isRendered = true;
    if (this.isReady) {
      this.listenIframeReload();
    }
  }

  onLoad() {
    this.isReady = true;
    if (this.isRendered) {
      this.listenIframeReload();
    }
  }

  debounce() {
    if (this.timer) clearTimeout(this.timer);
    this.timer = setTimeout(this.render, 500);
  }

  onPrefersColorScheme(ev) {
    if (['dark', 'light'].includes(ev.prefersColorScheme)) {
      if (this.theme !== ev.prefersColorScheme) {
        this.theme = ev.prefersColorScheme;
        this.render();
      }
    }
  }

  systemQuery(event) {
    if (forced()) return;
    const theme = event.matches === true ? 'dark' : 'light';
    if (this.theme !== theme) {
      this.theme = theme;
      this.render();
    }
  }
}

function toggleButtonFn(event) {
  const currentEditor = event.target.closest('.js-editor-tinymce').querySelector('textarea');
  const toggler = event.target.closest('.js-tiny-toggler-button');
  const toggleIcon = toggler.querySelector('.icon-eye');
  JoomlaEditor.setActive(currentEditor.id);
  const ed = JoomlaEditor.getActive();

  if (toggler && ed) {
    const visible = ed.toggle();

    if (toggleIcon) {
      toggleIcon.setAttribute('class', visible ? 'icon-eye' : 'icon-eye-slash');
    }
  }
}

/**
 * Find all TinyMCE elements and initialize TinyMCE instance for each
 *
 * @param {HTMLElement}  target  Target Element where to search for the editor element
 *
 * @since 3.7.0
 */
function setupEditors(target) {
  (target || document).querySelectorAll('.js-editor-tinymce').forEach((editor) => {
    // Setup the editor
    // eslint-disable-next-line no-new
    new Editor(editor.querySelector('textarea'));

    // Setup the toggle button
    const toggleButton = editor.querySelector('.js-tiny-toggler-button');
    if (!toggleButton) return;
    toggleButton.removeAttribute('disabled');
    toggleButton.addEventListener('click', toggleButtonFn);
  });
}

/** Initialize at an initial page load */
setupEditors(document);

/** Initialize when a part of the page was updated */
document.addEventListener('joomla:updated', ({ target }) => setupEditors(target));
