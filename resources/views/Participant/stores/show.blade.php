@extends('layouts.participant')

@section('title', $store->name . ' - Menu')

@section('content')
    {{-- Dynamic Store Theme Settings --}}
    @php
        $primaryColor = $store->theme_primary_color ?? '#18181b'; // zinc-900 fallback
        $bgColor = $store->theme_bg_color ?? '#ffffff'; // white fallback
        $textColor = $store->theme_text_color ?? '#18181b'; // zinc-900 fallback
    @endphp

    @push('styles')
        @if($store->theme_bg_image)
            <link rel="preload" as="image" href="{{ asset('storage/' . $store->theme_bg_image) }}">
        @endif
        @if($store->theme_body_bg_image)
            <link rel="preload" as="image" href="{{ asset('storage/' . $store->theme_body_bg_image) }}">
        @endif
        <style>
            :root {
                --store-primary:
                    {{ $primaryColor }}
                ;
                --store-bg:
                    {{ $bgColor }}
                ;
                --store-text:
                    {{ $textColor }}
                ;
            }

            /* Custom classes utilizing the CSS variables */
            .store-bg-header {
                background-color: var(--store-bg);
            }

            .store-text-header {
                color: var(--store-text);
            }

            .store-bg-primary {
                background-color: var(--store-primary);
                color: #ffffff;
            }

            .store-text-primary {
                color: var(--store-primary);
            }

            .store-border-primary {
                border-color: var(--store-primary);
            }

            /* For category active state */
            .category-btn.active {
                background-color: var(--store-primary);
                color: #ffffff;
                border-color: var(--store-primary);
            }

            @if($store->theme_body_bg_image)
                body {
                    background-image: url('{{ asset('storage/' . $store->theme_body_bg_image) }}');
                    background-size: cover;
                    background-position: center;
                    background-attachment: fixed;
                }

            @endif
        </style>
    @endpush

    <div id="storeData" data-id="{{ $store->id }}" data-name="{{ $store->name }}" class="hidden"></div>

    <div class="mb-6 flex items-center gap-4 store-bg-header -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 -mt-4 sm:-mt-6 lg:-mt-8 shadow-sm bg-cover bg-center"
        @if($store->theme_bg_image) style="background-image: url('{{ asset('storage/' . $store->theme_bg_image) }}');"
        @endif>
        <a href="{{ route('participant.stores.index') }}"
            class="w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-sm border border-zinc-200 text-zinc-900 hover:bg-white active:scale-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/50 border border-zinc-200 overflow-hidden flex-shrink-0">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}"
                        class="w-full h-full object-cover">
                @else
                    <span class="w-full h-full flex items-center justify-center text-zinc-600 text-sm font-bold">🏪</span>
                @endif
            </div>
            <h1 class="text-xl font-extrabold tracking-tight store-text-header">{{ $store->name }}</h1>
        </div>
    </div>

    @if(!$products->isEmpty())
        <div class="mb-6 space-y-4">
            <div class="flex gap-2">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="block w-full pl-9 pr-3 py-2.5 border border-zinc-200 rounded-xl text-sm leading-5 bg-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all shadow-sm"
                        placeholder="Chercher un article...">
                </div>

                <button type="button" id="priceSortBtn" data-sort="default"
                    class="flex-shrink-0 flex items-center justify-center gap-1.5 px-4 py-2.5 border border-zinc-200 rounded-xl text-sm font-bold text-zinc-600 bg-white hover:bg-zinc-50 active:scale-95 transition-all shadow-sm w-[90px]">
                    <span>Prix</span>
                    <span id="sortIcon" class="text-zinc-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                    </span>
                </button>
            </div>

            @if($categories->count() > 0)
                <div
                    class="flex overflow-x-auto gap-2 pb-1 snap-x snap-mandatory [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                    <button
                        class="category-btn active snap-start whitespace-nowrap px-5 py-1.5 rounded-full text-sm font-bold shadow-sm transition-colors"
                        data-category="all">Tous</button>
                    @foreach($categories as $category)
                        <button
                            class="category-btn snap-start whitespace-nowrap px-5 py-1.5 rounded-full bg-white border border-zinc-200 text-zinc-600 text-sm font-bold shadow-sm transition-colors"
                            data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="mb-4">
        <div class="grid grid-cols-1 gap-4" id="productsContainer">
            @foreach($products as $product)
                <div class="product-card relative bg-white rounded-2xl p-3 shadow-sm border border-zinc-100 flex gap-4 items-center"
                    data-name="{{ strtolower($product->name) }}" data-price="{{ $product->unit_price }}"
                    data-category="{{ $product->category_id ?? 'all' }}">

                    @php
                        $isFavorite = in_array($product->id, $wishlistedIds);
                    @endphp
                    <button type="button" onclick="toggleWishlist({{ $product->id }}, this)"
                        class="absolute top-2 right-2 p-1.5 rounded-full bg-white/90 backdrop-blur shadow-sm active:scale-90 transition-all z-10 border border-zinc-100">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transition-colors duration-300 {{ $isFavorite ? 'text-red-500 fill-current' : 'text-zinc-300 stroke-2' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <div
                        class="w-20 h-20 rounded-xl bg-zinc-50 flex-shrink-0 overflow-hidden border border-zinc-100 flex items-center justify-center">
                        @if($product->picture)
                            <img src="{{ asset('storage/' . $product->picture) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="h-8 w-8 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>

                    <div class="flex-grow py-1">
                        <h3 class="font-bold text-zinc-900 text-base leading-tight">{{ $product->name }}</h3>

                        <div class="mt-3 flex items-center justify-between">
                            <div class="font-extrabold text-zinc-900 text-lg">
                                {{ rtrim(rtrim($product->unit_price, '0'), '.') }} <span
                                    class="text-xs font-semibold text-zinc-500">Pts</span>
                            </div>

                            <div class="cart-controls" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                data-price="{{ $product->unit_price }}">

                                <button type="button"
                                    class="btn-add-initial store-bg-primary w-9 h-9 rounded-full flex items-center justify-center active:scale-90 transition-all shadow-md">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>

                                <div
                                    class="btn-qty-controls hidden flex items-center bg-zinc-100 rounded-full border border-zinc-200 overflow-hidden h-9">
                                    <button type="button"
                                        class="btn-minus w-9 h-full flex items-center justify-center text-zinc-600 hover:bg-zinc-200 active:bg-zinc-300 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <span class="qty-display w-6 text-center text-sm font-extrabold text-zinc-900">1</span>
                                    <button type="button"
                                        class="btn-plus w-9 h-full flex items-center justify-center text-zinc-600 hover:bg-zinc-200 active:bg-zinc-300 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="stickyCartBar"
        class="fixed bottom-[76px] left-0 w-full store-bg-primary px-5 py-3.5 flex justify-between items-center shadow-[0_-8px_30px_rgba(0,0,0,0.12)] transition-transform duration-300 translate-y-[150%] z-40 rounded-t-3xl border-t border-black/10">
        <div class="flex flex-col">
            <span class="text-[11px] font-semibold text-white/70 uppercase tracking-wider">Panier en cours</span>
            <span class="font-extrabold text-base mt-0.5"><span id="cartTotalItems">0</span> articles • <span
                    id="cartTotalPrice">0</span> Pts</span>
        </div>
        <a href="{{ route('participant.cart.index') }}"
            class="bg-white/95 text-zinc-900 px-5 py-2.5 rounded-full text-sm font-extrabold active:scale-95 hover:bg-white transition-all shadow-sm text-center">
            Voir le panier
        </a>
    </div>

    @push('scripts')
        <script>
            window.ParticipantStoreShowConfig = {
                wishlistToggleRoute: '{{ route("participant.wishlist.toggle", ":id") }}',
                csrfToken: '{{ csrf_token() }}'
            };
        </script>
        @vite('resources/js/participant/stores/show.js')
    @endpush
@endsection