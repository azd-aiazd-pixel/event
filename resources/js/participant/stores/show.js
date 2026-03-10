window.toggleWishlist = async function (productId, btnElement) {
    if (typeof window.ParticipantStoreShowConfig === 'undefined') {
        console.error('ParticipantStoreShowConfig is not defined.');
        return;
    }
    event.stopPropagation();

    const config = window.ParticipantStoreShowConfig;
    const url = config.wishlistToggleRoute.replace(':id', productId);

    const svg = btnElement.querySelector('svg');
    svg.classList.add('animate-pulse');

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        svg.classList.remove('animate-pulse');

        if (response.ok) {
            if (data.is_favorite) {
                svg.classList.remove('text-zinc-300', 'stroke-2');
                svg.classList.add('text-red-500', 'fill-current');
            } else {
                svg.classList.remove('text-red-500', 'fill-current');
                svg.classList.add('text-zinc-300', 'stroke-2');
            }

            if (window.showToast) {
                window.showToast(data.message, 'success');
            }
        } else {
            alert('Erreur: ' + (data.message || 'Impossible de modifier les favoris.'));
        }
    } catch (error) {
        console.error("Erreur réseau :", error);
        svg.classList.remove('animate-pulse');
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const CART_KEY = 'event_carts';
    const storeData = document.getElementById('storeData');
    if (!storeData) return;

    const currentStoreId = storeData.getAttribute('data-id');
    const currentStoreName = storeData.getAttribute('data-name');

    const cartControlsElements = document.querySelectorAll('.cart-controls');
    const stickyCartBar = document.getElementById('stickyCartBar');
    const cartTotalItemsSpan = document.getElementById('cartTotalItems');
    const cartTotalPriceSpan = document.getElementById('cartTotalPrice');

    let allCarts = JSON.parse(localStorage.getItem(CART_KEY)) || {};

    if (!allCarts[currentStoreId]) {
        allCarts[currentStoreId] = {
            store_name: currentStoreName,
            items: {}
        };
    }
    let currentCart = allCarts[currentStoreId].items;

    function saveCart() {
        if (Object.keys(currentCart).length === 0) {
            delete allCarts[currentStoreId];
        } else {
            if (!allCarts[currentStoreId]) {
                allCarts[currentStoreId] = {
                    store_name: currentStoreName,
                    items: {}
                };
            }
            allCarts[currentStoreId].items = currentCart;
        }

        localStorage.setItem(CART_KEY, JSON.stringify(allCarts));
        updateUI();

        window.dispatchEvent(new Event('cartUpdated'));
    }

    function updateUI() {
        let totalItems = 0;
        let totalPrice = 0;

        cartControlsElements.forEach(control => {
            const productId = control.getAttribute('data-id');
            const btnInitial = control.querySelector('.btn-add-initial');
            const qtyControls = control.querySelector('.btn-qty-controls');
            const qtyDisplay = control.querySelector('.qty-display');

            if (currentCart[productId]) {
                btnInitial.classList.add('hidden');
                qtyControls.classList.remove('hidden');
                qtyDisplay.textContent = currentCart[productId].qty;

                totalItems += currentCart[productId].qty;
                totalPrice += currentCart[productId].qty * currentCart[productId].price;
            } else {
                btnInitial.classList.remove('hidden');
                qtyControls.classList.add('hidden');
            }
        });

        if (totalItems > 0) {
            cartTotalItemsSpan.textContent = totalItems;
            cartTotalPriceSpan.textContent = Number(totalPrice.toFixed(2));
            stickyCartBar.classList.remove('translate-y-[150%]');
        } else {
            stickyCartBar.classList.add('translate-y-[150%]');
        }
    }

    cartControlsElements.forEach(control => {
        const productId = control.getAttribute('data-id');
        const productName = control.getAttribute('data-name');
        const productPrice = parseFloat(control.getAttribute('data-price'));

        const btnInitial = control.querySelector('.btn-add-initial');
        const btnPlus = control.querySelector('.btn-plus');
        const btnMinus = control.querySelector('.btn-minus');

        btnInitial.addEventListener('click', () => {
            currentCart[productId] = {
                name: productName,
                price: productPrice,
                qty: 1
            };
            saveCart();
        });

        btnPlus.addEventListener('click', () => {
            currentCart[productId].qty += 1;
            saveCart();
        });

        btnMinus.addEventListener('click', () => {
            if (currentCart[productId].qty > 1) {
                currentCart[productId].qty -= 1;
            } else {
                delete currentCart[productId];
            }
            saveCart();
        });
    });

    // --- GESTION DU TRI PAR PRIX (SANS RECHARGEMENT) ---
    const priceSortBtn = document.getElementById('priceSortBtn');
    const sortIcon = document.getElementById('sortIcon');
    const productsContainer = document.getElementById('productsContainer');

    if (priceSortBtn && productsContainer) {
        // 1. Sauvegarder l'ordre initial (default) dès le chargement
        const productCardsArray = Array.from(productsContainer.querySelectorAll('.product-card'));
        productCardsArray.forEach((card, index) => {
            card.setAttribute('data-original-index', index);
        });

        priceSortBtn.addEventListener('click', () => {
            let currentCards = Array.from(productsContainer.querySelectorAll('.product-card'));
            let currentSort = priceSortBtn.getAttribute('data-sort');
            let nextSort = 'asc';

            // Définir la prochaine étape
            if (currentSort === 'default') nextSort = 'asc';
            else if (currentSort === 'asc') nextSort = 'desc';
            else if (currentSort === 'desc') nextSort = 'default';

            priceSortBtn.setAttribute('data-sort', nextSort);

            // Mettre à jour l'icône
            if (nextSort === 'asc') {
                sortIcon.innerHTML = `<svg class="h-4 w-4 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" /></svg>`;
                priceSortBtn.classList.add('border-zinc-900', 'bg-zinc-50');
            } else if (nextSort === 'desc') {
                sortIcon.innerHTML = `<svg class="h-4 w-4 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" /></svg>`;
            } else {
                sortIcon.innerHTML = `<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>`;
                priceSortBtn.classList.remove('border-zinc-900', 'bg-zinc-50');
            }

            // Trier les cartes SANS recharger la page
            currentCards.sort((a, b) => {
                if (nextSort === 'default') {
                    // On retourne à l'index d'origine sauvegardé plus haut
                    return parseInt(a.getAttribute('data-original-index')) - parseInt(b.getAttribute('data-original-index'));
                } else {
                    let priceA = parseFloat(a.getAttribute('data-price'));
                    let priceB = parseFloat(b.getAttribute('data-price'));
                    return nextSort === 'asc' ? priceA - priceB : priceB - priceA;
                }
            });

            // Réinsérer les cartes triées dans le conteneur
            currentCards.forEach(card => productsContainer.appendChild(card));
        });
    }

    // Category Filter Logic
    const categoryBtns = document.querySelectorAll('.category-btn');
    const productCards = document.querySelectorAll('.product-card');

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const category = btn.getAttribute('data-category');

            categoryBtns.forEach(b => {
                b.classList.remove('active');
                b.classList.add('bg-white', 'text-zinc-600');
            });

            btn.classList.add('active');
            btn.classList.remove('bg-white', 'text-zinc-600');

            productCards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();

            productCards.forEach(card => {
                const productName = card.getAttribute('data-name');
                if (productName.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    updateUI();
});
