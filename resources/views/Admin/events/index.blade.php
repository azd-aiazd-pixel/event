@extends('layouts.admin')

@section('header')
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end mb-8 pb-6 border-b border-gray-100 gap-4">
    <a href="{{ route('admin.events.create') }}" class="inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-purple-600 transition-all shadow-xl shadow-gray-200 group">
        <span>+ Nouveau Festival</span>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-8">
    

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
        <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col lg:flex-row gap-6 items-end">
            <div class="flex-1 w-full">
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Rechercher</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="NOM DU FESTIVAL..." 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-purple-500/20 uppercase">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                <div class="flex-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest text-center sm:text-left">Début</label>
                    <input type="date" name="date_start" value="{{ request('date_start') }}" 
                           class="w-full bg-gray-50 border-none rounded-2xl text-xs font-bold p-3 cursor-pointer focus:ring-2 focus:ring-purple-500/20">
                </div>
                <div class="hidden sm:flex items-center justify-center pt-6 text-gray-300">➜</div>
                <div class="flex-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest text-center sm:text-left">Fin</label>
                    <input type="date" name="date_end" value="{{ request('date_end') }}" 
                           class="w-full bg-gray-50 border-none rounded-2xl text-xs font-bold p-3 cursor-pointer focus:ring-2 focus:ring-purple-500/20">
                </div>
            </div>

            <button type="submit" class="w-full lg:w-auto px-10 py-3 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-purple-600 transition-all">
                Filtrer
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-50 shadow-2xl shadow-gray-200/50 overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-300 text-[9px] font-black uppercase tracking-[0.3em] border-b border-gray-50">
              
                    <th class="px-4 md:px-8 py-6">Festival</th>
                    
                 
                    <th class="hidden md:table-cell px-8 py-6">Période</th>
                    <th class="hidden md:table-cell px-8 py-6">Statut</th>
                    <th class="hidden md:table-cell px-8 py-6 text-center">ACTIVITÉ</th>
                    
                  
                    <th class="px-4 md:px-8 py-6 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($events as $event)
                <tr class="hover:bg-purple-50/30 transition-all group">
                    
                
                    <td class="px-4 md:px-8 py-5">
                        <div class="flex items-center gap-3">
                         
                            <div class="md:hidden w-2 h-2 rounded-full flex-shrink-0 {{ $event->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                            
                            <div>
                                <span class="text-sm font-black text-gray-900 block group-hover:text-purple-600 transition-colors uppercase">{{ $event->name }}</span>
                                <span class="text-[9px] text-gray-400 font-mono font-bold">#{{ str_pad($event->id, 4, '0', STR_PAD_LEFT) }}</span>
                                
                               
                                <div class="md:hidden mt-1 text-[9px] font-bold text-gray-500">
                                    {{ $event->start_date->format('d/m') }} - {{ $event->end_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </td>

                 
                    <td class="hidden md:table-cell px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-gray-900 tracking-tight">{{ $event->start_date->format('d.m.Y') }}</span>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Fin: {{ $event->end_date->format('d.m.Y') }}</span>
                        </div>
                    </td>

                  
                    <td class="hidden md:table-cell px-8 py-5">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full {{ $event->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]' : 'bg-gray-300' }}"></span>
                            <span class="text-[10px] font-black uppercase {{ $event->is_active ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $event->is_active ? 'Activé' : 'Offline' }}
                            </span>
                        </span>
                    </td>

                 
               <td class="hidden md:table-cell px-8 py-5 text-center">
    <div class="flex items-center justify-center gap-2">
        
        {{-- Badge Participants --}}
        <span class="bg-gray-900 text-white px-3 py-1 rounded-full text-[10px] font-black tracking-tighter" title="Nombre de participants">
            👤 {{ $event->participants_count }}
        </span>

        {{-- Badge Stores Actifs --}}
        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-black tracking-tighter border border-blue-200" title="Boutiques Actives">
            🏪 {{ $event->stores_count }}
        </span>

    </div>
</td>
                    
                   
                    <td class="px-4 md:px-8 py-5 text-right relative overflow-visible">
                        <details class="relative inline-block text-left dropdown-action">
                            <summary class="list-none cursor-pointer w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-900 hover:text-white transition-all outline-none ml-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                            </summary>
                            
                    <div class="absolute right-0 mt-2 w-56 bg-gray-900 text-white rounded-2xl shadow-2xl z-[100] py-2 overflow-hidden border border-white/10 backdrop-blur-md bg-opacity-95 origin-top-right">
    <div class="px-4 py-2 text-[8px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1 text-left">Navigation</div>
    
    <a href="{{ route('admin.participants.index', $event->id) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider hover:bg-purple-600 transition-colors text-left">
        <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Gérer Participants
    </a>

    <a href="{{ route('admin.stores.index', $event->id) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider hover:bg-blue-600 transition-colors text-left">
        <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        Gérer Stores
    </a>

    <a href="{{ route('admin.events.dashboard', $event) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider hover:bg-white hover:text-gray-900 transition-colors text-left">
        <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Stats
    </a>

    <div class="border-t border-white/10 my-1"></div>

    <a href="{{ route('admin.events.edit', $event) }}" class="flex items-center px-4 py-3 text-[10px] font-black uppercase tracking-wider text-gray-400 hover:text-white transition-colors text-left">
        <svg class="w-4 h-4 mr-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Éditer
    </a>
</div>
                        </details>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-20 text-center font-black text-gray-200 uppercase tracking-widest text-xs italic">Aucun festival trouvé</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($events->hasPages())
            <div class="p-6 border-t border-gray-50">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    summary::-webkit-details-marker { display: none; }
    summary { list-style: none; outline: none; }
    .overflow-visible { overflow: visible !important; }
</style>
@endsection