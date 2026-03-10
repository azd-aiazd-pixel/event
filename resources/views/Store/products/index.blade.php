@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase text-slate-900">
                Gestion <span class="text-slate-400">Produits</span>
            </h1>
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mt-1">
                {{ $products->total() }} Références au catalogue
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('store.products.create', $store) }}" class="flex items-center gap-2 px-6 py-3 bg-black text-white rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Nouveau Produit</span>
            </a>
        </div>
    </div>

<form action="{{ URL::current() }}" method="GET" id="filterForm" class="mb-10">
    <input type="hidden" name="category" id="categoryInput" value="{{ request('category') }}">
    <input type="hidden" name="stock" id="stockInput" value="{{ request('stock') }}">

    <div class="flex flex-wrap items-center gap-4">
        
        <div class="flex-grow min-w-[280px] relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher un produit..." 
                   class="w-full pl-12 pr-4 py-4 bg-white border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-black focus:border-black transition-all shadow-sm">
            <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button type="button" @click="open = !open" 
                    class="flex items-center justify-between gap-4 min-w-[200px] px-6 py-4 bg-white border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:border-black transition-all">
                <span>{{ $categories->firstWhere('id', request('category'))->name ?? 'Toutes Catégories' }}</span>
                <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute left-0 mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl z-20 py-2">
                <button type="button" @click="document.getElementById('categoryInput').value = ''; document.getElementById('filterForm').submit()" 
                        class="w-full text-left px-6 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-slate-50 {{ !request('category') ? 'text-purple-600' : 'text-slate-400' }}">
                    Toutes
                </button>
                @foreach($categories as $cat)
                <button type="button" @click="document.getElementById('categoryInput').value = '{{ $cat->id }}'; document.getElementById('filterForm').submit()" 
                        class="w-full text-left px-6 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-slate-50 {{ request('category') == $cat->id ? 'text-purple-600' : 'text-slate-400' }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

     
        @if(request()->anyFilled(['search', 'category', 'stock']))
            <a href="{{ route('store.products.index', $store) }}" class="p-4 bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        @endif
    </div>
</form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="group bg-white border border-slate-100 rounded-[2.5rem] p-4 transition-all duration-300 hover:shadow-[0_30px_60px_rgba(0,0,0,0.05)] hover:-translate-y-1">
                
                <div class="relative aspect-square rounded-[2rem] overflow-hidden bg-slate-50 mb-4 border border-slate-50">
                    @if($product->picture)
                        <img src="{{ asset('storage/' . $product->picture) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-200">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif

                    <div class="absolute top-3 right-3">
                        <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full backdrop-blur-md {{ $product->is_active ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current {{ $product->is_active ? 'animate-pulse' : '' }}"></span>
                            <span class="text-[8px] font-black uppercase tracking-widest">{{ $product->is_active ? 'Actif' : 'Off' }}</span>
                        </span>
                    </div>
                </div>

                <div class="px-2">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[8px] font-black uppercase tracking-[0.2em] text-purple-500 bg-purple-50 px-2 py-0.5 rounded">
                            {{ $product->category->name ?? 'Général' }}
                        </span>
                    </div>
                    
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tighter mb-4 truncate">
                        {{ $product->name }}
                    </h3>

                    <div class="flex items-end justify-between border-t border-slate-50 pt-4">
                        <div>
                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">Prix</p>
                            <p class="text-lg font-black text-slate-900 leading-none">
                                {{ number_format($product->unit_price, 2) }} <span class="text-[10px] text-slate-400 ml-0.5">DH</span>
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">Stock</p>
                            <div class="flex items-center justify-end gap-1">
                                @if(!$product->is_stockable)
                                    <span class="text-[9px] font-black uppercase tracking-widest text-blue-500 bg-blue-50 px-2 py-1 rounded-md">
                                        Illimité
                                    </span>
                                @elseif($product->quantity <= 0)
                                    <span class="text-[9px] font-black uppercase tracking-widest text-red-500 bg-red-50 px-2 py-1 rounded-md">
                                        Rupture
                                    </span>
                                @else
                                    <span class="text-sm font-black {{ $product->quantity <= 5 ? 'text-orange-500' : 'text-slate-900' }}">
                                        {{ $product->quantity }}
                                    </span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $product->unitMeasure->name ?? '' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('store.products.edit', [$store, $product]) }}" class="flex-grow py-3 rounded-xl bg-slate-50 text-slate-400 text-[9px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all text-center">
                        Modifier
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[3.5rem] border-2 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Aucun produit trouvé</p>
                <p class="text-[10px] text-slate-300 mt-1 uppercase">Essayez de modifier vos filtres ou ajoutez un produit</p>
            </div>
        @endforelse
    </div>

    {{-- 4. PAGINATION --}}
    <div class="mt-12">
        {{ $products->links() }}
    </div>

</div>
@endsection