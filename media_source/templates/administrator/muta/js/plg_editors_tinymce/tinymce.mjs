/**
 * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
if (!Joomla) throw new Error('The API wasn\'t initiated properly');

const pluginOptions = Joomla.getOptions ? Joomla.getOptions('plg_editor_tinymce', {}) : (Joomla.optionsStorage.plg_editor_tinymce || {});
const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const forced = () => document.documentElement.hasAttribute('data-forced-theme');
const onBeforeSubmit = (editor) => editor.on('submit', () => {
  if (editor.isHidden()) {
    editor.show();
  }
}, true);

function registerJoomlaInstance(ed) {
  /** Register the editor's instance to Joomla Object */
  Joomla.editors.instances[ed.id] = {
    getValue: () => ed.getContent(),
    setValue: (text) => ed.setContent(text),
    getSelection: () => ed.selection.getContent({ format: 'text' }),
    replaceSelection: (text) => ed.execCommand('mceInsertContent', false, text),
    disable: (disabled) => ed.setMode(disabled ? 'readonly' : 'design'),
    id: ed.id,
    instance: ed,
  };
}

class Editor {
  constructor(element) {
    if (Joomla.editors.instances[element.id]) return;

    this.element = element;
    this.editorOptions = {};
    this.options = {};
    const theme = document.documentElement.getAttribute('data-bs-theme');
    /* eslint-disable-next-line no-nested-ternary */
    this.theme = !forced() ? darkModeMediaQuery.matches === true ? 'dark' : 'light' : theme;
    this.render = this.render.bind(this);
    this.initOptions = this.initOptions.bind(this);
    this.onPostRender = this.onPostRender.bind(this);
    this.debounce = this.debounce.bind(this);
    this.onLoad = this.onLoad.bind(this);
    this.listenIframeReload = this.listenIframeReload.bind(this);
    this.onPrefersColorScheme = this.onPrefersColorScheme.bind(this);
    this.systemQuery = this.systemQuery.bind(this);

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
    if (options.joomlaMergeDefaults) {
      options = { ...defaultOptions, ...options };
    } else {
      options = { ...options };
    }
    // Ensure tinymce is initialised in readonly mode if the textarea has readonly applied
    let readOnlyMode = false;

    if (this.element) {
      // We already have the Target, so reset the selector and assign given element as target
      options.selector = null;
      options.target = this.element;
      readOnlyMode = this.element.readOnly;
    }

    const buttonValues = [];
    const arr = Object.keys(options.joomlaExtButtons.names).map((key) => options.joomlaExtButtons.names[key]);

    const icons = {
      // eslint-disable-next-line max-len
      joomla: '<svg viewBox="0 0 32 32" width="24" height="24"><path d="M8.313 8.646c1.026-1.026 2.688-1.026 3.713-0.001l0.245 0.246 3.159-3.161-0.246-0.246c-1.801-1.803-4.329-2.434-6.638-1.891-0.331-2.037-2.096-3.591-4.224-3.592-2.364 0-4.28 1.92-4.28 4.286 0 2.042 1.425 3.75 3.333 4.182-0.723 2.42-0.133 5.151 1.776 7.062l7.12 7.122 3.156-3.163-7.119-7.121c-1.021-1.023-1.023-2.691 0.006-3.722zM31.96 4.286c0-2.368-1.916-4.286-4.281-4.286-2.164 0-3.952 1.608-4.24 3.695-2.409-0.708-5.118-0.109-7.020 1.794l-7.12 7.122 3.159 3.162 7.118-7.12c1.029-1.030 2.687-1.028 3.709-0.006 1.025 1.026 1.025 2.691-0.001 3.717l-0.244 0.245 3.157 3.164 0.246-0.248c1.889-1.893 2.49-4.586 1.8-6.989 2.098-0.276 3.717-2.074 3.717-4.25zM28.321 23.471c0.566-2.327-0.062-4.885-1.878-6.703l-7.109-7.125-3.159 3.16 7.11 7.125c1.029 1.031 1.027 2.691 0.006 3.714-1.025 1.025-2.688 1.025-3.714-0.001l-0.243-0.243-3.156 3.164 0.242 0.241c1.922 1.925 4.676 2.514 7.105 1.765 0.395 1.959 2.123 3.431 4.196 3.431 2.363 0 4.28-1.917 4.28-4.285 0-2.163-1.599-3.952-3.679-4.244zM19.136 16.521l-7.111 7.125c-1.022 1.024-2.689 1.026-3.717-0.004-1.026-1.028-1.026-2.691-0.001-3.718l0.244-0.243-3.159-3.16-0.242 0.241c-1.836 1.838-2.455 4.432-1.858 6.781-1.887 0.446-3.292 2.145-3.292 4.172-0.001 2.367 1.917 4.285 4.281 4.285 2.034-0.001 3.737-1.419 4.173-3.324 2.334 0.58 4.906-0.041 6.729-1.867l7.109-7.124-3.157-3.163z"></path></svg>',
    };

    arr.forEach((xtdButton) => {
      const tmp = {};
      tmp.text = xtdButton.name;
      tmp.icon = xtdButton.icon;
      tmp.type = 'menuitem';

      if (xtdButton.iconSVG) {
        icons[tmp.icon] = xtdButton.iconSVG;
      }

      if (xtdButton.href) {
        tmp.onAction = () => {
          document.getElementById(`${xtdButton.id}_modal`).open();
        };
      } else {
        tmp.onAction = () => {
          // eslint-disable-next-line no-new-func
          new Function(xtdButton.click)();
        };
      }

      buttonValues.push(tmp);
    });

    if (buttonValues.length) {
      options.setup = (editor) => {
        editor.mode.set(readOnlyMode ? 'readonly' : 'design');

        Object.keys(icons).forEach((icon) => editor.ui.registry.addIcon(icon, icons[icon]));

        editor.ui.registry.addMenuButton('jxtdbuttons', {
          text: Joomla.Text._('PLG_TINY_CORE_BUTTONS'),
          icon: 'joomla',
          fetch: (callback) => callback(buttonValues),
        });
      };
    } else {
      options.setup = (editor) => editor.mode.set(readOnlyMode ? 'readonly' : 'design');
    }

    // We'll take over the onSubmit event
    options.init_instance_callback = onBeforeSubmit;
    this.editorOptions = options;
  }

  // tinyMCE themes docs: https://www.tiny.cloud/docs/general-configuration-guide/customize-ui/
  render() {
    tinyMCE.remove(`#${this.element.id}`);
    this.editor = null;
    const options = {
      skin: this.theme === 'light' ? this.editorOptions.skin : 'oxide-dark',
      content_css: `${this.editorOptions.content_css}${this.theme === 'light' ? '' : ',dark'}`,
    };
    this.options = { ...this.editorOptions, ...options };
    this.editor = new tinyMCE.Editor(this.element.id, this.options, tinymce.EditorManager);

    // Work around iframe behavior, when iframe element changes location in DOM and losing its content.
    // @todo v6 use a promise based approach
    if (!this.editor.inline) {
      this.isReady = false;
      this.isRendered = false;
      this.editor.on('load', this.onLoad);
      this.editor.on('PostRender', this.onPostRender);
    }

    this.editor.render();

    registerJoomlaInstance(this.editor);
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
  const editorEl = event.target.closest('.js-editor-tinymce').querySelector('textarea');
  const toggleIcon = editorEl.querySelector('.icon-eye');

  if (Joomla.editors.instances[editorEl.id].instance.isHidden()) {
    Joomla.editors.instances[editorEl.id].instance.show();
  } else {
    Joomla.editors.instances[editorEl.id].instance.hide();
  }

  if (toggleIcon) {
    toggleIcon.setAttribute('class', Joomla.editors.instances[editorEl.id].instance.isHidden() ? 'icon-eye' : 'icon-eye-slash');
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

/**
 * Initialize at an initial page load
 */
setupEditors(document);

/**
 * Initialize when a part of the page was updated
 */
document.addEventListener('joomla:updated', ({ target }) => setupEditors(target));
