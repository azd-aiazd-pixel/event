@extends('layouts.admin')

@section('header')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-100">
    <div>
        
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 mb-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-purple-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour aux festivals
        </a>

        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">
                Gestion <span class="text-purple-600">Participants</span>
            </h1>
            <span class="px-2 py-1 rounded bg-gray-100 text-[10px] font-black uppercase text-gray-500 tracking-widest hidden sm:inline-block">{{ $event->name }}</span>
        </div>
    </div>

   
    <a href="{{ route('admin.participants.create', $event->id) }}" 
       class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-purple-600 transition-all shadow-xl shadow-gray-200 hover:shadow-purple-100 group w-full md:w-auto">
        <span>Ajouter un Participant</span>
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
        <form action="{{ route('admin.participants.index', $event->id) }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="RECHERCHER PAR EMAIL OU CODE NFC..." 
                       class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-gray-50 rounded-2xl text-xs font-bold tracking-widest focus:border-purple-600 focus:ring-0 transition-all placeholder-gray-300 uppercase">
            </div>

            <button type="submit" class="px-8 py-4 bg-gray-100 text-gray-900 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-gray-200 transition-all w-full md:w-auto">
                Rechercher
            </button>
        </form>
    </div>

   
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($participants as $participant)
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm relative">
                
                {{-- Menu Dropdown (Positionné en haut à droite de la carte) --}}
                <div class="absolute top-4 right-4 z-10">
                    <details class="relative inline-block text-left dropdown-action">
                        <summary class="list-none cursor-pointer w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </summary>
                        {{-- Le contenu du menu est identique à celui du PC --}}
                        <div class="absolute right-0 mt-2 w-56 bg-gray-900 text-white rounded-2xl shadow-2xl z-50 py-2 overflow-hidden border border-white/10 backdrop-blur-md bg-opacity-95 origin-top-right">
                            <div class="px-4 py-2 text-[8px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">Actions</div>
                            <a href="{{ route('admin.participants.edit', [$event->id, $participant->id]) }}" class="flex items-center px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-white hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Modifier
                            </a>
                            <div class="border-t border-white/10 my-2"></div>
                            <form action="{{ route('admin.participants.destroy', [$event->id, $participant->id]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Supprimer ?')" class="w-full text-left flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-wider text-red-400 hover:bg-red-600 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </details>
                </div>

       
                <div class="flex items-center gap-4 mb-6 pr-10">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-gray-200">
                        {{ substr($participant->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 truncate max-w-[180px]">{{ $participant->user->email }}</h3>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mt-1">ID: #{{ $participant->id }}</p>
                    </div>
                </div>

             
                <div class="grid grid-cols-2 gap-4 border-t border-gray-50 pt-4">
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Bracelet NFC</p>
                        @if($participant->nfc_code)
                            <p class="text-xs font-mono font-black text-purple-600 bg-purple-50 inline-block px-2 py-1 rounded-lg">{{ $participant->nfc_code }}</p>
                        @else
                            <p class="text-[10px] font-bold text-gray-300 italic uppercase">Aucun</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Solde</p>
                        <p class="text-lg font-black text-gray-900">{{ number_format($participant->balance, 2) }} <span class="text-[10px] text-gray-400">pts</span></p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-10 rounded-[2rem] text-center font-black text-gray-300 uppercase text-xs">Aucun participant trouvé</div>
        @endforelse
    </div>


    <div class="hidden md:block bg-white rounded-[2rem] border border-gray-50 shadow-2xl shadow-gray-200/50 overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-300 text-[9px] font-black uppercase tracking-[0.3em] border-b border-gray-50">
                    <th class="px-8 py-6">Email / Compte</th>
                    <th class="px-8 py-6">Code NFC</th>
                    <th class="px-8 py-6">Solde</th>
                    <th class="px-8 py-6 text-right">Settings</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($participants as $participant)
                <tr class="hover:bg-purple-50/30 transition-all group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-[10px] font-black text-white">
                                {{ substr($participant->user->name, 0, 2) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-gray-900 tracking-tight">{{ $participant->user->email }}</span>
                                <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">ID: #{{ $participant->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        @if($participant->nfc_code)
                            <span class="text-sm font-mono font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-md">{{ $participant->nfc_code }}</span>
                        @else
                            <span class="text-[10px] font-bold text-gray-300 italic uppercase">Aucun Bracelet</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-sm font-black text-gray-900">
                            {{ number_format($participant->balance, 2) }} <span class="text-[10px] text-gray-400 uppercase">pts</span>
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right relative overflow-visible">
                     
                        <details class="relative inline-block text-left dropdown-action">
                            <summary class="list-none cursor-pointer w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-900 hover:text-white transition-all outline-none ml-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                            </summary>
                            <div class="absolute right-0 mt-3 w-60 bg-gray-900 text-white rounded-2xl shadow-2xl z-[100] py-3 overflow-hidden border border-white/10 backdrop-blur-md bg-opacity-95 origin-top-right">
                                <div class="px-4 py-2 text-[8px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">Actions</div>
                                <a href="{{ route('admin.participants.edit', [$event->id, $participant->id]) }}" class="flex items-center px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-white hover:text-gray-900 transition-colors">
                                    <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Modifier Infos
                                </a>
                                <div class="border-t border-white/10 my-2"></div>
                                <form action="{{ route('admin.participants.destroy', [$event->id, $participant->id]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Supprimer ce participant et son compte ?')" class="w-full text-left flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-wider text-red-400 hover:bg-red-600 hover:text-white transition-colors">
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

    @if($participants->hasPages())
    <div class="px-4">
        {{ $participants->links() }}
    </div>
    @endif
</div>

<style>
    summary::-webkit-details-marker { display: none; }
    summary { list-style: none; outline: none; }
    details[open] summary { background: #111827; color: white; }
</style>
@endsection