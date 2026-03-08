/**
 * oc-shop Storefront JavaScript
 */

// Update cart count on page load
document.addEventListener('DOMContentLoaded', function () {
    updateCartCount();
});

async function updateCartCount() {
    try {
        const resp = await fetch('/cart', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        // If cart count is embedded in the layout, skip this
    } catch (e) {
        // silent fail
    }
}

// Confirm before delete actions
document.querySelectorAll('[data-confirm]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        if (!confirm(btn.dataset.confirm || 'Are you sure?')) {
            e.preventDefault();
        }
    });
});
