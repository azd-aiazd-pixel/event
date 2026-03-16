@extends('layouts.store')

@section('content')
    <div class="h-screen w-full flex overflow-hidden bg-[#F1F5F9] font-sans" x-data="terminalHandler()">

        <div
            class="w-[88px] bg-slate-900 flex flex-col items-center py-6 flex-shrink-0 z-30 shadow-[4px_0_24px_rgba(0,0,0,0.15)] relative border-r border-slate-800">

            <div
                class="w-12 h-12 bg-slate-800 text-white rounded-2xl flex items-center justify-center font-black mb-8 shadow-inner overflow-hidden border border-slate-700">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xl">🏪</span>
                @endif
            </div>

            <div class="flex flex-col gap-4 w-full px-3">
                <button @click="switchTab('pos')"
                    :class="currentTab === 'pos' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="flex flex-col items-center justify-center p-3 rounded-2xl gap-2 transition-all group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="text-[9px] font-black uppercase tracking-wider">Caisse</span>
                </button>

                <button @click="switchTab('pickup')"
                    :class="currentTab === 'pickup' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="flex flex-col items-center justify-center p-3 rounded-2xl gap-2 transition-all group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <span class="text-[9px] font-black uppercase tracking-wider">Retraits</span>
                </button>
            </div>

            <div class="mt-auto w-full px-3">
                <a href="{{ route('store.dashboard', $store) }}"
                    class="flex flex-col items-center justify-center p-3 rounded-2xl gap-2 text-slate-500 hover:bg-red-500 hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="text-[9px] font-black uppercase tracking-wider">Sortir</span>
                </a>
            </div>
        </div>

        <div class="flex-grow relative overflow-hidden bg-[#F1F5F9]">

            <div x-show="currentTab === 'pos'" class="absolute inset-0" x-transition.opacity>
                @include('Store.terminal.partials.pos')
            </div>

            <div x-show="currentTab === 'pickup'" class="absolute inset-0 bg-slate-50" x-transition.opacity x-cloak>
                @include('Store.terminal.partials.pickup')
            </div>

        </div>

        <div x-show="toast.show" x-transition class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[200] pointer-events-none"
            x-cloak>
            <div :class="toast.type === 'success' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-red-500 shadow-red-500/20'"
                class="px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-4 text-white pointer-events-auto">
                <span class="font-black uppercase text-[10px] tracking-widest" x-text="toast.message"></span>
            </div>
        </div>

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        function terminalHandler() {
            return {
                
                currentTab: 'pos', 

                //les etats de la caisse
                products: @json($products ?? []),
                productMap: {},
                cart: [],
                search: '',
                activeCategory: null,
                selectedItemIndex: null,
                nfcCode: '',
                isNewInput: true,
                isProcessing: false,
                toast: { show: false, message: '', type: 'success' },

                //les etat de pickup
                readyOrders: @json($readyOrders ?? (object) []),
                pickupSearch: '',
                scannedParticipantId: null,

                init() {
                    this.productMap = Object.fromEntries(this.products.map(p => [p.id, p]));

                    if (typeof window.Echo !== 'undefined') {
                        window.Echo.private(`store.{{ $store->id }}.pickups`)
                            .listen('OrderReadyForPickup', (event) => {
                                console.log(' Nouvelle commande prête !', event);

                                const pId = event.participant_id;
                                const newOrder = event.order;

                                // Si on n'a pas encore de groupe pour ce client, on le crée
                                if (!this.readyOrders[pId]) {
                                    this.readyOrders[pId] = [];
                                }

                                // On vérifie que la commande n'est pas déjà affichée
                                const exists = this.readyOrders[pId].find(o => o.id === newOrder.id);
                                if (!exists) {
                                    // On ajoute la commande dans la liste mémoire d'Alpine
                                    this.readyOrders[pId].push(newOrder);
                                    this.readyOrders = { ...this.readyOrders }; // Force la réactivité

                                    // On affiche la petite notification verte en bas
                                    this.showToast(`Nouveau commande à livrer (Ticket #${newOrder.id}) !`, 'success');
                                }
                            });
                    } else {
                        console.warn('Attention: Laravel Echo n\'est pas activé.');
                    }
                },

                switchTab(tab) {
                    this.currentTab = tab;
                    this.$nextTick(() => {
                        const inputSelector = tab === 'pos'
                            ? 'input[x-model="nfcCode"]'
                            : 'input[x-model="pickupSearch"]';
                        const input = document.querySelector(inputSelector);
                        if (input) input.focus();
                    });
                },

                showToast(msg, type = 'success') {
                    this.toast.message = msg;
                    this.toast.type = type;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                // fonct de caisse
                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchSearch = p.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchCat = this.activeCategory === null || p.category_id === this.activeCategory;
                        return matchSearch && matchCat;
                    });
                },

                get total() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },

                addToCart(product) {
                    if (this.isProcessing) return;
                    const existing = this.cart.find(i => i.id === product.id);
                    if (existing) {
                        existing.qty++;
                        this.selectedItemIndex = this.cart.findIndex(i => i.id === product.id);
                    } else {
                        this.cart.push({ id: product.id, name: product.name, picture: product.picture, price: parseFloat(product.unit_price), qty: 1 });
                        this.selectedItemIndex = this.cart.length - 1;
                    }
                    this.isNewInput = true;
                },

                selectItem(index) {
                    if (this.isProcessing) return;
                    this.selectedItemIndex = index;
                    this.isNewInput = true;
                },

                handleNumpad(val) {
                    if (this.selectedItemIndex === null || this.isProcessing) return;
                    let currentItem = this.cart[this.selectedItemIndex];
                    if (val === 'C') { currentItem.qty = 1; this.isNewInput = true; return; }

                    if (this.isNewInput) {
                        currentItem.qty = parseInt(val === '00' ? '0' : val);
                        this.isNewInput = false;
                    } else {
                        let currentStr = currentItem.qty.toString();
                        let newVal = parseInt(currentStr + val);
                        if (newVal < 10000) currentItem.qty = newVal;
                    }
                    if (currentItem.qty < 1) currentItem.qty = 1;
                },

                removeItem() {
                    if (this.selectedItemIndex !== null && !this.isProcessing) {
                        this.cart.splice(this.selectedItemIndex, 1);
                        this.selectedItemIndex = (this.cart.length > 0) ? 0 : null;
                        this.isNewInput = true;
                    }
                },

                formatPrice(val) { return parseFloat(val).toFixed(2); },

                async processPayment() {
                    if (this.isProcessing || !this.nfcCode || this.cart.length === 0) return;
                    this.isProcessing = true;
                    try {
                        const res = await fetch("{{ route('store.terminal.pay', $store) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                nfc_code: this.nfcCode,
                                cart: this.cart.map(i => ({ id: i.id, qty: i.qty }))
                            })
                        });

                        const data = await res.json();

                        if (data.success) {
                            this.cart.forEach(cartItem => {
                                const product = this.productMap[cartItem.id];
                                if (product) product.quantity -= cartItem.qty;
                            });
                            this.cart = [];
                            this.nfcCode = '';
                            this.selectedItemIndex = null;
                            this.isNewInput = true;
                            this.showToast('Paiement validé !', 'success');

                            this.$nextTick(() => {
                                const input = document.querySelector('input[x-model="nfcCode"]');
                                if (input) input.focus();
                            });
                        } else {
                            this.showToast(data.error || 'Erreur lors du paiement', 'error');
                            this.nfcCode = '';
                        }
                    } catch (e) {
                        this.showToast('Erreur technique serveur', 'error');
                    } finally {
                        this.isProcessing = false;
                    }
                },

                // les fonctions de pickup

                get pickupGroups() {
                    let groups = [];

                    // Si on a scanné un bracelet spécifique
                    if (this.scannedParticipantId) {
                        if (this.readyOrders[this.scannedParticipantId]) {
                            groups.push({
                                pId: this.scannedParticipantId,
                                orders: this.readyOrders[this.scannedParticipantId]
                            });
                        }
                        return groups;
                    }

                    //  Si on cherche un numéro de commande manuel
                    if (this.pickupSearch && !isNaN(this.pickupSearch) && this.pickupSearch.length < 5) {
                        for (let [pId, orders] of Object.entries(this.readyOrders)) {
                            if (orders.some(o => o.id.toString().includes(this.pickupSearch))) {
                                groups.push({ pId: pId, orders: orders });
                            }
                        }
                        if (groups.length > 0) return groups;
                    }

                    //  Affichage normal : on liste tout
                    for (let [pId, orders] of Object.entries(this.readyOrders)) {
                        groups.push({ pId: pId, orders: orders });
                    }
                    return groups;
                },

                async processPickupScan() {
                    if (!this.pickupSearch) return;

                    // Si c'est manuel, on ne fait pas de requête au serveur
                    if (this.pickupSearch.length < 5 && !isNaN(this.pickupSearch)) {
                        return;
                    }

                    try {
                        const res = await fetch("{{ route('store.terminal.pickup.scan', $store) }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ nfc_code: this.pickupSearch })
                        });
                        const data = await res.json();

                        if (data.success) {
                            this.scannedParticipantId = data.participant_id;
                            this.pickupSearch = '';
                            this.showToast('Bracelet reconnu !', 'success');
                        } else {
                            this.showToast(data.error || 'Aucune commande pour ce bracelet', 'error');
                            this.pickupSearch = '';
                        }
                    } catch (e) {
                        this.showToast('Erreur serveur', 'error');
                    }
                },

                async collectOrders(pId, orderIds) {
                    try {
                        const res = await fetch("{{ route('store.terminal.pickup.collect', $store) }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ order_ids: orderIds })
                        });
                        const data = await res.json();

                        if (data.success) {
                            delete this.readyOrders[pId]; 
                            this.resetPickupFilter();
                            this.showToast('Commande livrée avec succès !', 'success');
                        } else {
                            this.showToast('Erreur lors de la validation', 'error');
                        }
                    } catch (e) {
                        this.showToast('Erreur serveur', 'error');
                    }
                },

                resetPickupFilter() {
                    this.scannedParticipantId = null;
                    this.pickupSearch = '';
                }
            }
        }
    </script>
@endsection