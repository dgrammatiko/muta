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
    this.attachShadow({ mode: 'open' });
    this.shadowRoot.innerHTML = `
    <style>
:host {
  --size: 1.5;
  position: relative;
  display: inline-block;
  width: calc(var(--size) * 2rem);
  height: calc(var(--size) * 1rem);
}

button {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: calc(var(--size) * 2rem);
  height: calc(var(--size) * 1rem);
  padding: 0;
  margin: 0;
  color: transparent;
  background-color: #000;
  border: 0;
  border-radius: calc(var(--size) * 1rem);
  transition: background-color ease 0.4s, transform ease 0.4s;
}

button > * {
  pointer-events: none;
}

button::before {
  position: absolute;
  top: .2rem;
  bottom: 0;
  left: .2rem;
  width: calc(var(--size) * .7rem);
  height: calc(var(--size) * .7rem);
  padding: 0;
  margin: 0;
  content: "";
  background-color: #fff;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' aria-hidden='true' focusable='false' role='img' height='.8rem' viewBox='0 0 384 512'%3E%3Cpath fill='black' d='M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  border-radius: calc(var(--size) * .8rem);
  transition: background-color ease 0.4s, transform ease 0.4s;
}

button[aria-pressed=false] {
  background-color: skyblue;
}

button[aria-pressed=false]::before {
  background-color: skyblue;
  background-image: url("data:image/svg+xml,%3Csvg aria-hidden='true' focusable='false' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='yellow' d='M361.5 1.2c5 2.1 8.6 6.6 9.6 11.9L391 121l107.9 19.8c5.3 1 9.8 4.6 11.9 9.6s1.5 10.7-1.6 15.2L446.9 256l62.3 90.3c3.1 4.5 3.7 10.2 1.6 15.2s-6.6 8.6-11.9 9.6L391 391 371.1 498.9c-1 5.3-4.6 9.8-9.6 11.9s-10.7 1.5-15.2-1.6L256 446.9l-90.3 62.3c-4.5 3.1-10.2 3.7-15.2 1.6s-8.6-6.6-9.6-11.9L121 391 13.1 371.1c-5.3-1-9.8-4.6-11.9-9.6s-1.5-10.7 1.6-15.2L65.1 256 2.8 165.7c-3.1-4.5-3.7-10.2-1.6-15.2s6.6-8.6 11.9-9.6L121 121 140.9 13.1c1-5.3 4.6-9.8 9.6-11.9s10.7-1.5 15.2 1.6L256 65.1 346.3 2.8c4.5-3.1 10.2-3.7 15.2-1.6zM352 256c0 53-43 96-96 96s-96-43-96-96s43-96 96-96s96 43 96 96zm32 0c0-70.7-57.3-128-128-128s-128 57.3-128 128s57.3 128 128 128s128-57.3 128-128z'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  transform: translateX(1.5rem);
}

button span {
  clip: rect(0 0 0 0);
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}

@media (prefers-reduced-motion: reduce) {
  button, button::before {
    transition: all 0.1s;
  }
}
</style>
<button><span aria-hidden="true"></span></button>
    `;

    this.button = this.shadowRoot.querySelector('button');
    this.span = this.shadowRoot.querySelector('span');
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

    this.button.setAttribute('aria-pressed', this.state === 'dark' ? 'true' : 'false');
    this.span.innerText = `${this.legend} ${this.state === 'dark' ? this.on : this.off}`;
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
    this.span.innerText = `${this.legend} ${this.state === 'dark' ? this.on : this.off}`;
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
