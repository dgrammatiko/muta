// https://github.com/dgrammatiko/dark-switch/blob/master/src/index.js
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
  get legend() { return this.getAttribute('text-legend') || 'dark theme:'; }
  get forced() { return this.hasAttribute('forced-theme'); }

  connectedCallback() {
    if (this.childElementCount) this.innerHTML = '';
    this.state = this.html.dataset && this.html.dataset.bsTheme ? this.html.dataset.bsTheme : 'light';
    if (supported && !this.forced && darkModeMediaQuery.matches) this.state = 'dark';
    if (supported && !this.forced && lightModeMediaQuery.matches) this.state = 'light';
    if (supported && !this.forced) darkModeMediaQuery.addListener(this.systemQuery);

    this.button = document.createElement('button');
    this.span = document.createElement('span');
    this.button.innerText = this.legend;
    this.button.setAttribute('tabindex', 0);
    this.button.setAttribute('aria-pressed', this.state == 'dark' ? 'true' : 'false');
    this.button.addEventListener('click', this.onClick);
    this.span.setAttribute('aria-hidden', 'true');
    this.span.innerText = this.state == 'dark' ? this.on : this.off;
    this.button.appendChild(this.span);
    this.appendChild(this.button);
    this.applyState();
  }

  disconnectedCallback() {
    if (supported && !this.forced) darkModeMediaQuery.removeListener(this.systemQuery);
    if (this.button) this.button.removeEventListener('click', this.onClick)
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
    this.emit();
    this.setCookie();
  }
  applyState() {
    const ev = new Event('joomla:toggle-theme', { bubbles: true, cancelable: false });
    this.button.setAttribute('aria-pressed', this.state == 'dark' ? 'true' : 'false');
    this.html.setAttribute('data-bs-theme', this.state === 'dark' ? 'dark' : 'light');
    ev.prefersColorScheme = this.state;
    window.dispatchEvent(ev);
    if (!navigator.cookieEnabled) return;
    const oneYearFromNow = new Date();
    oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
    document.cookie = `mutaPrefersColorScheme=${this.state}; expires=${oneYearFromNow.toGMTString()}`;
  }
}

if (!customElements.get('joomla-theme-switch')) customElements.define('joomla-theme-switch', Switcher);
