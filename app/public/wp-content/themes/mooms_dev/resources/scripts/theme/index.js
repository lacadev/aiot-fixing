import "@images/favicon.ico";
import "@styles/theme";
import "airbnb-browser-shims";
import "./pages/*.js";
import 'popper.js';
// import Swup from 'swup';
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import AOS from "aos/dist/aos";
import 'jquery.easing';
import Swal from 'sweetalert2';
import $ from "jquery";
import "select2";

jQuery(document).ready(function () {
  // const swup = new Swup();
  initializePageFeatures();

  // swup.hooks.on('content:replace', () => {
  //   initializePageFeatures();
  // });

});

function initializePageFeatures() {
  initAnimations();
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
 * Back to top button with hide on scroll and footer detection
 */
function setupBackToTopButton() {
  const backToTopBtn = $('#back-to-top');
  const footerOffsetTop = $('#footer').offset().top;
  const windowHeight = $(window).height();
  let scrollTimeout;
  const pageHeight = $(document).height();
  const scrollThreshold = pageHeight * 0.1; // 10% chiều cao trang

  // Sự kiện scroll để xử lý nút "Back to top"
  $(window).on('scroll', function () {
    const scrollTop = $(this).scrollTop();

    // Kiểm tra nếu người dùng đã scroll quá 10% chiều cao trang để hiện nút "Back to top"
    if (scrollTop > scrollThreshold) {
      backToTopBtn.addClass('fixed'); // Thêm class fixed
    } else {
      backToTopBtn.removeClass('fixed show hidden'); // Xóa tất cả các class nếu cuộn lên trên ngưỡng
    }

    // Ẩn nút "Back to top" khi scroll (cả cuộn lên và cuộn xuống đều ẩn)
    backToTopBtn.addClass('hidden').removeClass('show'); // Ẩn nút khi có cuộn

    // Kiểm tra nếu gần tới footer thì xóa cả `fixed` và `hidden`
    if (scrollTop + windowHeight >= footerOffsetTop) {
      backToTopBtn.removeClass('fixed hidden').addClass('show'); // Xóa `fixed`, hiện lại nút khi gần footer
    }

    // Xác định khi người dùng dừng cuộn
    clearTimeout(scrollTimeout); // Xóa timeout cũ
    scrollTimeout = setTimeout(function () {
      if (scrollTop + windowHeight < footerOffsetTop) {
        backToTopBtn.removeClass('hidden').addClass('show'); // Khi dừng cuộn, hiện lại nút nếu không gần footer
      }
    }, 500); // Thời gian chờ để xác định ngừng cuộn (200ms)
  });

  // Xử lý sự kiện click vào nút "Back to top"
  backToTopBtn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, 500, 'easeInOutCubic');
  });
}


/**
 * Menu fixed behavior
 */
function setupMenuFixedBehavior() {
  const navbar = document.querySelector('#header');

  $(window).on('scroll', () => {
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

  $(window).on('load resize', function () {
    const currMenuType = matchMedia('only screen and (max-width: 991px)').matches ? 'mobile' : 'desktop';

    if (currMenuType !== menuType) {
      menuType = currMenuType;
      if (currMenuType === 'mobile') {
        enableMobileMenu();
      } else {
        enableDesktopMenu();
      }
    }
  });

  // Toggle mobile menu
  $('.btn-menu').on('click', function () {
    $('#mainnav-mobi').slideToggle(300);
    $(this).toggleClass('active');
  });

  // Handle submenu in mobile menu
  $(document).on('click', '#mainnav-mobi li .btn-submenu', function (e) {
    $(this).toggleClass('active').next('ul').slideToggle(300);
    e.stopImmediatePropagation();
  });
}

/**
 * Enable mobile menu
 */
function enableMobileMenu() {
  const $mobileMenu = $('#mainnav').attr('id', 'mainnav-mobi').hide();
  const hasChildMenu = $('#mainnav-mobi').find('li:has(ul)');

  $('#header .container').after($mobileMenu);
  hasChildMenu.children('ul').hide();
  hasChildMenu.children('a').after('<span class="btn-submenu"></span>');
  $('.btn-menu').removeClass('active');
}

/**
 * Enable desktop menu
 */
function enableDesktopMenu() {
  const $desktopMenu = $('#mainnav-mobi').attr('id', 'mainnav').removeAttr('style');

  $desktopMenu.find('.submenu').removeAttr('style');
  $('#header').find('.nav-wrap').append($desktopMenu);
  $('.btn-submenu').remove();
}

// document.addEventListener('DOMContentLoaded', function () {
//   const submenuToggles = document.querySelectorAll('.submenu-toggle');

//   submenuToggles.forEach(function (toggle) {
//     toggle.addEventListener('click', function () {
//       const isExpanded = this.getAttribute('aria-expanded') === 'true';
//       this.setAttribute('aria-expanded', !isExpanded);
//       this.classList.toggle('is-active');
//       const submenu = this.nextElementSibling;
//       if (submenu && submenu.tagName === 'UL') {
//         submenu.classList.toggle('is-active');
//       }
//     });
//   });
// });

/**
 * Setup submenu toggle handling (JS mới của bạn)
 */
function setupSubmenuToggleHandling() {
  document.addEventListener('DOMContentLoaded', function () {
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

  // Khởi tạo trạng thái nút submit theo checkbox đồng ý
  if ($('#contactForm').length) {
    const $form = $('#contactForm');
    const $agree = $form.find('#agree');
    const $submit = $form.find('button[type="submit"]');
    function updateSubmitState() {
      $submit.prop('disabled', !$agree.is(':checked'));
    }
    updateSubmitState();
    $agree.on('change', updateSubmitState);
  }

  // Nếu đã gửi trong 24h, hiển thị thông báo ngay và khóa form submit
  if (getCookie(COOKIE_KEY)) {
    $(document).ready(function () {
      if ($('#contactForm').length) {
        Swal.fire({
          icon: 'info',
          title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_title : 'Bạn đã gửi yêu cầu',
          text: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_text : 'Bạn đã gửi liên hệ trong 24 giờ qua. Vui lòng thử lại sau.',
          confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.ok : 'Đã hiểu'
        });
      }
    });
  }

  $('#contactForm').on('submit', function (e) {
    e.preventDefault();
    if (getCookie(COOKIE_KEY)) {
      Swal.fire({
        icon: 'info',
        title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_title : 'Bạn đã gửi yêu cầu',
        text: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.already_sent_text : 'Bạn đã gửi liên hệ trong 24 giờ qua. Vui lòng thử lại sau.',
        confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.ok : 'Đã hiểu'
      });
    }
    var formData = $(this).serialize();
    // Determine correct admin-ajax endpoint on frontend
    var endpoint = (typeof ajaxurl !== 'undefined' && ajaxurl)
      ? ajaxurl
      : (typeof themeData !== 'undefined' && themeData.ajaxurl)
        ? themeData.ajaxurl
        : '/wp-admin/admin-ajax.php';
    const $submitBtn = $(this).find('button[type="submit"]');
    $submitBtn.prop('disabled', true).addClass('is-loading');
    $.ajax({
      url: endpoint, // Prefer localized themeData.ajaxurl, fallback to global ajaxurl
      type: 'POST',
      dataType: 'json',
      data: formData + '&action=send_contact_form',
      success: function (response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.success_title : 'Đã gửi thành công',
            text: response.data.message || ((typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.success_text_default : 'Cảm ơn bạn đã liên hệ.'),
            confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.close : 'Đóng'
          });
          setCookie(COOKIE_KEY, '1', 24);
          $('#contactForm')[0].reset();
        } else {
          Swal.fire({
            icon: 'error',
            title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.error_title : 'Không thể gửi',
            text: (response && response.data && response.data.message) ? response.data.message : ((typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.error_text : 'Đã xảy ra lỗi. Vui lòng thử lại.'),
            confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.close : 'Đóng'
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.connection_error_title : 'Lỗi kết nối',
          text: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.connection_error_text : 'Đã xảy ra lỗi. Vui lòng thử lại.',
          confirmButtonText: (typeof themeData !== 'undefined' && themeData.i18n) ? themeData.i18n.close : 'Đóng'
        });
      },
      complete: function () {
        $submitBtn.prop('disabled', false).removeClass('is-loading');
      }
    });
  });
}

function setupMenuMobile() {
  const mobileHeader = document.querySelector(".mobile-header");
  const toggleBtn    = document.querySelector(".mobile-header__menu-toggle");
  const menuContent  = document.querySelector(".mobile-menu");
  const closeBtn     = document.querySelector(".button-close");
  const menuItems    = document.querySelectorAll(".mobile-menu__menu li");
  const globalBtn    = menuContent?.querySelector(".mobile-menu__header .language .global");
  const dropdownItems = document.querySelectorAll(".nav_menu > li.nav__dropdown");
  const listMenuToggleBtn = document.querySelector(".list-menu__toggle");
  const modal = document.querySelector(".list-modal");
  const overlay = document.querySelector('.list-modal__overlay');

  const hideHeader = () => mobileHeader?.classList.add("hidden");
  const showHeader = () => mobileHeader?.classList.remove("hidden");

  toggleBtn?.addEventListener("click", (e) => {
    e.stopPropagation();

    // Mark position
    const scrollY = window.scrollY;
    document.body.style.position = "fixed";
    document.body.style.top = `-${scrollY}px`;
    document.body.style.width = "100%";

    // Active menu
    menuContent?.classList.add("active");
    document.documentElement.classList.add("no-scroll");
    document.body.classList.add("no-scroll");
    hideHeader();

    // Reset submenu
    menuItems.forEach((item) => item.classList.remove("open"));
  });

  closeBtn?.addEventListener("click", (e) => {
    e.stopPropagation();

    // Hide menu
    menuContent?.classList.remove("active");
    document.documentElement.classList.remove("no-scroll");
    document.body.classList.remove("no-scroll");
    showHeader();

    // Back to postion before 
    const scrollY = parseInt(document.body.style.top || "0") * -1;
    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.width = "";
    requestAnimationFrame(() => {
      window.scrollTo({ top: scrollY, behavior: "instant" });
    });
  });

  // Tonggle languge
  globalBtn?.addEventListener("click", (e) => {
    if (window.innerWidth < 769) {
      e.stopPropagation();
      globalBtn.classList.toggle("active");
    }
  });

  // Handle sub-menu open
  menuItems.forEach((item) => {
    const submenu = item.querySelector(".sub-menu");

    if (submenu) {
      item.classList.add("has-submenu");

      const link = item.querySelector("a");
      link.addEventListener("click", (e) => {
        e.preventDefault();
        item.classList.toggle("open");
      });
    }
  });


  dropdownItems.forEach((item) => {
    if (item.querySelector(".sub-menu")) {
      item.classList.add("has-submenu");
    }
  });
}
/**
 * Using library select2
 */
function setupSelect2() {
  $('.js-example-basic-multiple').select2();
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
    selectBox
      .closest('.page-listing, .mm-container, body')
      .appendChild(noProjectNotice);
  }

  function filterProjects() {
    const selectedValues = Array.from(selectBox.selectedOptions).map((opt) =>
      opt.value.trim()
    );

    let visibleCount = 0;

    projects.forEach((project) => {
      const dataValues =
        project
          .getAttribute('data-value')
          ?.split(/\s+/)
          .map((v) => v.trim()) || [];

      const match =
        selectedValues.length === 0 ||
        selectedValues.some((value) => dataValues.includes(value));

      if (match) {
        project.classList.remove('is-hidden');
        visibleCount++;
      } else {
        project.classList.add('is-hidden');
      }
    });

    if (visibleCount === 0) {
      noProjectNotice.style.display = 'block';
    } else {
      noProjectNotice.style.display = 'none';
    }
  }

  if (window.jQuery && jQuery.fn.select2) {
    jQuery(selectBox).on('change.select2', filterProjects);
  } else {
    selectBox.addEventListener('change', filterProjects);
  }

  filterProjects();

  // Hide header when scroll and display when stop
  let scrollTimer;
  window.addEventListener("scroll", () => {
    hideHeader();

    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(() => {
      showHeader();
    }, 400);
  });

  // Open list menu

  function openModal() {
    modal.classList.add('active');
    listMenuToggleBtn.classList.add('active');
  }

  function closeModal() {
    modal.classList.add('closing');
    listMenuToggleBtn.classList.remove('active');

    setTimeout(() => {
      modal.classList.remove('active', 'closing');
    }, 400);
  }

  listMenuToggleBtn?.addEventListener('click', () => {
    if (!modal.classList.contains('active')) {
      openModal();
    } else {
      closeModal();
    }
  });

  overlay?.addEventListener('click', () => {
    if (modal.classList.contains('active')) {
      closeModal();
    }
  });

}

function alertDropdownSubMenu() {
  const toggles = document.querySelectorAll(".submenu-toggle");

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      const parentLi = this.closest(".has_child_menu");
      const submenu = parentLi.querySelector(".nav__dropdown-menu");

      submenu.classList.toggle("is-active");
      this.classList.toggle("is-open");
    });
  });
}