/* ==========================================================================
   IT MOBILE - JAVASCRIPT INTERACTION
   Quản lý các tương tác UI, Mobile Navigation, Confirm Dialogs & Quantity Pickers
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Mobile Navigation Toggle & Accessibility
    const mobileToggleBtn = document.querySelector('.js-mobile-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (mobileToggleBtn && mainNav) {
        mobileToggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isExpanded = mobileToggleBtn.getAttribute('aria-expanded') === 'true';
            mobileToggleBtn.setAttribute('aria-expanded', !isExpanded);
            mainNav.classList.toggle('is-active');
        });

        // Đóng mobile nav khi bấm phím Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && mainNav.classList.contains('is-active')) {
                mainNav.classList.remove('is-active');
                mobileToggleBtn.setAttribute('aria-expanded', 'false');
                mobileToggleBtn.focus();
            }
        });

        // Đóng mobile nav khi click ra ngoài
        document.addEventListener('click', function (e) {
            if (mainNav.classList.contains('is-active') && !mainNav.contains(e.target) && !mobileToggleBtn.contains(e.target)) {
                mainNav.classList.remove('is-active');
                mobileToggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // 2. Admin Sidebar Toggle trên Mobile
    const adminToggleBtn = document.querySelector('.js-admin-toggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    if (adminToggleBtn && adminSidebar) {
        adminToggleBtn.addEventListener('click', function () {
            adminSidebar.classList.toggle('is-active');
        });
    }

    // 3. Xác nhận trước khi xóa dữ liệu
    const deleteButtons = document.querySelectorAll('.js-confirm-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm-message') || 'Bạn có chắc chắn muốn xóa mục này? Thao tác không thể hoàn tác.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // 4. Xác nhận khi Khôi phục dữ liệu mô phỏng
    const resetForm = document.querySelector('.js-confirm-reset');
    if (resetForm) {
        resetForm.addEventListener('submit', function (e) {
            if (!confirm('CẢNH BÁO: Hành động này sẽ đặt lại toàn bộ dữ liệu sản phẩm, bài viết và đơn hàng về trạng thái mẫu ban đầu!\n\nBạn có muốn tiếp tục?')) {
                e.preventDefault();
            }
        });
    }

    // 5. Điều khiển Tăng/Giảm số lượng sản phẩm & Tự động tính toán Giá tiền
    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
    }

    let syncTimer = null;
    function syncCartItemAjax(itemKey, qty) {
        if (!itemKey) return;
        clearTimeout(syncTimer);
        syncTimer = setTimeout(() => {
            const formData = new FormData();
            formData.append('action', 'update_ajax');
            formData.append('item_key', itemKey);
            formData.append('quantity', qty);

            const baseUrl = window.BASE_URL || '';
            fetch(baseUrl + '/cart.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.cart_totals) {
                    const headerCartBadge = document.querySelector('.cart-count');
                    if (headerCartBadge) {
                        headerCartBadge.textContent = data.cart_totals.total_count;
                    }
                }
            })
            .catch(err => console.error('Lỗi cập nhật giỏ hàng:', err));
        }, 250);
    }

    function recalculateCartTotals() {
        const cartRows = document.querySelectorAll('.cart-item-row');
        if (!cartRows.length) return;

        let globalTotalAmount = 0;
        let globalTotalCount = 0;

        cartRows.forEach(row => {
            const unitPrice = parseFloat(row.getAttribute('data-unit-price')) || 0;
            const input = row.querySelector('.js-cart-qty-input');
            if (!input) return;

            let qty = parseInt(input.value) || 1;
            let min = parseInt(input.getAttribute('min')) || 1;
            let max = parseInt(input.getAttribute('max')) || 99;

            if (qty < min) qty = min;
            if (qty > max) qty = max;
            input.value = qty;

            let subtotal = unitPrice * qty;

            const subtotalEl = row.querySelector('.js-item-subtotal');
            if (subtotalEl) {
                subtotalEl.textContent = formatVND(subtotal);
            }

            globalTotalAmount += subtotal;
            globalTotalCount += qty;

            const itemKey = row.getAttribute('data-item-key');
            if (itemKey) {
                syncCartItemAjax(itemKey, qty);
            }
        });

        const totalCountEl = document.querySelector('.js-cart-total-count');
        const totalAmountEl = document.querySelector('.js-cart-total-amount');
        const headerCartBadge = document.querySelector('.cart-count');

        if (totalCountEl) {
            totalCountEl.textContent = globalTotalCount + ' thiết bị';
        }
        if (totalAmountEl) {
            totalAmountEl.textContent = formatVND(globalTotalAmount);
        }
        if (headerCartBadge) {
            headerCartBadge.textContent = globalTotalCount;
        }
    }

    const qtyMinusBtns = document.querySelectorAll('.js-qty-minus');
    const qtyPlusBtns = document.querySelectorAll('.js-qty-plus');
    const qtyInputs = document.querySelectorAll('.quantity-input');

    qtyMinusBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const container = this.closest('.quantity-control');
            const input = container ? container.querySelector('.quantity-input') : null;
            if (input) {
                let val = parseInt(input.value) || 1;
                let min = parseInt(input.getAttribute('min')) || 1;
                if (val > min) {
                    input.value = val - 1;
                    triggerChange(input);
                }
            }
        });
    });

    qtyPlusBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const container = this.closest('.quantity-control');
            const input = container ? container.querySelector('.quantity-input') : null;
            if (input) {
                let val = parseInt(input.value) || 1;
                let max = parseInt(input.getAttribute('max')) || 99;
                if (val < max) {
                    input.value = val + 1;
                    triggerChange(input);
                }
            }
        });
    });

    qtyInputs.forEach(input => {
        input.addEventListener('change', function () {
            if (this.classList.contains('js-cart-qty-input')) {
                recalculateCartTotals();
            }
        });
        input.addEventListener('input', function () {
            if (this.classList.contains('js-cart-qty-input')) {
                recalculateCartTotals();
            }
        });
    });

    function triggerChange(element) {
        const event = new Event('change', { bubbles: true });
        element.dispatchEvent(event);
    }

    // 6. Auto-fill tài khoản Demo
    const demoButtons = document.querySelectorAll('.js-fill-demo');
    demoButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const email = this.getAttribute('data-email');
            const pass = this.getAttribute('data-password');

            const emailInput = document.querySelector('#email');
            const passInput = document.querySelector('#password');

            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = pass;
            }
        });
    });

});
