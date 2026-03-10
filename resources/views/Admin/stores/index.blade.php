@extends('layouts.admin')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-100">
    <div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 mb-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour aux festivals
        </a>

        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">
                Gestion <span class="text-blue-600">Stores</span>
            </h1>
            <span class="px-2 py-1 rounded bg-gray-100 text-[10px] font-black uppercase text-gray-500 tracking-widest hidden sm:inline-block">{{ $event->name }}</span>
        </div>
    </div>

    <a href="{{ route('admin.stores.create', $event->id) }}" 
       class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-blue-600 transition-all shadow-xl shadow-gray-200 hover:shadow-blue-100 group w-full md:w-auto">
        <span>Ajouter un Store</span>
        <svg class="w-4 h-4 ml-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-8 mt-8">

    @if(session('success'))
        <div class="p-4 bg-gray-900 text-white rounded-2xl flex items-center gap-4 shadow-lg border-l-4 border-emerald-500">
            <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white text-xs">✓</div>
            <p class="text-xs font-bold tracking-tight uppercase tracking-wider">{!! session('success') !!}</p>
        </div>
    @endif

    <div class="relative group">
        <form action="{{ route('admin.stores.index', $event->id) }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="RECHERCHER PAR NOM DE BOUTIQUE OU email ou nom user..." 
                       class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-gray-50 rounded-2xl text-xs font-bold tracking-widest focus:border-blue-600 focus:ring-0 transition-all placeholder-gray-300 uppercase">
            </div>

            <button type="submit" class="px-8 py-4 bg-gray-100 text-gray-900 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-gray-200 transition-all w-full md:w-auto">
                Rechercher
            </button>
        </form>
    </div>

    {{-- VUE MOBILE  --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($stores as $store)
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm relative">
                
                {{-- Dropdown Mobile --}}
                <div class="absolute top-4 right-4 z-10">
                    <details class="relative inline-block text-left dropdown-action">
                        <summary class="list-none cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </summary>
                        <div class="absolute right-0 mt-2 w-56 bg-gray-900 text-white rounded-2xl shadow-2xl z-50 py-2 overflow-hidden border border-white/10 backdrop-blur-md bg-opacity-95 origin-top-right">
                            <div class="px-4 py-2 text-[8px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">Actions</div>
                            <a href="{{ route('admin.stores.edit', [$event->id, $store->id]) }}" class="flex items-center px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-white hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Modifier
                            </a>
                            <a href="{{ route('admin.stores.products.index', $store->id) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider hover:bg-blue-600 transition-colors group">
    <svg class="w-4 h-4 mr-3 text-blue-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
    </svg>
    Gérer Catalogue
</a>
                            <div class="border-t border-white/10 my-2"></div>
                            <form action="{{ route('admin.stores.destroy', [$event->id, $store->id]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Supprimer cette boutique ?')" class="w-full text-left flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-wider text-red-400 hover:bg-red-600 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </details>
                </div>

                {{-- Contenu Mobile --}}
                <div class="flex items-center gap-4 mb-6 pr-10">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100">
                        @if($store->logo)
                            <img src="{{ Storage::url($store->logo) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 truncate max-w-[180px]">{{ $store->name }}</h3>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mt-1">Gérant: {{ substr($store->user->name, 0, 10) }}...</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t border-gray-50 pt-4">
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Contact</p>
                        <p class="text-[10px] font-bold text-gray-900">{{ $store->user->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Statut</p>
                        @if($store->status === 'active')
                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase">Actif</span>
                        @else
                            <span class="text-[10px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-10 rounded-[2rem] text-center font-black text-gray-300 uppercase text-xs">Aucune boutique trouvée</div>
        @endforelse
    </div>

    {{-- VUE DESKTOP  --}}
    <div class="hidden md:block bg-white rounded-[2rem] border border-gray-50 shadow-2xl shadow-gray-200/50 overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-300 text-[9px] font-black uppercase tracking-[0.3em] border-b border-gray-50">
                    <th class="px-8 py-6">Boutique</th>
                    <th class="px-8 py-6">Vendeur Responsable</th>
                    <th class="px-8 py-6">Statut</th>
                    <th class="px-8 py-6 text-right">Settings</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($stores as $store)
                <tr class="hover:bg-blue-50/30 transition-all group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden">
                                @if($store->logo)
                                    <img src="{{ Storage::url($store->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                @endif
                            </div>
                            <span class="text-xs font-black text-gray-900 tracking-tight">{{ $store->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-900">{{ $store->user->name }}</span>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $store->user->email }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        @if($store->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-500 border border-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right relative overflow-visible">
                        <details class="relative inline-block text-left dropdown-action">
                            <summary class="list-none cursor-pointer w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-900 hover:text-white transition-all outline-none ml-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                            </summary>
                            <div class="absolute right-0 mt-3 w-60 bg-gray-900 text-white rounded-2xl shadow-2xl z-[100] py-3 overflow-hidden border border-white/10 backdrop-blur-md bg-opacity-95 origin-top-right">
                                <div class="px-4 py-2 text-[8px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">Actions</div>
                                <a href="{{ route('admin.stores.edit', [$event->id, $store->id]) }}" class="flex items-center px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-white hover:text-gray-900 transition-colors">
                                    <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Modifier Boutique
                                </a>
                                <a href="{{ route('admin.stores.products.index', $store->id) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider hover:bg-blue-600 transition-colors group">
    <svg class="w-4 h-4 mr-3 text-blue-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
    </svg>
    Gérer Catalogue
</a>

<a href="{{ route('admin.stores.dashboard', [$event->id, $store->id]) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider hover:bg-emerald-600 transition-colors group">
    <svg class="w-4 h-4 mr-3 text-emerald-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
    </svg>
   Dashboard
</a>



                                <div class="border-t border-white/10 my-2"></div>
                                <form action="{{ route('admin.stores.destroy', [$event->id, $store->id]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Supprimer cette boutique et tout son historique ?')" class="w-full text-left flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-wider text-red-400 hover:bg-red-600 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </details>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($stores->hasPages())
    <div class="px-4">
        {{ $stores->links() }}
    </div>
    @endif
</div>

<style>
    summary::-webkit-details-marker { display: none; }
    summary { list-style: none; outline: none; }
    details[open] summary { background: #111827; color: white; }
</style>
@endsection