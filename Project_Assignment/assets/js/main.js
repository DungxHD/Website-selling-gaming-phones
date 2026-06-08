// Main JavaScript for Gaming Phone Store

// Theme Toggle
function initThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            showNotification(newTheme === 'dark' ? 'Đã bật chế độ tối' : 'Đã bật chế độ sáng', 'success');
        });
    }
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Cart Management
class CartManager {
    constructor() {
        this.updateCartCount();
    }
    
    async addToCart(productId, quantity = 1) {
        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            const response = await fetch('index.php?controller=home&action=addToCart', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount();
                showNotification(result.message, 'success');
            } else {
                showNotification(result.message, 'error');
            }
            
            return result;
        } catch (error) {
            console.error('Error adding to cart:', error);
            showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
        }
    }
    
    async updateCartItem(productId, quantity) {
        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            const response = await fetch('index.php?controller=home&action=updateCart', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                showNotification(result.message, 'error');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            showNotification('Có lỗi xảy ra khi cập nhật giỏ hàng', 'error');
        }
    }
    
    async removeFromCart(productId) {
        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            
            const response = await fetch('index.php?controller=home&action=removeFromCart', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                showNotification(result.message, 'error');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
        }
    }
    
    updateCartCount() {
        fetch('index.php?action=getCartCount')
            .then(response => response.json())
            .then(data => {
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.textContent = data.count || 0;
                    if (data.count > 0) {
                        el.style.display = 'flex';
                    } else {
                        el.style.display = 'none';
                    }
                });
            })
            .catch(error => console.error('Error getting cart count:', error));
    }
}

// Modal Management
class ModalManager {
    constructor() {
        this.modal = document.getElementById('productModal');
        this.modalContent = document.getElementById('modalContent');
        
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.closeModal();
                }
            });
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                    this.closeModal();
                }
            });
        }
    }
    
    async openProductModal(productId) {
        try {
            const response = await fetch(`index.php?action=getProductDetail&id=${productId}`);
            const product = await response.json();
            
            if (product) {
                this.modalContent.innerHTML = this.renderProductDetail(product);
                this.modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Initialize buttons in modal
                this.initializeModalButtons();
            }
        } catch (error) {
            console.error('Error loading product detail:', error);
            showNotification('Không thể tải chi tiết sản phẩm', 'error');
        }
    }
    
    closeModal() {
        if (this.modal) {
            this.modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    renderProductDetail(product) {
        return `
            <button class="modal-close" onclick="modalManager.closeModal()">×</button>
            <div class="product-detail-container">
                <div class="detail-image">
                    <img src="assets/images/${product.image}" alt="${product.name}">
                </div>
                <div class="detail-info">
                    <h1>${product.name}</h1>
                    <div class="detail-price">${this.formatPrice(product.price)}</div>
                    
                    <div class="detail-specs">
                        <h3>Thông số kỹ thuật</h3>
                        <div class="spec-grid">
                            <div class="spec-box">
                                <i class="fas fa-microchip"></i>
                                <div>
                                    <span>CPU</span>
                                    <span>${product.cpu}</span>
                                </div>
                            </div>
                            <div class="spec-box">
                                <i class="fas fa-desktop"></i>
                                <div>
                                    <span>Màn hình</span>
                                    <span>${product.screen}</span>
                                </div>
                            </div>
                            <div class="spec-box">
                                <i class="fas fa-battery-full"></i>
                                <div>
                                    <span>Pin</span>
                                    <span>${product.battery}</span>
                                </div>
                            </div>
                            <div class="spec-box">
                                <i class="fas fa-bolt"></i>
                                <div>
                                    <span>Sạc</span>
                                    <span>${product.charging_power}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-description">
                        <h3>Mô tả sản phẩm</h3>
                        <p>${product.description}</p>
                    </div>
                    
                    <div class="detail-actions">
                        <div class="quantity-selector">
                            <button onclick="decreaseQuantity()">-</button>
                            <input type="number" id="modalQuantity" value="1" min="1" max="${product.stock}">
                            <button onclick="increaseQuantity(${product.stock})">+</button>
                        </div>
                        <button class="btn btn-add-cart" onclick="cartManager.addToCart(${product.id}, document.getElementById('modalQuantity').value)">
                            <i class="fas fa-shopping-cart"></i> Thêm giỏ hàng
                        </button>
                        <button class="btn btn-buy-now" onclick="buyNow(${product.id})">
                            <i class="fas fa-bolt"></i> Mua ngay
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    initializeModalButtons() {
        // Additional initialization if needed
    }
    
    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    }
}

// Quantity controls
function increaseQuantity(max) {
    const input = document.getElementById('modalQuantity');
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('modalQuantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Buy now function
function buyNow(productId) {
    cartManager.addToCart(productId, document.getElementById('modalQuantity').value).then(() => {
        window.location.href = 'index.php?controller=home&action=cart';
    });
}

// Auth functions
async function handleLogin(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch('index.php?controller=user&action=login', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Đăng nhập thành công!', 'success');
            setTimeout(() => {
                window.location.href = result.redirect || 'index.php';
            }, 1000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showNotification('Có lỗi xảy ra khi đăng nhập', 'error');
    }
}

async function handleRegister(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch('index.php?controller=user&action=register', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                switchAuthTab('login');
            }, 1500);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Register error:', error);
        showNotification('Có lỗi xảy ra khi đăng ký', 'error');
    }
}

function switchAuthTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const loginTab = document.querySelector('[data-tab="login"]');
    const registerTab = document.querySelector('[data-tab="register"]');
    
    if (tab === 'login') {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
    } else {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        loginTab.classList.remove('active');
        registerTab.classList.add('active');
    }
}

// Search and filter
function handleSearch(event) {
    event.preventDefault();
    const form = event.target;
    form.submit();
}

function applyFilters() {
    const form = document.getElementById('filterForm');
    if (form) {
        form.submit();
    }
}

// Sort products
function sortProducts(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

// Initialize on DOM ready
let cartManager;
let modalManager;

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    cartManager = new CartManager();
    modalManager = new ModalManager();
    
    // Initialize product card clicks
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                const productId = card.dataset.productId;
                if (productId) {
                    modalManager.openProductModal(productId);
                }
            }
        });
    });
    
    // Initialize auth tabs
    document.querySelectorAll('.auth-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            switchAuthTab(tab.dataset.tab);
        });
    });
});

// Admin functions
async function deleteProduct(productId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('id', productId);
        
        const response = await fetch('index.php?controller=admin&action=deleteProduct', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
    }
}

async function toggleUserStatus(userId) {
    try {
        const formData = new FormData();
        formData.append('id', userId);
        
        const response = await fetch('index.php?controller=admin&action=toggleUserStatus', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Toggle status error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    }
}

// Format price helper
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
}
