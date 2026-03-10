document.addEventListener('DOMContentLoaded', function () {
    const CART_KEY = 'event_carts';
    const cartControlsElements = document.querySelectorAll('.cart-controls');
    const stickyCartBar = document.getElementById('stickyCartBar');
    const cartTotalItemsSpan = document.getElementById('cartTotalItems');
    const cartTotalPriceSpan = document.getElementById('cartTotalPrice');

    if (!stickyCartBar || !cartTotalItemsSpan || !cartTotalPriceSpan) return;

    let allCarts = JSON.parse(localStorage.getItem(CART_KEY)) || {};

    function saveCart() {
        for (let storeId in allCarts) {
            if (allCarts[storeId] && allCarts[storeId].items && Object.keys(allCarts[storeId].items).length === 0) {
                delete allCarts[storeId];
            }
        }
        localStorage.setItem(CART_KEY, JSON.stringify(allCarts));
        updateUI();
        window.dispatchEvent(new Event('cartUpdated'));
    }

    function updateUI() {
        let globalTotalItems = 0;
        let globalTotalPrice = 0;

        cartControlsElements.forEach(control => {
            const storeId = control.getAttribute('data-store-id');
            const productId = control.getAttribute('data-id');
            const btnInitial = control.querySelector('.btn-add-initial');
            const qtyControls = control.querySelector('.btn-qty-controls');
            const qtyDisplay = control.querySelector('.qty-display');

            let storeCart = allCarts[storeId] ? allCarts[storeId].items : {};

            if (storeCart[productId]) {
                if (btnInitial) btnInitial.classList.add('hidden');
                if (qtyControls) qtyControls.classList.remove('hidden');
                if (qtyDisplay) qtyDisplay.textContent = storeCart[productId].qty;
            } else {
                if (btnInitial) btnInitial.classList.remove('hidden');
                if (qtyControls) qtyControls.classList.add('hidden');
            }
        });

        for (let storeId in allCarts) {
            let items = allCarts[storeId].items;
            for (let pid in items) {
                globalTotalItems += items[pid].qty;
                globalTotalPrice += items[pid].qty * items[pid].price;
            }
        }

        if (globalTotalItems > 0) {
            cartTotalItemsSpan.textContent = globalTotalItems;
            cartTotalPriceSpan.textContent = Number(globalTotalPrice.toFixed(2));
            stickyCartBar.classList.remove('translate-y-[150%]');
        } else {
            stickyCartBar.classList.add('translate-y-[150%]');
        }
    }

    cartControlsElements.forEach(control => {
        const storeId = control.getAttribute('data-store-id');
        const storeName = control.getAttribute('data-store-name');
        const productId = control.getAttribute('data-id');
        const productName = control.getAttribute('data-name');
        const productPrice = parseFloat(control.getAttribute('data-price'));

        const btnInitial = control.querySelector('.btn-add-initial');
        const btnPlus = control.querySelector('.btn-plus');
        const btnMinus = control.querySelector('.btn-minus');

        function getStoreCart() {
            if (!allCarts[storeId]) {
                allCarts[storeId] = { store_name: storeName, items: {} };
            }
            if (!allCarts[storeId].items) {
                allCarts[storeId].items = {};
            }
            return allCarts[storeId].items;
        }

        if (btnInitial) {
            btnInitial.addEventListener('click', () => {
                let items = getStoreCart();
                items[productId] = { name: productName, price: productPrice, qty: 1 };
                saveCart();
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', () => {
                let items = getStoreCart();
                if (items[productId]) {
                    items[productId].qty += 1;
                    saveCart();
                }
            });
        }

        if (btnMinus) {
            btnMinus.addEventListener('click', () => {
                let items = getStoreCart();
                if (items[productId]) {
                    if (items[productId].qty > 1) {
                        items[productId].qty -= 1;
                    } else {
                        delete items[productId];
                    }
                    saveCart();
                }
            });
        }
    });

    updateUI();
});
