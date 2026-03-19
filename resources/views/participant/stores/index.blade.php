@extends('layouts.participant')

@section('title', 'Boutiques de l\'événement')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-zinc-900 tracking-tight">Boutiques & Stands</h1>
        
        <div class="mt-4 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-3 border border-zinc-200 rounded-xl leading-5 bg-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all shadow-sm" placeholder="Rechercher un stand, un repas...">
        </div>
    </div>

    @if($stores->isEmpty())
        <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-zinc-100 mt-10">
            <div class="text-zinc-300 text-6xl mb-4">🏪</div>
            <h3 class="text-lg font-bold text-zinc-800">Aucune boutique ouverte</h3>
            <p class="text-zinc-500 text-sm mt-2">Revenez un peu plus tard !</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="storesGrid">
            @foreach($stores as $store)
                <a href="{{ route('participant.stores.show', $store->id) }}" class="store-card bg-white rounded-2xl p-4 shadow-sm border border-zinc-100 flex items-center gap-4 hover:shadow-md hover:border-zinc-200 active:scale-[0.98] transition-all duration-200" data-name="{{ strtolower($store->name) }}">
                    
                    <div class="w-14 h-14 rounded-full bg-zinc-50 overflow-hidden flex-shrink-0 border border-zinc-100 flex items-center justify-center">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-zinc-400 text-xl">🏪</span>
                        @endif
                    </div>

                    <div class="flex-grow flex items-center">
                        <h3 class="font-bold text-zinc-900 text-lg">{{ $store->name }}</h3>
                    </div>

                    <div class="text-zinc-300 pl-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        <div id="noResults" class="hidden bg-transparent rounded-3xl p-8 text-center mt-6">
            <div class="text-zinc-300 text-4xl mb-3">🔍</div>
            <p class="text-zinc-500 text-sm">Aucun stand ne correspond à votre recherche.</p>
        </div>
    @endif

    @vite('resources/js/participant/stores/index.js')
@endsection