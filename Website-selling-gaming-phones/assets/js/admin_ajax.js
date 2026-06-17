document.addEventListener('DOMContentLoaded', () => {
    initAdminFlash();
    initAdminProductAjax();
});

function initAdminFlash() {
    // Hiển thị SweetAlert2 nếu có flashData (dành cho Admin sau khi login hoặc redirect)
    if (window.flashData && window.flashData.message) {
        Swal.fire({
            title: window.flashData.type === 'success' ? 'Thành công!' : 'Lỗi!',
            text: window.flashData.message,
            icon: window.flashData.type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        window.flashData = null; // Xóa để không hiện lại
    }
}

function initAdminProductAjax() {
    // 1. Xử lý form Thêm / Sửa sản phẩm
    const productForm = document.querySelector('.admin-product-form');
    if (productForm) {
        productForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Ngăn load trang
            
            const submitBtn = productForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';

            try {
                const formData = new FormData(productForm);
                const url = productForm.getAttribute('action');

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    Swal.fire({
                        title: data.success ? 'Thành công!' : 'Thất bại!',
                        text: data.message,
                        icon: data.success ? 'success' : 'error',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    if (data.success) {
                        // Tải lại bảng HTML mà không load lại toàn bộ trang
                        await reloadProductTable();
                        
                        // Đóng modal và xóa param edit nếu đang mở
                        const modalOverlay = document.getElementById('productModalOverlay');
                        if (modalOverlay) {
                            modalOverlay.classList.remove('active', 'active-edit-mode');
                            document.body.style.overflow = '';
                        }
                        
                        const url = new URL(window.location);
                        if (url.searchParams.has('edit')) {
                            url.searchParams.delete('edit');
                            window.history.replaceState({}, '', url);
                        }
                        
                        // Nếu là form thêm mới thì reset form
                        if (!productForm.querySelector('input[name="id"]').value || productForm.querySelector('input[name="id"]').value == 0) {
                            productForm.reset();
                            // Bỏ ảnh preview nếu có
                            const imgPreview = productForm.querySelector('.bg-light img');
                            if (imgPreview) imgPreview.parentElement.remove();
                        }
                    }
                } else {
                    Swal.fire('Lỗi server', 'Không thể kết nối đến máy chủ.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Lỗi!', 'Có lỗi xảy ra trong quá trình xử lý.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }

    // 2. Xử lý xóa sản phẩm (vì các nút xóa nằm trong bảng có thể được load lại, dùng event delegation)
    const tableWrap = document.querySelector('.admin-table-wrap');
    if (tableWrap) {
        tableWrap.addEventListener('submit', async (e) => {
            // Tìm xem event có đến từ form xóa sản phẩm không
            if (e.target.closest('form[action*="admin_product_delete"]')) {
                e.preventDefault();
                const form = e.target;
                
                // Vì form hiện tại có onsubmit="return confirm(...)", ta cần xóa cái đó để SweetAlert chạy
                // Hoặc nó đã bị preventDefault nên ta có thể dùng SweetAlert
                const result = await Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Sản phẩm này sẽ bị xóa vĩnh viễn!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Hủy'
                });

                if (result.isConfirmed) {
                    try {
                        const formData = new FormData(form);
                        const url = form.getAttribute('action');
                        
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            Swal.fire({
                                title: data.success ? 'Đã xóa!' : 'Lỗi!',
                                text: data.message,
                                icon: data.success ? 'success' : 'error',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            if (data.success) {
                                // Xóa row khỏi DOM bằng animation
                                const row = form.closest('tr');
                                row.style.transition = 'all 0.5s';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => row.remove(), 500);
                            }
                        } else {
                            Swal.fire('Lỗi server', 'Không thể kết nối đến máy chủ.', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra trong quá trình xóa.', 'error');
                    }
                }
            }
        });
    }
}

// Tải lại danh sách sản phẩm thông qua fetch URL hiện tại
async function reloadProductTable() {
    try {
        const response = await fetch(window.location.href);
        const html = await response.text();
        
        // Tạo một DOM tạm để bóc tách cái bảng
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        const newTable = doc.querySelector('.admin-table-wrap');
        const currentTable = document.querySelector('.admin-table-wrap');
        
        if (newTable && currentTable) {
            currentTable.innerHTML = newTable.innerHTML;
        }
    } catch (error) {
        console.error('Không thể load lại bảng:', error);
    }
}
