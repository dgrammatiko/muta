/**
 * @copyright  (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (!Joomla) {
  throw new Error('Joomla API is not initialized');
}

const getCookie = (name) => {
  if (document.cookie.length < 1) return false;
  try {
    const arr = document.cookie.split('; ');
    if (!arr.length) return false;
    const maybe = arr.find((row) => row.startsWith(name));
    if (!maybe.length) return false;
    return maybe.split('=')[1];
  } catch (err) {
    return false;
  }
};

let currentWindowWidth = document.documentElement.width;
const menu = document.querySelector('.sidebar-menu');
const sidebarNav = document.querySelectorAll('.sidebar-nav');
const subhead = document.querySelector('#subhead-container');
const wrapper = document.querySelector('.wrapper');
const sidebarWrapper = document.querySelector('.sidebar-wrapper');
const logo = document.querySelector('.logo');
const isLogin = document.querySelector('body').classList.contains('com_login');
const menuToggleIcon = document.getElementById('menu-collapse-icon');
const navDropDownIcon = document.querySelectorAll('.nav-item.dropdown span[class*="icon-angle-"]');
const headerTitleArea = document.querySelector('#header .header-title');
const headerItemsArea = document.querySelector('#header .header-items');
const headerCondensedItemContainer = document.getElementById('header-more-items');
const headerExpandedItems = [...headerItemsArea.children].filter((element) => element && element.classList.contains('header-item'));
const headerCondensedItems = headerCondensedItemContainer.querySelectorAll('.header-dd-item');
let headerTitleWidth = headerTitleArea.getBoundingClientRect().width;
const headerItemWidths = [...headerExpandedItems.map((element) => element && element.getBoundingClientRect().width)];

// Get the ellipsis button width
headerCondensedItemContainer.classList.remove('d-none');
// eslint-disable-next-line no-unused-expressions
headerCondensedItemContainer.paddingTop;
const ellipsisWidth = headerCondensedItemContainer.getBoundingClientRect().width;
headerCondensedItemContainer.classList.add('d-none');

/**
 * Shrink or extend the logo, depending on sidebar
 *
 * @param {string} [change] is the sidebar 'open' or 'closed'
 *
 * @since   4.0.0
 */
function changeLogo(change, width) {
  if (!logo || isLogin) {
    return;
  }

  if (width < 576) {
    logo.classList.add('small');
    return;
  }

  const state = change || getCookie('mutaSidebarState=');

  if (state === 'closed') {
    logo.classList.add('small');
  } else {
    logo.classList.remove('small');
  }
  if (menuToggleIcon) {
    if (wrapper.classList.contains('closed')) {
      menuToggleIcon.classList.add('icon-toggle-on');
      menuToggleIcon.classList.remove('icon-toggle-off');
    } else {
      menuToggleIcon.classList.remove('icon-toggle-on');
      menuToggleIcon.classList.add('icon-toggle-off');
    }
  }
}

/**
 * toggle arrow icon between down and up depending on position of the nav header
 *
 * @param {string} [positionTop] set if the nav header positioned to the 'top' otherwise 'bottom'
 *
 * @since   4.0.0
 */
function toggleArrowIcon(positionTop) {
  const remIcon = (positionTop) ? 'icon-angle-up' : 'icon-angle-down';
  const addIcon = (positionTop) ? 'icon-angle-down' : 'icon-angle-up';

  if (!navDropDownIcon) {
    return;
  }

  navDropDownIcon.forEach((item) => {
    item.classList.remove(remIcon);
    item.classList.add(addIcon);
  });
}

/**
 *
 * @param {[]} arr
 * @returns {Number}
 */
const getSum = (arr) => arr.reduce((a, b) => Number(a) + Number(b), 0);

/**
 * put elements that are too much in the header in a dropdown
 *
 * @since   4.0.0
 */
function headerItemsInDropdown() {
  headerTitleWidth = headerTitleArea.getBoundingClientRect().width;
  const minViable = headerTitleWidth + ellipsisWidth;
  const totalHeaderItemWidths = 50 + getSum(headerItemWidths);

  if (headerTitleWidth + totalHeaderItemWidths < document.body.getBoundingClientRect().width) {
    headerExpandedItems.forEach((element) => element.classList.remove('d-none'));
    headerCondensedItemContainer.classList.add('d-none');
  } else {
    headerCondensedItemContainer.classList.remove('d-none');
    headerCondensedItems.forEach((el) => el.classList.add('d-none'));
    headerCondensedItemContainer.classList.remove('d-none');
    headerItemWidths.forEach((width, index) => {
      const tempArr = headerItemWidths.slice(index, headerItemWidths.length);
      if (minViable + getSum(tempArr) < document.body.getBoundingClientRect().width) {
        return;
      }
      if (headerExpandedItems[index].children && !headerExpandedItems[index].children[0].classList.contains('dropdown')) {
        headerExpandedItems[index].classList.add('d-none');
        headerCondensedItems[index].classList.remove('d-none');
      }
    });
  }
}

/**
 * Change appearance for mobile devices
 *
 * @since   4.0.0
 */
function setMobile(width) {
  if (width < 576) {
    toggleArrowIcon();

    sidebarNav.forEach((el) => el.classList.add('collapse'));
    if (subhead) subhead.classList.add('collapse');
    if (sidebarWrapper) sidebarWrapper.classList.add('collapse');

    if (menu) wrapper.classList.remove('closed');
  } else {
    toggleArrowIcon('top');
    sidebarNav.forEach((el) => el.classList.remove('collapse'));
    if (subhead) subhead.classList.remove('collapse');
    if (sidebarWrapper) sidebarWrapper.classList.remove('collapse');
  }

  if ((width > 576 && width < 900) && menu && wrapper.classList.contains('open')) {
    wrapper.classList.add('closed');
  }

  changeLogo('closed', width);
}

/**
 * Change appearance for mobile devices
 *
 * @since   4.0.0
 */
function setDesktop() {
  if (!sidebarWrapper) {
    changeLogo('closed', 600);
  } else {
    changeLogo(getCookie('mutaSidebarState=') || 'open', 600);
    sidebarWrapper.classList.remove('collapse');
  }

  sidebarNav.forEach((el) => el.classList.remove('collapse'));
  if (subhead) subhead.classList.remove('collapse');

  toggleArrowIcon('top');
}

function repaint() {
  if (currentWindowWidth < 992) {
    setMobile(currentWindowWidth);
  } else {
    setDesktop();
  }

  headerItemsInDropdown();
}

/**
 * React on resizing window
 *
 * @since   4.0.0
 */
function reactToResize() {
  const resizeObserver = new ResizeObserver((entries) => {
    // eslint-disable-next-line
    for (const entry of entries) {
      if (entry.contentBoxSize) {
        const contentBoxSize = entry.contentBoxSize[0];
        currentWindowWidth = contentBoxSize.inlineSize;
        repaint();
      }
    }
  });

  resizeObserver.observe(document.documentElement);
}

/**
 * Subhead gets white background when user scrolls down
 *
 * @since   4.0.0
 */
function subheadScrolling() {
  if (subhead) {
    document.addEventListener('scroll', () => {
      if (window.scrollY > 0) {
        subhead.classList.add('shadow-sm');
      } else {
        subhead.classList.remove('shadow-sm');
      }
    });
  }
}

if (!navigator.cookieEnabled) {
  Joomla.renderMessages({ error: [Joomla.Text._('JGLOBAL_WARNCOOKIES')] }, undefined, false, 6000);
}

window.addEventListener('joomla:menu-toggle', (event) => {
  document.cookie = `mutaSidebarState=${event.detail};`;
  repaint();
});

// Initialize
subheadScrolling();
reactToResize();
repaint();
