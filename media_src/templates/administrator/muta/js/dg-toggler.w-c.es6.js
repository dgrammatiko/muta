// https://github.com/dgrammatiko/dark-switch/blob/master/src/index.js
const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const supportsMediaColorScheme = window.matchMedia('(prefers-color-scheme)').media !== 'not all' ? true : false;

export class Switcher extends HTMLElement {
  constructor() {
    super();

    this.html = document.documentElement;
    this.onClick = this.onClick.bind(this);
    this.systemQuery = this.systemQuery.bind(this);
    this.applyState = this.applyState.bind(this);
  }

  get on() { return this.getAttribute('text-on') || 'on'; }
  get off() { return this.getAttribute('text-off') || 'off'; }
  get legend() { return this.getAttribute('text-legend') || 'dark theme:'; }
  get forced() { return this.hasAttribute('forced-theme'); }

  connectedCallback() {
    this.state = this.html.dataset && this.html.dataset.bsTheme ? this.html.dataset.bsTheme : 'light';
    this.render();

    if (supportsMediaColorScheme && !this.forced) {
      if (darkModeMediaQuery.matches) {
        this.state = 'dark';
        this.applyState();
      }
      darkModeMediaQuery.addListener(this.systemQuery);
    }
  }

  systemQuery(event) {
    this.state = event.matches === true ? 'dark' : 'light';
    this.applyState();
  }

  disconnectedCallback() {
    if (supportsMediaColorScheme && !this.forced) {
      darkModeMediaQuery.removeListener(this.systemQuery);
    }
    if (this.button) {
      this.button.removeEventListener('click', this.onClick)
    }
  }

  onClick() {
    this.state = this.state === 'light' ? 'dark' : 'light';
    // this.syncValues(this.state).then(() => this.applyState).catch(() => { return; });
    this.applyState()
  }

  applyState() {
    this.button.setAttribute('aria-pressed', this.state == 'dark' ? 'true' : 'false');
    this.html.setAttribute('data-bs-theme', this.state === 'dark' ? 'dark' : 'light');
    window.dispatchEvent(new CustomEvent('joomla:toggle-theme', { detail: { prefersColorScheme: this.state } }));
    if (navigator.cookieEnabled) {
      const oneYearFromNow = new Date();
      oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
      document.cookie = `mutaPrefersColorScheme=${this.state}; expires=${oneYearFromNow.toGMTString()}`;
    }
  }
  // syncValues(value = 'light') {
  //   const urlBase = Joomla.getOptions('system.paths').baseFull;
  //   return fetch(new URL(`${urlBase}index.php?option=com_users&task=user.setA11ySettings&format=json`), {
  //     method: 'POST',
  //     headers: {
  //       'Content-Type': 'application/json',
  //       'X-CSRF-Token': Joomla.getOptions('csrf.token', ''),
  //     },
  //     body: JSON.stringify({data: {prefersColorScheme: value}}),
  //     redirect: 'follow',
  //   });
  // }

  render() {
    if (!this.button) {
      this.button = document.createElement('button');
      this.button.innerText = this.legend;
      this.button.setAttribute('tabindex', 0)
      this.button.setAttribute('aria-pressed', this.state == 'dark' ? 'true' : 'false');
      this.span = document.createElement('span');
      this.span.setAttribute('aria-hidden', 'true');
      this.span.innerText = this.state == 'dark' ? this.on : this.off;
      this.button.appendChild(this.span);

      this.button.addEventListener('click', this.onClick)

      this.appendChild(this.button)
    }
  }
}

if (!customElements.get('joomla-theme-switch')) customElements.define('joomla-theme-switch', Switcher);
