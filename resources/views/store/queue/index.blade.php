@extends('layouts.store')

@section('content')

    <div class="container mx-auto max-w-7xl relative" x-data="queueHandler()">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-black text-black tracking-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    FILE D'ATTENTE
                </h1>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1">
                    Magasin : {{ $store->name }}
                </p>
            </div>

            <div class="flex items-center gap-4 mt-4 md:mt-0">

                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100 transition-colors"
                    :class="isConnected ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100'">
                    <span class="w-2 h-2 rounded-full"
                        :class="isConnected ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest"
                        :class="isConnected ? 'text-emerald-700' : 'text-red-600'"
                        x-text="isConnected ? 'En direct' : 'Déconnecté'"></span>
                </div>

                <div class="bg-black text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest">
                    <span x-text="orders.length"></span> Commandes
                </div>
            </div>
        </div>


        <template x-if="orders.length === 0">
            <div
                class="flex flex-col items-center justify-center py-32 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <svg class="w-16 h-16 text-gray-200 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Aucune commande</h3>
                <p class="text-xs font-medium text-gray-400 mt-2">La file d'attente est vide.</p>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="order in orders" :key="order.id">
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-black/5 transition-all flex flex-col overflow-hidden group">

                    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-end bg-gray-50/50">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Commande</p>
                            <span class="text-2xl font-black tracking-tighter text-black">#<span
                                    x-text="order.id"></span></span>
                        </div>
                        <span x-show="order.isNew" x-transition.opacity
                            class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest animate-pulse">
                            Nouveau
                        </span>
                    </div>

                    <div class="p-6 flex-grow">
                        <ul class="space-y-4">
                            <template x-for="item in order.items" :key="item.id">
                                <li class="flex items-start gap-4">
                                    <span
                                        class="bg-black text-white text-xs font-black px-2 py-1 rounded-md min-w-[2.5rem] text-center"
                                        x-text="item.quantity + 'x'">
                                    </span>
                                    <span class="text-sm font-bold text-gray-800 pt-0.5 leading-tight"
                                        x-text="item.product ? item.product.name : 'Produit inconnu'">
                                    </span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div class="px-6 pb-6 pt-2 flex gap-2">
                        {{-- Bouton Marquer Prêt --}}
                        <button @click="markAsReady(order.id)"
                            :disabled="processingId === order.id || cancellingId === order.id"
                            class="flex-1 text-[10px] font-black uppercase tracking-widest py-4 rounded-xl transition-colors flex justify-center items-center gap-2"
                            :class="processingId === order.id ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-400 hover:bg-black hover:text-white group-hover:bg-black group-hover:text-white'">

                            <svg x-show="processingId === order.id" class="w-4 h-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            <svg x-show="processingId !== order.id" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>

                            <span x-text="processingId === order.id ? 'En cours...' : 'Prêt'"></span>
                        </button>

                        {{-- Bouton Annuler --}}
                        <button @click="cancelOrder(order.id)"
                            :disabled="cancellingId === order.id || processingId === order.id"
                            class="flex-1 text-[10px] font-black uppercase tracking-widest py-4 rounded-xl transition-colors flex justify-center items-center gap-2"
                            :class="cancellingId === order.id ? 'bg-red-100 text-red-300 cursor-not-allowed' : 'bg-red-50 text-red-400 hover:bg-red-500 hover:text-white'">

                            <svg x-show="cancellingId === order.id" class="w-4 h-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            <svg x-show="cancellingId !== order.id" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>

                            <span x-text="cancellingId === order.id ? 'En cours...' : 'Annuler'"></span>
                        </button>
                    </div>

                </div>
            </template>
        </div>

        <div x-show="toast.show" x-transition class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[200]" x-cloak>
            <div :class="toast.type === 'success' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-red-500 shadow-red-500/20'"
                class="px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-4 text-white">
                <span class="font-black uppercase text-[10px] tracking-widest" x-text="toast.message"></span>
            </div>
        </div>

    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

@endsection

@section('header_scripts')
    <script>
        window.StoreQueueConfig = {
            pendingOrders: @json($pendingOrders ?? []),
            storeId: '{{ $store->id }}',
            csrfToken: '{{ csrf_token() }}',
            routes: {
                completeOrder: '{{ route("store.orders.complete", ["order" => ":order"]) }}',
                cancelOrder: '{{ route("store.orders.cancel", ["order" => ":order"]) }}'
            }
        };
    </script>
    @vite('resources/js/store/queue/index.js')
@endsection