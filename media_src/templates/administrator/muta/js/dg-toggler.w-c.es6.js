// https://github.com/dgrammatiko/dark-switch/blob/master/src/index.js
if (!Joomla) throw new Error('The Joomla API is not initialized properly');

const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const lightModeMediaQuery = window.matchMedia('(prefers-color-scheme: light)');
const supported = window.matchMedia('(prefers-color-scheme)').media !== 'not all' ? true : false;

export class Switcher extends HTMLElement {
  constructor() {
    super();

    this.html = document.documentElement;
    this.onClick = this.onClick.bind(this);
    this.systemQuery = this.systemQuery.bind(this);
  }

  get on() { return this.getAttribute('text-on') || 'on'; }
  get off() { return this.getAttribute('text-off') || 'off'; }
  get legend() { return this.getAttribute('text-legend') || 'dark theme'; }
  get forced() { return this.hasAttribute('forced-theme'); }

  connectedCallback() {
    this.state = this.html.dataset && this.html.dataset.bsTheme ? this.html.dataset.bsTheme : 'light';
    if (supported && !this.forced && darkModeMediaQuery.matches) this.state = 'dark';
    if (supported && !this.forced && lightModeMediaQuery.matches) this.state = 'light';
    if (supported && !this.forced) darkModeMediaQuery.addListener(this.systemQuery);

    this.innerHTML = Joomla.sanitizeHtml(`<button tabindex="0" aria-pressed="${this.state == 'dark' ? 'true' : 'false'}">${this.legend}<span aria-hidden="true">${this.state == 'dark' ? this.on : this.off}</span></button>`);
    this.button = this.querySelector('button');
    this.span = this.querySelector('span');
    this.button.addEventListener('click', this.onClick);
    this.applyState();
  }

  disconnectedCallback() {
    if (supported && !this.forced) darkModeMediaQuery.removeListener(this.systemQuery);
    if (this.button) this.button.removeEventListener('click', this.onClick);
  }

  systemQuery(event) {
    this.state = event.matches === true ? 'dark' : 'light';
    this.applyState();
  }
  onClick() {
    this.state = this.state === 'light' ? 'dark' : 'light';
    // fetch(new URL(`${Joomla.getOptions('system.paths').baseFull}index.php?option=com_users&task=user.setA11ySettings&format=json`), {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'X-CSRF-Token': Joomla.getOptions('csrf.token', ''),
    //   },
    //   body: JSON.stringify({data: {prefersColorScheme: value}}),
    //   redirect: 'follow',
    // }).finally(this.applyState).catch(() => {});
    this.applyState();
  }
  applyState() {
    const ev = new Event('joomla:toggle-theme', { bubbles: true, cancelable: false });
    ev.prefersColorScheme = this.state;
    window.dispatchEvent(ev);
    console.log(this.forced)
    this.button.setAttribute('aria-pressed', this.state == 'dark' ? 'true' : 'false');
    this.html.setAttribute('data-bs-theme', this.state === 'dark' ? 'dark' : 'light');
    if (this.forced && navigator.cookieEnabled) {
      const oneYearFromNow = new Date();
      oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
      document.cookie = `mutaPrefersColorScheme=${this.state};path=${Joomla.getOptions('system.paths').base};domain=${location.host};expires=${oneYearFromNow.toGMTString()}`;
    }
  }
}

if (!customElements.get('joomla-theme-switch')) customElements.define('joomla-theme-switch', Switcher);

const get_cookie = (name) => document.cookie.split(';').some(c => c.trim().startsWith(name + '='));

document.querySelectorAll([name=name="jform[params][forcedColorScheme]"]).forEach(el => {
  el.addEventListener('change', (cEl) => {
    if (cEl.value === '0' && get_cookie('mutaPrefersColorScheme')) {
      document.cookie = `mutaPrefersColorScheme;path=${Joomla.getOptions('system.paths').base};domain=${location.host};expires=Thu, 01 Jan 1970 00:00:01 GMT`;
    }
    if (cEl.value === '1' && !get_cookie('mutaPrefersColorScheme')) {
      const oneYearFromNow = new Date();
      oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
      document.cookie = `mutaPrefersColorScheme=${darkModeMediaQuery.matches ? 'dark' : 'light'};path=${Joomla.getOptions('system.paths').base};domain=${location.host};expires=${oneYearFromNow.toGMTString()}`;    }
  })
})
