document.addEventListener('DOMContentLoaded', function () {
    const CART_KEY = 'event_carts';
    const badge = document.getElementById('globalCartBadge');

    function updateGlobalCartBadge() {
        if (!badge) return;

        let totalItems = 0;
        try {
            let allCarts = JSON.parse(localStorage.getItem(CART_KEY)) || {};

            for (let storeId in allCarts) {
                let items = allCarts[storeId].items || {};
                for (let itemId in items) {
                    totalItems += items[itemId].qty;
                }
            }
        } catch (e) {
            console.error("Erreur de lecture du panier local", e);
        }

        if (totalItems > 0) {
            badge.textContent = totalItems > 99 ? '99+' : totalItems; // Anti-débordement visuel
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    updateGlobalCartBadge();

    window.addEventListener('cartUpdated', updateGlobalCartBadge);
});
