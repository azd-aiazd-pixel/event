document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.ParticipantCartConfig === 'undefined') {
        console.error('ParticipantCartConfig is not defined. Make sure it is defined in the blade view.');
        return;
    }

    const config = window.ParticipantCartConfig;
    const CART_KEY = 'event_carts';
    const cartContainer = document.getElementById('cartContainer');
    const emptyCartState = document.getElementById('emptyCartState');

    function renderCart() {
        if (!cartContainer || !emptyCartState) return;

        cartContainer.innerHTML = '';

        let allCarts = JSON.parse(localStorage.getItem(CART_KEY)) || {};
        const storeIds = Object.keys(allCarts);

        if (storeIds.length === 0) {
            emptyCartState.classList.remove('hidden');
            return;
        } else {
            emptyCartState.classList.add('hidden');
        }

        storeIds.forEach(storeId => {
            const storeCart = allCarts[storeId];
            const items = storeCart.items;
            let storeTotal = 0;
            let itemsHtml = '';

            if (Object.keys(items).length === 0) {
                delete allCarts[storeId];
                localStorage.setItem(CART_KEY, JSON.stringify(allCarts));
                window.dispatchEvent(new Event('cartUpdated')); // Met à jour la bulle du haut
                renderCart(); // Redessine
                return;
            }

            for (let productId in items) {
                const item = items[productId];
                const itemTotal = Number((item.price * item.qty).toFixed(2));
                storeTotal += itemTotal;

                itemsHtml += `
                    <div class="flex justify-between items-center py-4 border-b border-zinc-50 last:border-0">
                        <div class="flex-grow pr-4">
                            <h3 class="font-bold text-zinc-800 text-sm leading-tight">${item.name}</h3>
                            <div class="font-extrabold text-zinc-900 mt-1">${item.price} <span class="text-[10px] text-zinc-500">Pts/u</span></div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center bg-zinc-100 rounded-full border border-zinc-200 overflow-hidden h-8 shadow-sm">
                                <button onclick="window.updateQty('${storeId}', '${productId}', -1)" class="w-8 h-full flex items-center justify-center text-zinc-600 hover:bg-zinc-200 active:bg-zinc-300 transition-colors">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
                                </button>
                                <span class="w-6 text-center text-xs font-extrabold text-zinc-900">${item.qty}</span>
                                <button onclick="window.updateQty('${storeId}', '${productId}', 1)" class="w-8 h-full flex items-center justify-center text-zinc-600 hover:bg-zinc-200 active:bg-zinc-300 transition-colors">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                            <span class="font-extrabold text-zinc-900 text-sm">${itemTotal} <span class="text-[10px] text-zinc-500">Pts</span></span>
                        </div>
                    </div>
                `;
            }

            storeTotal = Number(storeTotal.toFixed(2));

            // la card du magasin avec liste
            const storeCard = document.createElement('div');
            storeCard.className = 'bg-white rounded-3xl p-5 shadow-sm border border-zinc-100';
            storeCard.innerHTML = `
                <div class="flex items-center gap-3 mb-2 pb-4 border-b border-zinc-100">
                    <div class="w-10 h-10 rounded-full bg-zinc-50 flex items-center justify-center border border-zinc-100 shadow-inner">
                        <span class="text-xl">🏪</span>
                    </div>
                    <h2 class="font-extrabold text-lg text-zinc-900">${storeCart.store_name}</h2>
                </div>
                
                <div class="mb-2">
                    ${itemsHtml}
                </div>

                <div class="flex items-center justify-between pt-4 mt-2 border-t border-zinc-100 border-dashed">
                    <div>
                        <span class="block text-xs font-bold text-zinc-400 uppercase tracking-wider">Total</span>
                        <span class="font-black text-2xl text-zinc-900">${storeTotal} <span class="text-sm text-zinc-500 font-bold">Pts</span></span>
                    </div>
                    
                    <button onclick="window.checkoutStore('${storeId}', event)" class="bg-zinc-900 text-white px-6 py-3.5 rounded-xl font-bold flex items-center gap-2 hover:bg-zinc-800 active:scale-95 transition-all shadow-md shadow-zinc-900/20">
                        <span>Payer</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            `;

            cartContainer.appendChild(storeCard);
        });
    }

    if (cartContainer && emptyCartState) {
        window.renderCart = renderCart;
        renderCart();
    }

    window.updateQty = function (storeId, productId, change) {
        let allCarts = JSON.parse(localStorage.getItem(CART_KEY)) || {};

        if (allCarts[storeId] && allCarts[storeId].items[productId]) {
            allCarts[storeId].items[productId].qty += change;

            if (allCarts[storeId].items[productId].qty <= 0) {
                delete allCarts[storeId].items[productId];
            }

            localStorage.setItem(CART_KEY, JSON.stringify(allCarts));
            window.dispatchEvent(new Event('cartUpdated'));
            if (window.renderCart) {
                window.renderCart();
            }
        }
    };
});

window.showToast = function (message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 flex flex-col gap-3 w-[90%] max-w-md pointer-events-none';
        document.body.appendChild(container);
    }

    const isSuccess = type === 'success';
    const bgColor = isSuccess ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-red-50 border-red-200 text-red-900';
    const icon = isSuccess
        ? `<svg class="w-6 h-6 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
        : `<svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;

    const toast = document.createElement('div');
    toast.className = `flex items-center gap-3 p-4 rounded-2xl shadow-xl border ${bgColor} transform transition-all duration-300 -translate-y-5 opacity-0`;
    toast.innerHTML = `
        ${icon}
        <span class="font-bold text-sm">${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('-translate-y-5', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('-translate-y-5', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
};

window.checkoutStore = async function (storeId, event) {
    const btn = event.currentTarget;
    const originalHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="animate-pulse">Paiement...</span>';

    const allCarts = JSON.parse(localStorage.getItem('event_carts')) || {};
    const cartToCheckout = allCarts[storeId];

    if (!cartToCheckout) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        return;
    }

    const itemsArray = Object.keys(cartToCheckout.items).map(productId => {
        return {
            id: productId,
            qty: cartToCheckout.items[productId].qty
        };
    });

    const payload = {
        store_id: storeId,
        items: itemsArray
    };

    try {
        const config = window.ParticipantCartConfig;
        const response = await fetch(config.checkoutRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok) {
            window.showToast(data.message, 'success');

            delete allCarts[storeId];
            localStorage.setItem('event_carts', JSON.stringify(allCarts));

            let summaryUrl = config.orderShowRoute;
            window.location.href = summaryUrl.replace(':id', data.order_id);

        } else {
            window.showToast(data.message || 'Paiement refusé.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }

    } catch (error) {
        console.error("Erreur réseau :", error);
        window.showToast('Erreur de connexion au serveur.', 'error');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
};
