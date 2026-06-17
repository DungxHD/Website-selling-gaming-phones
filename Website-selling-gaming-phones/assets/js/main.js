document.addEventListener('DOMContentLoaded', () => {
    initPageTransitions();
    initTheme();
    initSlider();
    initFilterPanel();
    initRevealOnScroll();
    initFlashAutoHide();
    initJQueryEffects();
    initAjaxCart();
});

function initPageTransitions() {
    // Thêm class vào main hoặc body để thực hiện hiệu ứng xuất hiện ban đầu
    const mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.classList.add('page-transition-enter');
    }

    // Tự động thêm hiệu ứng xuất hiện tuần tự cho các form đăng nhập, đăng ký
    const authElements = document.querySelectorAll('.auth-form > label, .auth-form > button, .admin-login-form > label, .admin-login-form > button');
    authElements.forEach((el, index) => {
        el.classList.add('stagger-item');
        el.style.animationDelay = `${index * 80 + 100}ms`;
    });

    // Bắt sự kiện click vào các link nội bộ để thực hiện hiệu ứng biến mất (leave)
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        const target = link.getAttribute('target');

        // Bỏ qua các link mở tab mới, link neo (#), hoặc không trỏ đi đâu
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || target === '_blank') {
            return;
        }

        // Bỏ qua nếu link chứa download, hoặc đang giữ phím mod (Ctrl, Cmd, Shift)
        if (link.hasAttribute('download') || e.ctrlKey || e.metaKey || e.shiftKey) {
            return;
        }

        // Kích hoạt hiệu ứng thoát
        e.preventDefault();
        
        if (mainContent) {
            mainContent.classList.remove('page-transition-enter');
            mainContent.classList.add('page-transition-leave');
        } else {
            document.body.classList.add('page-transition-leave');
        }

        // Chờ animation hoàn thành rồi chuyển trang
        setTimeout(() => {
            window.location.href = href;
        }, 400); // 400ms tương ứng với thời gian leave animation
    });
}

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
    // If flashData is defined from the backend
    if (window.flashData && window.flashData.message) {
        showToast(window.flashData.type, window.flashData.message);
        // Clear it so it doesn't show again if page isn't reloaded
        window.flashData = null;
    }
}

function showToast(type, message) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    // Giới hạn tối đa 2 thông báo trên màn hình
    const existingToasts = container.querySelectorAll('.toast');
    if (existingToasts.length >= 2) {
        // Xóa ngay lập tức thông báo cũ nhất (cái đầu tiên)
        existingToasts[0].remove();
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
    
    toast.innerHTML = `
        <i class="fa-solid ${iconClass}"></i>
        <div style="flex: 1">${message}</div>
        <button class="toast-close" onclick="this.parentElement.classList.remove('show'); this.parentElement.classList.add('hide'); setTimeout(() => this.parentElement.remove(), 500);"><i class="fa-solid fa-xmark"></i></button>
    `;
    
    container.appendChild(toast);
    
    // Thêm class show để thực hiện animation xuất hiện
    toast.classList.add('show');
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        if(toast.parentElement) {
            toast.classList.remove('show');
            toast.classList.add('hide'); // Thêm class hide để thực hiện animation biến mất
            
            // Chờ animation hide kết thúc rồi xóa khỏi DOM (500ms theo CSS)
            setTimeout(() => {
                if(toast.parentElement) toast.remove();
            }, 500); 
        }
    }, 3000);
}

function initAjaxCart() {
    const cartForms = document.querySelectorAll('form[action*="cart_add"]');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Ngăn load lại trang
            
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            try {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: params,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Backend nhận diện AJAX
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    showToast(data.success ? 'success' : 'error', data.message);
                    
                    if (data.cartCount !== undefined) {
                        const countBadge = document.querySelector('.cart-count');
                        if (countBadge) {
                            countBadge.textContent = data.cartCount;
                            // Add a little pop effect to the badge
                            countBadge.style.transition = 'transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1)';
                            countBadge.style.transform = 'scale(1.4)';
                            setTimeout(() => countBadge.style.transform = '', 200);
                        }
                    }
                } else {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            } catch (error) {
                console.error('Lỗi khi thêm giỏ hàng:', error);
                showToast('error', 'Không thể kết nối đến máy chủ!');
            } finally {
                if (btn) btn.disabled = false;
            }
        });
    });
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
