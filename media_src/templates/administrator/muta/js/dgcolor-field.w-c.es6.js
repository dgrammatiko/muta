import { LitElement, html } from 'lit';
import 'toolcool-color-picker';


class DgColor extends LitElement {
  static get properties() {
    return {
      value: {type: Object, value: {}},
      name: {type: String, value: 'muta-colors' }
    };
  }

  constructor() {
    super();

    this.blueprint = {
      main: [
        {
          cssVariableName: 'hue',
          label: 'TPL_MUTA_COLORS_HUE',
          value: 214
        }
      ] ,
      colors: [
        {
          cssVariableName: 'bs-link-color',
          label: 'TPL_MUTA_COLORS_SETTINGS_LINK_COLOR_LABEL',
          value: '#0d6efd'
        },
        {
          cssVariableName: 'bs-link-color-rgb',
          label: '',
          value: '13, 110, 253'
        },
        {
          cssVariableName: 'bs-link-hover-color',
          label: 'TPL_MUTA_COLORS_SETTINGS_LINK_HOVER_COLOR_LABEL',
          value: '#013a8e'
        },
        {
          cssVariableName: 'bs-link-hover-color-rgb',
          label: '',
          value: '1, 58, 142'
        }
      ],
      calculated: [
        {
          cssVariableName: 'template-bg-light',
          label: '',
          value: '#f0f4fb'
        },
        {
          cssVariableName: 'template-text-dark',
          label: '',
          value: '#495057'
        },
        {
          cssVariableName: 'template-text-light',
          label: '',
          value: '#ffffff'
        },
        {
          cssVariableName: 'template-link-color',
          label: '',
          value: '#0d6efd'
        },
        {
          cssVariableName: 'template-link-hover-color',
          label: '',
          value: '#0143a3'
        },
        {
          cssVariableName: 'template-special-color',
          label: '',
          value: '#0d6efd'
        },
        {
          cssVariableName: 'template-sidebar-bg',
          label: '',
          value: 'var(--template-bg-dark-80)'
        },
        {
          cssVariableName: 'template-sidebar-font-color',
          label: '',
          value: '#fff'
        },
        {
          cssVariableName: 'template-sidebar-link-color',
          label: '',
          value: '#fff'
        },
        {
          cssVariableName: 'template-bg-light',
          label: '',
          value: '#f0f4fb'
        },
        {
          cssVariableName: 'template-text-light',
          label: '',
          value: '#fff'
        },
        {
          cssVariableName: 'template-contrast',
          label: '',
          value: '#0d6efd'
        },
        {
          cssVariableName: 'template-bg-dark',
          label: '',
          value: 'hsl(var(--hue), 40%, 20%)'
        },
        {
          cssVariableName: 'template-bg-dark-3',
          label: '',
          value: 'hsl(var(--hue), 40%, 97%)'
        },
        {
          cssVariableName: 'template-bg-dark-5',
          label: '',
          value: 'hsl(var(--hue), 40%, 95%)'
        },
        {
          cssVariableName: 'template-bg-dark-7',
          label: '',
          value: 'hsl(var(--hue), 40%, 93%)'
        },
        {
          cssVariableName: 'template-bg-dark-10',
          label: '',
          value: 'hsl(var(--hue), 40%, 90%)'
        },
        {
          cssVariableName: 'template-bg-dark-15',
          label: '',
          value: 'hsl(var(--hue), 40%, 85%)'
        },
        {
          cssVariableName: 'template-bg-dark-20',
          label: '',
          value: 'hsl(var(--hue), 40%, 80%)'
        },
        {
          cssVariableName: 'template-bg-dark-30',
          label: '',
          value: 'hsl(var(--hue), 40%, 70%)'
        },
        {
          cssVariableName: 'template-bg-dark-40',
          label: '',
          value: 'hsl(var(--hue), 40%, 60%)'
        },
        {
          cssVariableName: 'template-bg-dark-50',
          label: '',
          value: 'hsl(var(--hue), 40%, 50%)'
        },
        {
          cssVariableName: 'template-bg-dark-60',
          label: '',
          value: 'hsl(var(--hue), 40%, 40%)'
        },
        {
          cssVariableName: 'template-bg-dark-65',
          label: '',
          value: 'hsl(var(--hue), 40%, 35%)'
        },
        {
          cssVariableName: 'template-bg-dark-70',
          label: '',
          value: 'hsl(var(--hue), 40%, 30%)'
        },
        {
          cssVariableName: 'template-bg-dark-75',
          label: '',
          value: 'hsl(var(--hue), 40%, 25%)'
        },
        {
          cssVariableName: 'template-bg-dark-80',
          label: '',
          value: 'hsl(var(--hue), 40%, 20%)'
        },
        {
          cssVariableName: 'template-bg-dark-90',
          label: '',
          value: 'hsl(var(--hue), 40%, 10%)'
        },
      ]
    }

    const fallback = JSON.parse('{"bs-link-color":"#144AAB","bs-link-color-rgb":"20, 74, 171","bs-link-hover-color":"#9EC5FE","bs-link-hover-color-rgb":"158,197,254","hue":"214","template-bg-light":"#f0f4fb","template-text-dark":"#495057","template-text-light":"#fff","template-link-color":"rgb(20, 74, 171)","template-link-hover-color":"rgb(14, 56, 108)","template-special-color":"#0d6efd","template-sidebar-bg":"var(--template-bg-dark-80)","template-sidebar-font-color":"#fff","template-sidebar-link-color":"#fff","template-contrast":"#0d6efd","template-bg-dark":"hsl(var(--hue), 40%, 20%)","template-bg-dark-3":"hsl(var(--hue), 40%, 97%)","template-bg-dark-5":"hsl(var(--hue), 40%, 95%)","template-bg-dark-7":"hsl(var(--hue), 40%, 93%)","template-bg-dark-10":"hsl(var(--hue), 40%, 90%)","template-bg-dark-15":"hsl(var(--hue), 40%, 85%)","template-bg-dark-20":"hsl(var(--hue), 40%, 80%)","template-bg-dark-30":"hsl(var(--hue), 40%, 70%)","template-bg-dark-40":"hsl(var(--hue), 40%, 60%)","template-bg-dark-50":"hsl(var(--hue), 40%, 50%)","template-bg-dark-60":"hsl(var(--hue), 40%, 40%)","template-bg-dark-65":"hsl(var(--hue), 40%, 35%)","template-bg-dark-70":"hsl(var(--hue), 40%, 30%)","template-bg-dark-75":"hsl(var(--hue), 40%, 25%)","template-bg-dark-80":"hsl(var(--hue), 40%, 20%)","template-bg-dark-90":"hsl(var(--hue), 40%, 10%)","bs-primary":"#0d6efd","bs-primary-rgb":"13, 110, 253"}');

    try {
      this.value = JSON.parse(this.getAttribute('value'));
    } catch (e) {
      this.value = fallback;
    }

    if (!this.value.length) this.value = fallback;
    this.renderColors = this.renderColors.bind(this);
    this.applyColors = this.applyColors.bind(this);
  }

  createRenderRoot() {
    return this; // Disable shadow DOM.
  }

  connectedCallback() {
    super.connectedCallback();

    this.fields = this.blueprint.colors.filter(x => !x.cssVariableName.endsWith('-rgb'));
    this.sliderValue = this.value.hue;
  }

  firstUpdated() {
    this.querySelector('input[type="hidden"]')?.remove();
  }

  render() {
    this.applyColors();
    return html`${this.createHueField()}${this.fields.map(field => this.createColorField(field))}${this.renderColors()}<input type="hidden" name="${this.name}" .value='${JSON.stringify(this.value)}'>`;
  }

  createHueField() {
    return html`<div class="control-group">
    <div class="control-label"><label id="jform_params_hue-lbl" for="jform_params_hue">Choose your hue value for the dark template colour</label></div>

    <div class="controls">
          <label for="slider-input" class="visually-hidden">Selected Colour Value</label>
          <input type="text" class="form-control" id="slider-input" pattern="[0-9]{1,3}" style="border: 2px solid rgb(19, 47, 83);" .value=${this.sliderValue} @input=${(e) => { this.sliderValue = e.target.value; this.value.hue = e.target.value; this.requestUpdate();} }>
          <label for="hue-slider" class="visually-hidden">Hue Slider</label>
          <input type="range" min="0" step="1" max="360" class="form-control color-slider" .value=${this.sliderValue} @input=${(e) => { this.sliderValue = e.target.value; this.value.hue = e.target.value; this.requestUpdate();} } style="background-image: linear-gradient(90deg, rgb(83, 19, 19), rgb(83, 19, 19), rgb(83, 38, 19), rgb(83, 57, 19), rgb(83, 77, 19), rgb(70, 83, 19), rgb(51, 83, 19), rgb(32, 83, 19), rgb(19, 83, 25), rgb(19, 83, 45), rgb(19, 83, 64), rgb(19, 83, 83), rgb(19, 64, 83), rgb(19, 45, 83), rgb(19, 25, 83), rgb(32, 19, 83), rgb(51, 19, 83), rgb(70, 19, 83), rgb(83, 19, 77), rgb(83, 19, 57), rgb(83, 19, 38), rgb(83, 19, 19), rgb(83, 19, 19)); appearance: none;">
      </div>
    </div>
</div>`
  }

  createColorField(field) {
    return html`<div class="control-group">
    <div class="control-label"><label for=${field.cssVariableName}>${Joomla.Text._(field.label)}</label></div>
    <div class="controls">
      <toolcool-color-picker id=${field.cssVariableName} color=${this.value?.[field.cssVariableName]} @change=${(e) => {
        this.value[`${field.cssVariableName}-rgb`] = e.detail.rgb.replace('rgb(', '').replace(')', '');
        if (field.cssVariableName === 'bs-link-color') this.value['template-link-color'] = e.detail.rgb;
        if (field.cssVariableName === 'bs-link-hover-color') this.value['template-link-hover-color'] = e.detail.rgb;
        this.value[`${field.cssVariableName}`] = e.detail.hex;
        this.value[`${field.cssVariableName}-rgb`] = e.detail.rgb.replace('rgb(', '').replace(')', '');
        this.requestUpdate(); }} button-width="8rem" button-height="3rem" button-padding="2px" style="--tool-cool-color-picker-popup-bg: var(--bs-body-bg); --tool-cool-color-picker-field-label-color: var(--bs-body-color)"></toolcool-color-picker>
    </div>
  </div>`;
  }

  renderColors() {
    return html`<p>CSS Variables:</p>
    <pre><code>--hue: ${this.value?.hue};</code></pre>
    <pre><code>--bs-primary: ${this.value['link-color']};</code></pre>
    <pre><code>--bs-primary-rgb: ${this.value['link-color-rgb']};</code></pre>
    ${this.blueprint.colors.map(field => { return html`<pre><code>--${field.cssVariableName}: ${this.value?.[field.cssVariableName]};</code></pre>`})}
    ${this.blueprint.calculated.map(field => html`<pre><code>--${field.cssVariableName}: ${this.value?.[field.cssVariableName]};</code></pre>`)}
    `
  }

  applyColors() {
    for (const [key, value] of Object.entries(this.value)) {
      document.documentElement.style.setProperty(`--${key}`, value)
    }
    document.documentElement.style.setProperty('--hue', this.value?.hue);
    document.documentElement.style.setProperty('--bs-primary', this.value?.['bs-link-color']);
    document.documentElement.style.setProperty('--bs-primary-rgb', this.value?.['bs-link-color-rgb']);
    document.documentElement.style.setProperty('accent-color', this.value?.['bs-link-color']);
  }
}

customElements.define('dg-color-field', DgColor)
