// https://github.com/dgrammatiko/dark-switch/blob/master/src/index.js
if (!Joomla) throw new Error('The Joomla API is not initialized properly');

const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const lightModeMediaQuery = window.matchMedia('(prefers-color-scheme: light)');
const supported = window.matchMedia('(prefers-color-scheme)').media !== 'not all';
const forced = () => document.documentElement.hasAttribute('data-forced-theme');

class Switcher extends HTMLElement {
  constructor() {
    super();

    this.html = document.documentElement;
    this.onClick = this.onClick.bind(this);
    this.systemQuery = this.systemQuery.bind(this);
  }

  get on() { return this.getAttribute('text-on') || 'on'; }

  get off() { return this.getAttribute('text-off') || 'off'; }

  get legend() { return this.getAttribute('text-legend') || 'dark theme'; }

  connectedCallback() {
    this.state = forced() && this.html.dataset && this.html.dataset.bsTheme ? this.html.dataset.bsTheme : 'light';
    if (supported && !forced()) {
      this.state = supported && lightModeMediaQuery.matches ? 'light' : 'dark';
      darkModeMediaQuery.addListener(this.systemQuery);
    }

    this.innerHTML = Joomla.sanitizeHtml(`<button tabindex="0" aria-pressed="${this.state === 'dark' ? 'true'
      : 'false'}"><span aria-hidden="true">${this.legend} ${this.state === 'dark' ? this.on : this.off}</span></button>`);
    this.button = this.querySelector('button');
    this.span = this.querySelector('span');
    this.button.addEventListener('click', this.onClick);
    this.applyState();
  }

  disconnectedCallback() {
    if (supported && !forced()) darkModeMediaQuery.removeListener(this.systemQuery);
    if (this.button) this.button.removeEventListener('click', this.onClick);
  }

  systemQuery(event) {
    this.state = event.matches === true ? 'dark' : 'light';
    this.applyState();
  }

  onClick() {
    this.state = this.state === 'light' ? 'dark' : 'light';
    this.applyState();
  }

  applyState() {
    const ev = new Event('joomla:toggle-theme', { bubbles: true, cancelable: false });
    ev.prefersColorScheme = this.state;
    window.dispatchEvent(ev);
    this.button.setAttribute('aria-pressed', this.state === 'dark' ? 'true' : 'false');
    this.html.setAttribute('data-bs-theme', this.state === 'dark' ? 'dark' : 'light');
    if (navigator.cookieEnabled) {
      if (forced()) {
        const oneYearFromNow = new Date();
        oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
        document.cookie = `mutaPrefersColorScheme=${this.state};expires=${oneYearFromNow.toGMTString()}`;
      } else {
        document.cookie = `mutaPrefersColorScheme=${this.state};expires=Thu, 01 Jan 1970 00:00:01 GMT`;
      }
    }
  }
}

if (!customElements.get('joomla-theme-switch')) customElements.define('joomla-theme-switch', Switcher);
