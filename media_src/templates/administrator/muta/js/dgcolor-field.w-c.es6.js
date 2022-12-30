import { LitElement, html } from 'lit';
import 'toolcool-color-picker';
import fallbackJson from '../../../../../src/templates/administrator/muta/fields/def.json';

const blueprint = {
  colors: [
    {
      type: 'hue',
      cssVariableName: 'hue',
      label: 'TPL_MUTA_COLORS_HUE',
    },
    {
      type: 'picker',
      cssVariableName: 'bs-link-color',
      label: 'TPL_MUTA_COLORS_SETTINGS_LINK_COLOR_LABEL',
    },
    {
      type: 'picker',
      cssVariableName: 'bs-link-hover-color',
      label: 'TPL_MUTA_COLORS_SETTINGS_LINK_HOVER_COLOR_LABEL',
    },
    {
      type: 'calculated',
      cssVariableName: 'bs-primary',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'bs-primary-rgb',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'link-color',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'link-color-rgb',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'bs-link-color-rgb',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'bs-link-hover-color-rgb',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-light',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-text-dark',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-text-light',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-link-color',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-link-hover-color',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-special-color',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-sidebar-bg',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-sidebar-font-color',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-sidebar-link-color',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-light',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-text-light',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-contrast',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-3',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-5',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-7',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-10',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-15',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-20',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-30',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-40',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-50',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-60',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-65',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-70',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-75',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-80',
      label: '',
    },
    {
      type: 'calculated',
      cssVariableName: 'template-bg-dark-90',
      label: '',
    },
  ],
};

class DgColor extends LitElement {
  static get properties() {
    return {
      value: {type: Object, value: {}},
      name: {type: String, value: 'muta-colors' }
    };
  }

  constructor() {
    super();

    try {
      this.value = JSON.parse(this.getAttribute('value'));
    } catch (e) {
      this.value = fallbackJson;
    }

    if (!this.value.length) this.value = fallbackJson;
    this.applyColors = this.applyColors.bind(this);
  }

  createRenderRoot() {
    return this; // Disable shadow DOM.
  }

  connectedCallback() {
    super.connectedCallback();

    this.fields = blueprint.colors.filter(x => !x.cssVariableName.endsWith('-rgb'));
    this.sliderValue = this.value.hue;
  }

  firstUpdated() {
    this.querySelector('input[type="hidden"]')?.remove();
  }

  render() {
    this.applyColors();
    return html`${this.fields.map(field => {
      if (field.type === 'picker') return this.createColorField(field);
      else if (field.type === 'hue') return this.createHueField();
    })}
    <button class="btn btn-warning" type="button" @click=${() => {this.value = fallbackJson; this.requestUpdate;}}>Reset</button>
    <p>CSS Variables:</p>${blueprint.colors.map(field => { return html`<pre><code>--${field.cssVariableName}: ${this.value?.[field.cssVariableName]};</code></pre>`})}
    <input type="hidden" name="${this.name}" .value='${JSON.stringify(this.value)}'>`;
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
        if (field.cssVariableName === 'bs-link-color') {
          this.value['template-link-color'] = e.detail.rgb;
          this.value['link-color'] = e.detail.rgb;
          this.value['bs-primary'] = e.detail.rgb;
        }
        if (field.cssVariableName === 'bs-link-hover-color') {
          this.value['template-link-hover-color'] = e.detail.rgb;
          this.value['link-hover-color'] = e.detail.rgb;
          this.value['bs-primary-rgb'] = e.detail.rgb;
        }
        this.value[`${field.cssVariableName}`] = e.detail.hex;
        this.value[`${field.cssVariableName}-rgb`] = e.detail.rgb.replace('rgb(', '').replace(')', '');
        this.requestUpdate(); }} button-width="8rem" button-height="3rem" button-padding="2px" style="--tool-cool-color-picker-popup-bg: var(--bs-body-bg); --tool-cool-color-picker-field-label-color: var(--bs-body-color)"></toolcool-color-picker>
    </div>
  </div>`;
  }

  applyColors() {
    for (const [key, value] of Object.entries(this.value)) {
      document.documentElement.style.setProperty(`--${key}`, value)
    }
    document.documentElement.style.setProperty('accent-color', this.value?.['bs-link-color']);
  }
}

customElements.define('dg-color-field', DgColor)
