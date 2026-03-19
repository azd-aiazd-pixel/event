@extends('layouts.participant')

@section('title', 'Mes Favoris')

@section('content')
<div class="max-w-md mx-auto w-full pb-8">
    <h1 class="text-2xl font-extrabold text-zinc-900 tracking-tight mb-6">Mes Favoris</h1>

    @if($groupedWishlist->isEmpty())
        <div class="bg-white rounded-[2rem] p-12 text-center border border-zinc-100 shadow-sm">
            <div class="text-5xl mb-4 text-zinc-200 text-center flex justify-center">❤️</div>
            <h3 class="text-lg font-bold text-zinc-800 italic">Votre liste est vide</h3>
            <p class="text-zinc-500 text-sm mt-2 leading-relaxed">Parcourez les boutiques pour ajouter des articles à vos favoris.</p>
            <a href="{{ route('participant.stores.index') }}" class="mt-6 inline-block bg-zinc-900 text-white px-6 py-3 rounded-2xl font-bold active:scale-95 transition-all">
                Découvrir les stands
            </a>
        </div>
    @else
        <div class="space-y-8">
            @foreach($groupedWishlist as $storeName => $products)
                <div class="space-y-3">
                    <div class="flex items-center gap-2 px-1">
                        <span class="text-xl">🏪</span>
                        <h2 class="text-lg font-black text-zinc-900 uppercase tracking-tight">{{ $storeName }}</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        @foreach($products as $product)
                            <div class="bg-white rounded-2xl p-3 shadow-sm border border-zinc-100 flex items-center gap-4 relative">
                                
                                <div class="w-16 h-16 rounded-xl bg-zinc-50 flex-shrink-0 overflow-hidden border border-zinc-100">
                                    @if($product->picture)
                                        <img src="{{ asset('storage/' . $product->picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-zinc-300">🖼️</div>
                                    @endif
                                </div>

                                <div class="flex-grow">
                                    <h4 class="font-bold text-zinc-900 leading-tight">{{ $product->name }}</h4>
                                    <p class="text-zinc-500 font-extrabold text-sm mt-1">{{ rtrim(rtrim($product->unit_price, '0'), '.') }} Pts</p>
                                </div>

                                <div class="cart-controls" 
                                     data-store-id="{{ $product->store_id }}" 
                                     data-store-name="{{ $storeName }}" 
                                     data-id="{{ $product->id }}" 
                                     data-name="{{ $product->name }}" 
                                     data-price="{{ $product->unit_price }}">
                                    
                                    <button type="button" class="btn-add-initial bg-zinc-900 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-zinc-800 active:scale-90 transition-all shadow-md shadow-zinc-900/20">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                    </button>

                                    <div class="btn-qty-controls hidden flex items-center bg-zinc-100 rounded-full border border-zinc-200 overflow-hidden h-9">
                                        <button type="button" class="btn-minus w-9 h-full flex items-center justify-center text-zinc-600 hover:bg-zinc-200 active:bg-zinc-300 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" /></svg>
                                        </button>
                                        <span class="qty-display w-6 text-center text-sm font-extrabold text-zinc-900">1</span>
                                        <button type="button" class="btn-plus w-9 h-full flex items-center justify-center text-zinc-600 hover:bg-zinc-200 active:bg-zinc-300 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="stickyCartBar" class="fixed bottom-[76px] left-0 w-full bg-zinc-900 text-white px-5 py-3.5 flex justify-between items-center shadow-[0_-8px_30px_rgba(0,0,0,0.12)] transition-transform duration-300 translate-y-[150%] z-40 rounded-t-3xl border-t border-zinc-800">
    <div class="flex flex-col">
        <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider">Panier en cours</span>
        <span class="font-extrabold text-base mt-0.5"><span id="cartTotalItems">0</span> articles • <span id="cartTotalPrice">0</span> Pts</span>
    </div>
    <a href="{{ route('participant.cart.index') }}" class="bg-white text-zinc-900 px-5 py-2.5 rounded-full text-sm font-extrabold active:scale-95 hover:bg-zinc-100 transition-all shadow-sm text-center">
        Voir le panier
    </a>
</div>

    @vite('resources/js/participant/wishlist/index.js')
@endsection