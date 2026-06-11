document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initSlider();
    initFilterPanel();
    initRevealOnScroll();
    initFlashAutoHide();
    initJQueryEffects();
});

function initTheme() {
    const toggle = document.getElementById('theme-toggle');
    const saved = localStorage.getItem('gaming-phone-theme');

    if (saved === 'dark') {
        document.body.classList.add('dark-mode');
    }

    if (!toggle) {
        return;
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('gaming-phone-theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
}

function initSlider() {
    const slider = document.querySelector('[data-slider]');
    if (!slider) {
        return;
    }

    const slides = Array.from(slider.querySelectorAll('.deal-slide'));
    const prev = document.querySelector('[data-slider-prev]');
    const next = document.querySelector('[data-slider-next]');
    let active = slides.findIndex((slide) => slide.classList.contains('active'));
    active = active < 0 ? 0 : active;
    let autoPlayId = null;

    const show = (index) => {
        if (!slides.length) {
            return;
        }

        slides[active].classList.remove('active');
        active = (index + slides.length) % slides.length;
        slides[active].classList.add('active');
    };

    const startAutoPlay = () => {
        if (slides.length <= 1) {
            return;
        }

        clearInterval(autoPlayId);
        autoPlayId = window.setInterval(() => show(active + 1), 5200);
    };

    prev?.addEventListener('click', () => {
        show(active - 1);
        startAutoPlay();
    });

    next?.addEventListener('click', () => {
        show(active + 1);
        startAutoPlay();
    });

    slider.addEventListener('mouseenter', () => clearInterval(autoPlayId));
    slider.addEventListener('mouseleave', startAutoPlay);
    startAutoPlay();
}

function initFilterPanel() {
    const toggle = document.querySelector('[data-filter-toggle]');
    const panel = document.querySelector('[data-filter-panel]');

    if (!toggle || !panel) {
        return;
    }

    toggle.setAttribute('aria-expanded', panel.classList.contains('open') ? 'true' : 'false');

    // Nếu đã có jQuery thì để phần hiệu ứng slide cho jQuery xử lý để tránh bị click 1 lần mà toggle 2 lần.
    if (window.jQuery) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isOpen = panel.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
}

function initRevealOnScroll() {
    const items = document.querySelectorAll('.reveal-on-scroll');
    if (!items.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.14,
        rootMargin: '0px 0px -30px 0px',
    });

    items.forEach((item, index) => {
        item.style.transitionDelay = `${Math.min(index * 70, 240)}ms`;
        observer.observe(item);
    });
}

function initFlashAutoHide() {
    const flash = document.querySelector('.flash');
    if (!flash) {
        return;
    }

    window.setTimeout(() => {
        flash.classList.add('is-hiding');
        window.setTimeout(() => flash.remove(), 420);
    }, 2600);
}

function initJQueryEffects() {
    if (!window.jQuery) {
        return;
    }

    const $window = $(window);
    const $header = $('.site-header');
    const $filterPanel = $('[data-filter-panel]');
    const $filterToggle = $('[data-filter-toggle]');

    $header.toggleClass('is-scrolled', $window.scrollTop() > 12);
    $filterToggle.attr('aria-expanded', $filterPanel.hasClass('open') ? 'true' : 'false');

    $window.on('scroll', () => {
        $header.toggleClass('is-scrolled', $window.scrollTop() > 12);
    });

    $('[data-filter-toggle]').on('click', function () {
        if (!$filterPanel.length) {
            return;
        }

        const willOpen = !$filterPanel.hasClass('open');
        $filterPanel.stop(true, true).slideToggle(220).toggleClass('open', willOpen);
        $(this).attr('aria-expanded', willOpen ? 'true' : 'false');
    });

    $('.product-card, .hero-product, .admin-card, .checkout-summary, .checkout-form-card').on('mousemove', function (event) {
        const rect = this.getBoundingClientRect();
        const rotateY = ((event.clientX - rect.left) / rect.width - 0.5) * 5;
        const rotateX = ((event.clientY - rect.top) / rect.height - 0.5) * -5;
        this.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
    }).on('mouseleave', function () {
        this.style.transform = '';
    });
}
