@extends('layouts.store')

@section('title', 'Remboursements')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="refundManager()">




        <div x-show="message" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="mb-8 p-5 rounded-[1.5rem] border flex items-center gap-4 text-sm font-bold shadow-sm" :class="{
                 'bg-red-50 border-red-100 text-red-600': messageType === 'error',
                 'bg-emerald-50 border-emerald-100 text-emerald-600': messageType === 'success',
                 'bg-blue-50 border-blue-100 text-blue-600': messageType === 'info'
             }">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                :class="messageType === 'success' ? 'bg-emerald-200/50' : (messageType === 'error' ? 'bg-red-200/50' : 'bg-blue-200/50')">
                <svg x-show="messageType === 'success'" class="w-4 h-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
                <svg x-show="messageType === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <svg x-show="messageType === 'info'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span x-text="message" class="flex-grow"></span>
            <button @click="message = ''" class="opacity-50 hover:opacity-100 transition-opacity p-2">&times;</button>
        </div>

        {{-- Zone de Scan / Recherche --}}
        <div
            class="bg-white border border-slate-100 p-3 sm:p-4 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.02)] mb-12 relative overflow-hidden group">
            <div
                class="absolute inset-0 bg-gradient-to-r from-slate-50 to-transparent w-32 group-focus-within:w-full transition-all duration-700 ease-out opacity-50">
            </div>

            <form @submit.prevent="searchOrders" class="relative flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow flex items-center">
                    <div class="absolute left-6 flex items-center justify-center text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <input type="text" x-model="nfcCode" x-ref="nfcInput" autofocus
                        placeholder="Scannez le bracelet NFC du participant..."
                        class="w-full pl-16 pr-6 py-5 bg-transparent border-none rounded-[1.5rem] text-lg font-black text-slate-900 focus:ring-0 placeholder-slate-300 transition-all outline-none"
                        :disabled="isLoading">
                </div>
                <button type="submit" :disabled="isLoading || !nfcCode"
                    class="px-10 py-5 bg-slate-900 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] hover:bg-black hover:shadow-xl hover:-translate-y-0.5 disabled:opacity-50 disabled:hover:translate-y-0 transition-all flex items-center justify-center gap-3">
                    <span x-show="!isLoading">Rechercher</span>
                    <svg x-show="isLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </form>
        </div>

        {{-- État Vide (Attente de scan) --}}
        <div x-show="orders.length === 0 && !message && !isLoading"
            class="py-20 flex flex-col items-center justify-center text-center opacity-50">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </div>
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">En attente de lecture NFC</p>
        </div>

        {{-- Liste des commandes --}}
        <div x-show="orders.length > 0" x-cloak class="space-y-6">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 pl-4 border-l-2 border-slate-200">
                Commandes Éligibles
            </h3>

            <div class="grid grid-cols-1 gap-4">
                <template x-for="order in orders" :key="order.id">
                    <div
                        class="bg-white border border-slate-100 p-6 sm:p-8 rounded-[2rem] shadow-[0_10px_30px_rgba(0,0,0,0.01)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.04)] transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6 group">

                        {{-- Info Commande --}}
                        <div class="flex-grow">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="text-lg font-black tracking-tight text-slate-900"
                                    x-text="'#' + order.id"></span>
                                <span
                                    class="px-3 py-1.5 bg-slate-50 text-slate-500 rounded-xl text-[9px] font-black uppercase tracking-widest border border-slate-100"
                                    x-text="new Date(order.updated_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                            </div>

                            {{-- Détail des items --}}
                            <div class="flex flex-wrap gap-2">
                                <template x-for="item in order.items" :key="item.id">
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                        <span
                                            class="w-6 h-6 rounded-lg bg-white flex items-center justify-center text-[10px] font-black text-slate-900 shadow-sm"
                                            x-text="item.quantity"></span>
                                        <span class="text-[11px] font-bold text-slate-600"
                                            x-text="item.product.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Prix et Action --}}
                        <div
                            class="flex items-center justify-between md:flex-col md:items-end md:justify-center gap-4 border-t md:border-t-0 border-slate-50 pt-6 md:pt-0 pl-0 md:pl-8 md:border-l">
                            <div class="text-left md:text-right">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Montant à
                                    rendre</p>
                                <p class="text-2xl font-black tracking-tighter text-slate-900">
                                    <span x-text="parseFloat(order.total_points).toString()"></span>
                                    <span class="text-[12px] text-slate-400 tracking-normal ml-1">Pts</span>
                                </p>
                            </div>

                            <button @click="processRefund(order.id)" :disabled="isLoading"
                                class="px-6 py-3.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all duration-300 disabled:opacity-50">
                                Rembourser
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            window.StoreRefundConfig = {
                csrfToken: '{{ csrf_token() }}',
                routes: {
                    search: '{{ route("store.refunds.search", $store->id) }}',
                    process: '{{ route("store.refunds.process", $store->id) }}'
                }
            };
        </script>
        @vite('resources/js/store/refund/index.js')
    @endpush
@endsection