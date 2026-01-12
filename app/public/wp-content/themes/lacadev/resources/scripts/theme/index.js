/* eslint-disable no-unused-vars */
import '@images/favicon.ico';
import '@styles/theme';
import './pages/*.js';
import './ajax-search.js';
import Swup from 'swup';
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import AOS from "aos/dist/aos";
import Swal from 'sweetalert2';

document.addEventListener( 'DOMContentLoaded', () => {
	const swup = new Swup();
	initializePageFeatures();

	swup.hooks.on( 'content:replace', () => {
		initializePageFeatures();
	} );
} );

function initializePageFeatures() {
  initAnimations();
  setupGsap404();
  setupBackToTopButton();
  setupMenuFixedBehavior();
  setupMobileMenuHandling();
  setupSubmenuToggleHandling();
  setupHideHeaderOnScroll();
  setupAjaxSendMail();
  setupMenuMobile();
  alertDropdownSubMenu();
  setupSelect2();
  setupProjectFilter();
}

/**
 * Khởi tạo hoạt ảnh GSAP và AOS
 */
function initAnimations() {
  // GSAP
  gsap.registerPlugin(ScrollTrigger);
  gsap.from(".welcome-aiot", {
    x: '50%',
    duration: 2,
    opacity: 0.3,
    scrollTrigger: {
      trigger: ".welcome-aiot",
      start: "top 80%",
      end: "bottom 20%",
      scrub: true,
    }
  });

  // AOS
  AOS.init({
    duration: 400,
  });
}

/**
 * Setup GSAP for 404 page
 */

function setupGsap404() {
  gsap.set("svg", { visibility: "visible" });

  gsap.to("#spaceman", {
    y: 5,
    rotation: 2,
    yoyo: true,
    repeat: -1,
    ease: "sine.inOut",
    duration: 1,
  });

  gsap.to("#starsBig line", {
    rotation: "random(-30,30)",
    transformOrigin: "50% 50%",
    yoyo: true,
    repeat: -1,
    ease: "sine.inOut",
  });

  gsap.fromTo(
    "#starsSmall g",
    { scale: 0 },
    {
      scale: 1,
      transformOrigin: "50% 50%",
      yoyo: true,
      repeat: -1,
      stagger: 0.1,
    }
  );

  gsap.to("#circlesSmall circle", {
    y: -4,
    yoyo: true,
    duration: 1,
    ease: "sine.inOut",
    repeat: -1,
  });

  gsap.to("#circlesBig circle", {
    y: -2,
    yoyo: true,
    duration: 1,
    ease: "sine.inOut",
    repeat: -1,
  });

  gsap.set("#glassShine", { x: -68 });
  gsap.to("#glassShine", {
    x: 80,
    duration: 2,
    rotation: -30,
    ease: "expo.inOut",
    transformOrigin: "50% 50%",
    repeat: -1,
    repeatDelay: 8,
    delay: 2,
  });
}

/**
 * Back to top button with hide on scroll and footer detection
 */
function setupBackToTopButton() {
  const backToTopBtn = document.getElementById('back-to-top');
  const footer = document.getElementById('footer');
  if (!backToTopBtn || !footer) return;

  const footerOffsetTop = footer.offsetTop;
  const windowHeight = window.innerHeight;
  let scrollTimeout;
  const pageHeight = document.documentElement.scrollHeight;
  const scrollThreshold = pageHeight * 0.1; // 10% chiều cao trang

  // Sự kiện scroll để xử lý nút "Back to top"
  window.addEventListener('scroll', function () {
    const scrollTop = window.scrollY;

    // Kiểm tra nếu người dùng đã scroll quá 10% chiều cao trang để hiện nút "Back to top"
    if (scrollTop > scrollThreshold) {
      backToTopBtn.classList.add('fixed'); // Thêm class fixed
    } else {
      backToTopBtn.classList.remove('fixed', 'show', 'hidden'); // Xóa tất cả các class nếu cuộn lên trên ngưỡng
    }

    // Ẩn nút "Back to top" khi scroll (cả cuộn lên và cuộn xuống đều ẩn)
    backToTopBtn.classList.add('hidden');
    backToTopBtn.classList.remove('show'); // Ẩn nút khi có cuộn

    // Kiểm tra nếu gần tới footer thì xóa cả `fixed` và `hidden`
    if (scrollTop + windowHeight >= footerOffsetTop) {
      backToTopBtn.classList.remove('fixed', 'hidden');
      backToTopBtn.classList.add('show'); // Xóa `fixed`, hiện lại nút khi gần footer
    }

    // Xác định khi người dùng dừng cuộn
    clearTimeout(scrollTimeout); // Xóa timeout cũ
    scrollTimeout = setTimeout(function () {
      if (scrollTop + windowHeight < footerOffsetTop) {
        backToTopBtn.classList.remove('hidden');
        backToTopBtn.classList.add('show'); // Khi dừng cuộn, hiện lại nút nếu không gần footer
      }
    }, 500); // Thời gian chờ để xác định ngừng cuộn (500ms)
  });

  // Xử lý sự kiện click vào nút "Back to top"
  backToTopBtn.addEventListener('click', function (e) {
    e.preventDefault();
    // Smooth scroll with easing effect
    smoothScrollToTop(500);
  });
}

/**
 * Smooth scroll to top with easing (easeInOutCubic)
 */
function smoothScrollToTop(duration) {
  const startPosition = window.scrollY;
  const startTime = performance.now();

  function easeInOutCubic(t) {
    return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
  }

  function animation(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const easeProgress = easeInOutCubic(progress);

    window.scrollTo(0, startPosition * (1 - easeProgress));

    if (progress < 1) {
      requestAnimationFrame(animation);
    }
  }

  requestAnimationFrame(animation);
}


/**
 * Menu fixed behavior
 */
function setupMenuFixedBehavior() {
  const navbar = document.querySelector('#header');
  if (!navbar) return;

  window.addEventListener('scroll', () => {
    const isMobile = window.innerWidth <= 768;
    const scrollThreshold = isMobile ? 90 : 106;

    if (window.scrollY > scrollThreshold) {
      navbar.classList.add('fixed');
    } else {
      navbar.classList.remove('fixed');
    }
  });
}

/**
 * Menu mobile handling
 */
function setupMobileMenuHandling() {
  let menuType = 'desktop';

  function handleResize() {
    const currMenuType = matchMedia('only screen and (max-width: 991px)').matches ? 'mobile' : 'desktop';

    if (currMenuType !== menuType) {
      menuType = currMenuType;
      if (currMenuType === 'mobile') {
        enableMobileMenu();
      } else {
        enableDesktopMenu();
      }
    }
  }

  // Run on load and resize
  handleResize();
  window.addEventListener('resize', handleResize);

  // Toggle mobile menu
  const btnMenu = document.querySelector('.btn-menu');
  btnMenu?.addEventListener('click', function () {
    const mainnavMobi = document.getElementById('mainnav-mobi');
    if (mainnavMobi) {
      slideToggle(mainnavMobi, 300);
    }
    this.classList.toggle('active');
  });

  // Handle submenu in mobile menu
  document.addEventListener('click', function (e) {
    if (e.target.matches('#mainnav-mobi li .btn-submenu')) {
      e.stopImmediatePropagation();
      const btnSubmenu = e.target;
      btnSubmenu.classList.toggle('active');
      const nextUl = btnSubmenu.nextElementSibling;
      if (nextUl && nextUl.tagName === 'UL') {
        slideToggle(nextUl, 300);
      }
    }
  });
}

/**
 * Slide toggle helper function (vanilla JS replacement for jQuery slideToggle)
 */
function slideToggle(element, duration = 300) {
  if (window.getComputedStyle(element).display === 'none') {
    slideDown(element, duration);
  } else {
    slideUp(element, duration);
  }
}

function slideDown(element, duration = 300) {
  element.style.display = 'block';
  element.style.overflow = 'hidden';
  const height = element.scrollHeight;
  element.style.height = '0px';
  element.offsetHeight; // Force reflow
  element.style.transition = `height ${duration}ms ease`;
  element.style.height = height + 'px';

  setTimeout(() => {
    element.style.height = '';
    element.style.overflow = '';
    element.style.transition = '';
  }, duration);
}

function slideUp(element, duration = 300) {
  element.style.overflow = 'hidden';
  element.style.height = element.scrollHeight + 'px';
  element.offsetHeight; // Force reflow
  element.style.transition = `height ${duration}ms ease`;
  element.style.height = '0px';

  setTimeout(() => {
    element.style.display = 'none';
    element.style.height = '';
    element.style.overflow = '';
    element.style.transition = '';
  }, duration);
}

/**
 * Enable mobile menu
 */
function enableMobileMenu() {
  const mainnav = document.getElementById('mainnav');
  if (!mainnav) return;

  mainnav.id = 'mainnav-mobi';
  mainnav.style.display = 'none';

  const headerContainer = document.querySelector('#header .container');
  if (headerContainer) {
    headerContainer.insertAdjacentElement('afterend', mainnav);
  }

  const hasChildMenu = mainnav.querySelectorAll('li');
  hasChildMenu.forEach(li => {
    const ul = li.querySelector(':scope > ul');
    if (ul) {
      ul.style.display = 'none';
      const link = li.querySelector(':scope > a');
      if (link && !li.querySelector('.btn-submenu')) {
        const btnSubmenu = document.createElement('span');
        btnSubmenu.className = 'btn-submenu';
        link.insertAdjacentElement('afterend', btnSubmenu);
      }
    }
  });

  const btnMenu = document.querySelector('.btn-menu');
  btnMenu?.classList.remove('active');
}

/**
 * Enable desktop menu
 */
function enableDesktopMenu() {
  const mainnavMobi = document.getElementById('mainnav-mobi');
  if (!mainnavMobi) return;

  mainnavMobi.id = 'mainnav';
  mainnavMobi.style.display = '';

  const submenus = mainnavMobi.querySelectorAll('.submenu');
  submenus.forEach(submenu => {
    submenu.style.display = '';
  });

  const navWrap = document.querySelector('#header .nav-wrap');
  if (navWrap) {
    navWrap.appendChild(mainnavMobi);
  }

  const btnSubmenus = mainnavMobi.querySelectorAll('.btn-submenu');
  btnSubmenus.forEach(btn => btn.remove());
}

/**
 * Setup submenu toggle handling (JS mới của bạn)
 */
function setupSubmenuToggleHandling() {
  // Note: DOMContentLoaded already fired, so we run this directly
  const submenuToggles = document.querySelectorAll('.submenu-toggle');

  submenuToggles.forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !isExpanded);
      this.classList.toggle('is-active');
      const submenu = this.nextElementSibling;
      if (submenu && submenu.tagName === 'UL') {
        submenu.classList.toggle('is-active');
      }
    });
  });
}

/**
 * Ẩn/hiện header khi scroll
 */
function setupHideHeaderOnScroll() {
  let lastScrollTop = 0;
  let header = document.getElementById('header');
  let scrollTimeout;

  window.addEventListener('scroll', function () {
    clearTimeout(scrollTimeout); // Clear timeout khi có sự kiện scroll xảy ra

    let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScrollTop > lastScrollTop) {
      // Khi scroll xuống, ẩn header
      header.classList.add('hidden');
    } else {
      // Khi scroll lên, hiện header
      header.classList.add('hidden');
    }

    lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // Ngăn việc giá trị scrollTop là âm

    // Chờ một khoảng thời gian sau khi cuộn để hiện lại header nếu người dùng ngừng cuộn
    scrollTimeout = setTimeout(() => {
      header.classList.remove('hidden');
    }, 500);
  });
}

function setupAjaxSendMail() {
  const COOKIE_KEY = 'contact_form_sent';

  function setCookie(name, value, hours) {
    const date = new Date();
    date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
    const expires = "; expires=" + date.toUTCString();
    document.cookie = name + "=" + (value || '') + expires + "; path=/";
  }

  function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

  const contactForm = document.getElementById('contactForm');
  if (!contactForm) return;

  // Khởi tạo trạng thái nút submit theo checkbox đồng ý
  const agreeCheckbox = contactForm.querySelector('#agree');
  const submitBtn = contactForm.querySelector('button[type="submit"]');

  function updateSubmitState() {
    if (submitBtn && agreeCheckbox) {
      submitBtn.disabled = !agreeCheckbox.checked;
    }
  }
  updateSubmitState();
  agreeCheckbox?.addEventListener('change', updateSubmitState);

  // Nếu đã gửi trong 24h, hiển thị thông báo ngay và khóa form submit
  if (getCookie(COOKIE_KEY)) {
    Swal.fire({
      icon: 'info',
      title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_title : 'Bạn đã gửi yêu cầu',
      text: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_text : 'Bạn đã gửi liên hệ trong 24 giờ qua. Vui lòng thử lại sau.',
      confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.ok : 'Đã hiểu'
    });
  }

  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();
    if (getCookie(COOKIE_KEY)) {
      Swal.fire({
        icon: 'info',
        title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_title : 'Bạn đã gửi yêu cầu',
        text: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_text : 'Bạn đã gửi liên hệ trong 24 giờ qua. Vui lòng thử lại sau.',
        confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.ok : 'Đã hiểu'
      });
      return;
    }

    const formData = new FormData(this);
    formData.append('action', 'send_contact_form');

    // Determine correct admin-ajax endpoint on frontend
    let endpoint = '/wp-admin/admin-ajax.php';
    if (typeof ajaxurl !== 'undefined' && ajaxurl) {
      endpoint = ajaxurl;
    } else if (typeof themeData !== 'undefined' && themeData.ajaxurl) {
      endpoint = themeData.ajaxurl;
    }

    const submitButton = this.querySelector('button[type="submit"]');
    if (submitButton) {
      submitButton.disabled = true;
      submitButton.classList.add('is-loading');
    }

    fetch(endpoint, {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(response => {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.success_title : 'Đã gửi thành công',
            text: response.data.message || ((typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.success_text_default : 'Cảm ơn bạn đã liên hệ.'),
            confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.close : 'Đóng'
          });
          setCookie(COOKIE_KEY, '1', 24);
          contactForm.reset();
        } else {
          Swal.fire({
            icon: 'error',
            title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.error_title : 'Không thể gửi',
            text: (response && response.data && response.data.message) ? response.data.message : ((typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.error_text : 'Đã xảy ra lỗi. Vui lòng thử lại.'),
            confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.close : 'Đóng'
          });
        }
      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.connection_error_title : 'Lỗi kết nối',
          text: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.connection_error_text : 'Đã xảy ra lỗi. Vui lòng thử lại.',
          confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.close : 'Đóng'
        });
      })
      .finally(() => {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.classList.remove('is-loading');
        }
      });
  });
}

function setupMenuMobile() {
  const mobileHeader = document.querySelector('.mobile-header');
  const toggleBtn    = document.querySelector('.mobile-header__menu-toggle');
  const menuContent  = document.querySelector('.mobile-menu');
  const closeBtn     = document.querySelector('.button-close');
  const menuItems    = document.querySelectorAll('.mobile-menu__menu li');
  const globalBtn    = menuContent?.querySelector('.mobile-menu__header .language .global');
  const dropdownItems = document.querySelectorAll('.nav_menu > li.nav__dropdown');
  const modal         = document.querySelector('.list-modal');
  const overlay       = document.querySelector('.list-modal__overlay');
  const listMenuToggleBtn = document.querySelector('.list-menu__toggle');

  const hideHeader = () => mobileHeader?.classList.add('hidden');
  const showHeader = () => mobileHeader?.classList.remove('hidden');

  toggleBtn?.addEventListener('click', (e) => {
    e.stopPropagation();

    // Mark position
    const scrollY = window.scrollY;
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollY}px`;
    document.body.style.width = '100%';

    // Active menu
    menuContent?.classList.add('active');
    document.documentElement.classList.add('no-scroll');
    document.body.classList.add('no-scroll');
    hideHeader();

    // Reset submenu
    menuItems.forEach((item) => item.classList.remove('open'));
  });

  // Hover nav-menu li dropdown
  dropdownItems.forEach((item) => {
    if (item.querySelector('.sub-menu')) {
      item.classList.add('has-submenu');
    }
  });

  // Toggle languge
  globalBtn?.addEventListener('click', (e) => {
    if (window.innerWidth < 769) {
      e.stopPropagation();
      globalBtn.classList.toggle('active');
    }
  });

  // Close mobile menu button
  closeBtn?.addEventListener('click', (e) => {
    e.stopPropagation();

    // Hide menu
    menuContent?.classList.remove('active');
    document.documentElement.classList.remove('no-scroll');
    document.body.classList.remove('no-scroll');
    showHeader();

    // Reset all submenu to close statement
    menuItems.forEach((item) => {
      if (item.classList.contains('open')) {
        const submenu = item.querySelector('.sub-menu');
        if (submenu) {
          submenu.style.maxHeight = '0';
          submenu.style.opacity = '0';
        }
        item.classList.remove('open');
      }
    });

    // Back to scroll position
    const scrollY = parseInt(document.body.style.top || '0') * -1;
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    requestAnimationFrame(() => {
      window.scrollTo({ top: scrollY, behavior: 'instant' });
    });
  });

  // Handle mobile-menu add sub-menu open
  menuItems.forEach((item) => {
    const submenu = item.querySelector('.sub-menu');
    const link = item.querySelector('a');

    if (!submenu || !link) return;

    item.classList.add('has-submenu');

    link.addEventListener('click', (e) => {
      e.preventDefault();

      const isOpen = item.classList.contains('open');

      menuItems.forEach((i) => {
        if (i !== item) {
          i.classList.remove('open');
          const sub = i.querySelector('.sub-menu');
          if (sub) {
            sub.style.maxHeight = 0;
            sub.style.opacity = 0;
          }
        }
      });

      item.classList.toggle('open', !isOpen);
      submenu.style.maxHeight = !isOpen ? submenu.scrollHeight + 'px' : 0;
      submenu.style.opacity = !isOpen ? '1' : '0';
    });
  });

  // Open list-content modal
  listMenuToggleBtn?.addEventListener('click', () => {
    const isActive = modal?.classList.contains('active');
    if (!isActive) {
      modal?.classList.add('active');
      listMenuToggleBtn?.classList.add('active');
      document.body.classList.add('no-scroll');
    } else {
      closeModal();
    }
  });

  overlay?.addEventListener('click', closeModal);

  function closeModal() {
    modal?.classList.add('closing');
    listMenuToggleBtn?.classList.remove('active');

    setTimeout(() => {
      modal?.classList.remove('active', 'closing');
      document.body.classList.remove('no-scroll');
    }, 400);
  }
}

/**
 * Setup Select2 - using vanilla JS with TomSelect or native multi-select fallback
 * Note: Select2 requires jQuery, so we'll use a simpler native approach
 * or you can replace with TomSelect library if needed
 */
function setupSelect2() {
  const selectElements = document.querySelectorAll('.js-example-basic-multiple');

  selectElements.forEach(select => {
    // Set placeholder via native attribute if not already set
    if (!select.querySelector('option[value=""]')) {
      const placeholderOption = document.createElement('option');
      placeholderOption.value = '';
      placeholderOption.textContent = 'Select a category';
      placeholderOption.disabled = true;
      placeholderOption.selected = true;
      select.insertBefore(placeholderOption, select.firstChild);
    }

    // Add custom styling class
    select.classList.add('custom-select');
  });
}

/**
 * Filer item by value select box
 */
function setupProjectFilter() {
  const selectBox = document.querySelector('.js-example-basic-multiple');
  if (!selectBox) return;

  const projects = document.querySelectorAll('.project-item');

  let noProjectNotice = document.querySelector('.no-project-notice');
  if (!noProjectNotice) {
    noProjectNotice = document.createElement('p');
    noProjectNotice.className = 'no-project-notice';
    noProjectNotice.textContent = 'No project found!!!';
    noProjectNotice.style.display = 'none';
    const container = selectBox.closest('.page-listing, .mm-container, body');
    if (container) {
      container.appendChild(noProjectNotice);
    }
  }

  function filterProjects() {
    const selectedValues = Array.from(selectBox.selectedOptions).map((opt) =>
      opt.value.trim()
    );

    let visibleCount = 0;

    projects.forEach((project) => {
      const dataValues =
        project
          .getAttribute("data-value")
          ?.split(/\s+/)
          .map((v) => v.trim()) || [];

      const match =
        selectedValues.length === 0 ||
        selectedValues.some((value) => dataValues.includes(value));

      if (match) {
        project.classList.remove("is-hidden");
        visibleCount++;
      } else {
        project.classList.add("is-hidden");
      }
    });

    updateProjectLayout();

    noProjectNotice.style.display = visibleCount === 0 ? "block" : "none";
  }

  function updateProjectLayout() {
    const visibleProjects = Array.from(
      document.querySelectorAll(".project-item:not(.is-hidden)")
    );

    visibleProjects.forEach((proj, index) => {
      proj.classList.remove("is-even", "is-odd");
      proj.classList.add(index % 2 === 0 ? "is-odd" : "is-even");
    });
  }

  selectBox.addEventListener('change', filterProjects);

  filterProjects();
}

function alertDropdownSubMenu() {
  const toggles = document.querySelectorAll('.submenu-toggle');

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      const parentLi = this.closest('.has_child_menu');
      const submenu = parentLi.querySelector('.nav__dropdown-menu');

      submenu.classList.toggle('is-active');
      this.classList.toggle('is-open');
    });
  });
}