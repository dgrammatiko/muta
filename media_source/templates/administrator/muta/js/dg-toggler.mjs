// https://github.com/dgrammatiko/dark-switch/blob/master/src/index.js
const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
const lightModeMediaQuery = window.matchMedia('(prefers-color-scheme: light)');
const supported = window.matchMedia('(prefers-color-scheme)').media !== 'not all';
const forced = () => ('forcedTheme' in document.documentElement.dataset);

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
  top: .15rem;
  bottom: 0;
  left: .15rem;
  width: calc(var(--size) * .8rem);
  height: calc(var(--size) * .8rem);
  padding: 0;
  margin: 0;
  content: "";
  background-color: #000;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' aria-hidden='true' focusable='false' role='img' height='.8rem' viewBox='0 0 384 512'%3E%3Cpath fill='white' d='M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  border-radius: calc(var(--size) * .8rem);
  transition: background-color ease 0.4s, transform ease 0.4s;
}

button[aria-pressed=false] {
  background-color: royalblue;
}

button[aria-pressed=false]::before {
  background-color: royalblue;
  background-image: url("data:image/svg+xml,%3Csvg aria-hidden='true' focusable='false' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1024 1024'%3E%3Cpath fill='yellow' stroke='yellow' stroke-width='15' d='M469.333333 128a42.666667 42.666667 0 0 1 85.333334 0v85.333333a42.666667 42.666667 0 0 1-85.333334 0V128z m0 682.666667a42.666667 42.666667 0 0 1 85.333334 0v85.333333a42.666667 42.666667 0 0 1-85.333334 0v-85.333333z m42.666667-85.333334a213.333333 213.333333 0 1 1 0-426.666666 213.333333 213.333333 0 0 1 0 426.666666z m0-85.333333a128 128 0 1 0 0-256 128 128 0 0 0 0 256z m-384-85.333333a42.666667 42.666667 0 0 1 0-85.333334h85.333333a42.666667 42.666667 0 0 1 0 85.333334H128z m682.666667 0a42.666667 42.666667 0 0 1 0-85.333334h85.333333a42.666667 42.666667 0 0 1 0 85.333334h-85.333333z m-30.165334-371.498667a42.666667 42.666667 0 0 1 60.330667 60.330667l-67.456 67.456a42.666667 42.666667 0 0 1-60.330667-60.330667l67.413334-67.456zM243.498667 840.832a42.666667 42.666667 0 1 1-60.330667-60.330667l67.456-67.456a42.666667 42.666667 0 1 1 60.330667 60.330667l-67.413334 67.456z m-60.330667-597.333333a42.666667 42.666667 0 0 1 60.330667-60.330667l67.456 67.456a42.666667 42.666667 0 0 1-60.330667 60.330667l-67.456-67.413334z m657.664 537.002666a42.666667 42.666667 0 0 1-60.330667 60.330667l-67.456-67.456a42.666667 42.666667 0 0 1 60.330667-60.330667l67.456 67.413334z'%3E%3C/path%3E%3C/svg%3E");
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
    this.button.addEventListener('click', this.onClick);
  }

  get on() { return this.getAttribute('text-on') || 'on'; }

  get off() { return this.getAttribute('text-off') || 'off'; }

  get legend() { return this.getAttribute('text-legend') || 'dark theme'; }

  connectedCallback() {
    this.state = forced() && this.html.dataset && this.html.dataset.theme ? this.html.dataset.theme : 'light';
    if (supported && !forced()) {
      this.state = lightModeMediaQuery.matches ? 'light' : 'dark';
      darkModeMediaQuery.addListener(this.systemQuery);
    }

    this.button.setAttribute('aria-pressed', this.state === 'dark' ? 'true' : 'false');
    this.span.innerText = `${this.legend} ${this.state === 'dark' ? this.on : this.off}`;
    this.applyState();
  }

  disconnectedCallback() {
    if (supported && !forced()) darkModeMediaQuery.removeListener(this.systemQuery);
  }

  systemQuery(event) {
    this.state = event.matches === true ? 'dark' : 'light';
    this.applyState();
  }

  onClick() {
    if (!forced()) {
      this.button.setAttribute('aria-pressed', this.state === 'dark' ? 'true' : 'false');
      return;
    }
    this.state = this.state === 'light' ? 'dark' : 'light';
    this.applyState();
  }

  applyState() {
    this.button.setAttribute('aria-pressed', this.state === 'dark' ? 'true' : 'false');
    this.span.innerText = `${this.legend} ${this.state === 'dark' ? this.on : this.off}`;
    this.html.setAttribute('data-bs-theme', this.state === 'dark' ? 'dark' : 'light');
    this.html.setAttribute('data-theme', this.state === 'dark' ? 'dark' : 'light');
    if (forced()) {
      const ev = new Event('joomla:toggle-theme', { bubbles: true, cancelable: false });
      ev.prefersColorScheme = this.state;
      window.dispatchEvent(ev);
      if (navigator.cookieEnabled) {
        const oneYearFromNow = new Date();
        oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
        document.cookie = `mutaPrefersColorScheme=${this.state};expires=${oneYearFromNow.toGMTString()}`;
      }
    }
  }
}

if (!customElements.get('joomla-theme-switch')) customElements.define('joomla-theme-switch', Switcher);
