@extends('layouts.admin')

@section('header')
<div class="pb-6 pt-4 border-b border-gray-100">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <div>
            <a href="{{ route('admin.stores.index', $store->event_id) }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour aux boutiques
            </a>
            <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase mt-1">
                Catalogue <span class="text-blue-600">{{ $store->name }}</span>
            </h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                {{ $store->event->name }}
            </p>
        </div>

<div class="flex items-center gap-3">
    
    <div class="relative group">
        <button type="button" class="inline-flex items-center px-4 py-3 bg-white border-2 border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:border-blue-300 hover:text-blue-600 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Exporter
        </button>
        
        <div class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform group-hover:translate-y-0 translate-y-2">
            <div class="py-2">
                <a href="{{ route('admin.stores.products.export', ['store' => $store->id, 'type' => 'csv']) }}" class="block px-4 py-2 text-[10px] font-black uppercase text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                    Format .CSV
                </a>
                <a href="{{ route('admin.stores.products.export', ['store' => $store->id, 'type' => 'txt']) }}" class="block px-4 py-2 text-[10px] font-black uppercase text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                    Format .TXT
                </a>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.stores.products.create', $store->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-600 transition-all shadow-xl shadow-gray-200 hover:-translate-y-1">
        <span>+ Ajouter Produit</span>
    </a>
</div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8 mt-6">
  
    <div class="bg-white p-4 rounded-[2rem] border border-gray-100 shadow-sm">
        <form action="{{ route('admin.stores.products.index', $store->id) }}" method="GET" class="flex flex-col md:flex-row gap-4">
            
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="RECHERCHER UN PRODUIT..." 
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-blue-500/20 uppercase tracking-wide placeholder-gray-300">
            </div>

            <div class="relative min-w-[220px]" 
                 x-data="{ 
                    open: false, 
                    selected: '{{ request('sort', 'latest') }}',
                    updateSort(value) {
                        this.selected = value;
                        this.open = false;
                        setTimeout(() => this.$el.closest('form').submit(), 50);
                    }
                 }" 
                 @click.away="open = false">
                 
                <input type="hidden" name="sort" x-model="selected">
                
                <button @click="open = !open" type="button" 
                        class="w-full pl-10 pr-10 py-3 bg-gray-50 hover:bg-gray-100 border-none rounded-2xl text-xs font-bold uppercase tracking-wide transition-colors flex items-center justify-between h-full text-gray-600">
                    
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                    </div>
                    
                    <span class="text-left w-full truncate">
                        <template x-if="selected === 'latest'"><span>Plus récents</span></template>
                        <template x-if="selected === 'price_asc'"><span>Prix croissant</span></template>
                        <template x-if="selected === 'price_desc'"><span>Prix décroissant</span></template>
                        <template x-if="selected === 'stock_asc'"><span>Stock faible</span></template>
                        <template x-if="selected === 'stock_desc'"><span>Stock élevé</span></template>
                        <template x-if="selected === 'name'"><span>Alphabétique</span></template>
                    </span>

                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </button>

                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute right-0 z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden py-2" x-cloak>
                    
                    <button type="button" @click="updateSort('latest')" class="w-full text-left px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide transition-colors flex items-center justify-between" :class="selected === 'latest' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">Plus récents <svg x-show="selected === 'latest'" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                    <button type="button" @click="updateSort('price_asc')" class="w-full text-left px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide transition-colors flex items-center justify-between" :class="selected === 'price_asc' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">Prix croissant <svg x-show="selected === 'price_asc'" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                    <button type="button" @click="updateSort('price_desc')" class="w-full text-left px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide transition-colors flex items-center justify-between" :class="selected === 'price_desc' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">Prix décroissant <svg x-show="selected === 'price_desc'" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                    <button type="button" @click="updateSort('stock_asc')" class="w-full text-left px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide transition-colors flex items-center justify-between" :class="selected === 'stock_asc' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">Stock faible <svg x-show="selected === 'stock_asc'" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                    <button type="button" @click="updateSort('stock_desc')" class="w-full text-left px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide transition-colors flex items-center justify-between" :class="selected === 'stock_desc' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">Stock élevé <svg x-show="selected === 'stock_desc'" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                    <button type="button" @click="updateSort('name')" class="w-full text-left px-4 py-2.5 text-[11px] font-bold uppercase tracking-wide transition-colors flex items-center justify-between" :class="selected === 'name' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">Alphabétique <svg x-show="selected === 'name'" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                </div>
            </div>
        </form>
    </div>

  
    <div class="bg-white rounded-[2rem] border border-gray-50 shadow-2xl shadow-gray-200/50 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-300 text-[9px] font-black uppercase tracking-[0.2em] border-b border-gray-50 bg-gray-50/50">
                    <th class="px-6 py-5">Produit</th>
                    <th class="px-6 py-5">Prix Unit.</th>
                    <th class="px-6 py-5 text-center">Stock</th>
                    <th class="px-6 py-5 text-center">Statut</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    
              
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-100 flex-shrink-0 overflow-hidden relative">
                                @if($product->picture)
                                    <img src="{{ Storage::url($product->picture) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-black text-gray-900 block group-hover:text-blue-600 transition-colors uppercase">{{ $product->name }}</span>
                                <span class="text-[9px] text-gray-400 font-mono">REF: #{{ $product->id }}</span>
                            </div>
                        </div>
                    </td>

                  
                    <td class="px-6 py-4">
                        <span class="font-black text-gray-900 text-sm tracking-tight">{{ number_format($product->unit_price, 2) }} <span class="text-[10px] text-gray-400">DH</span></span>
                    </td>

       
                    <td class="px-6 py-4 text-center">
                        @if(!$product->is_stockable)
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                Illimité
                            </span>
                        @elseif($product->quantity <= 0)
                            <span class="inline-block px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                Rupture
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-gray-50 text-gray-600 border border-gray-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                {{ $product->quantity }} <span class="ml-1 opacity-50">Unités</span>
                            </span>
                        @endif
                    </td>
              
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $product->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                            @if($product->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </div>
                    </td>

                 
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                         
                            <a href="{{ route('admin.stores.products.edit', [$store->id, $product->id]) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-600 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>

                       
                            <form action="{{ route('admin.stores.products.destroy', [$store->id, $product->id]) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:bg-red-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Le catalogue est vide</p>
                            <p class="text-[10px] text-gray-300 mt-1">Commencez par ajouter un produit.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($products->hasPages())
        <div class="p-6 border-t border-gray-50 bg-gray-50/30">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection