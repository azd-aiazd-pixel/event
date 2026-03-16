@extends('layouts.admin')

@section('header')
<div class="pb-6 pt-4 border-b border-gray-100">
    
    <a href="{{ route('admin.participants.index', $event->id) }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-purple-600 transition-colors">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à la liste
    </a>

    <div class="flex items-baseline gap-3">
        <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase">
            Modifier <span class="text-purple-600">Participant</span>
        </h1>
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">| Festival : {{ $event->name }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto mt-8">


    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
            <ul class="list-disc list-inside text-xs font-bold text-red-600 uppercase tracking-tight">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-purple-500/5 overflow-hidden relative">
        

        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none"></div>

        <form action="{{ route('admin.participants.update', [$event->id, $participant->id]) }}" method="POST" class="p-10 relative">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 mb-8">
                
              
                <div class="p-5 bg-gray-50/50 rounded-2xl border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-gray-200">
                        {{ substr($participant->user->name, 0, 2) }}
                    </div>
                    <div>
                        <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest">Compte Lié</label>
                        <p class="text-sm font-black text-gray-900">{{ $participant->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <label for="nfc_code" class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Code NFC (Bracelet)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-purple-500 group-focus-within:text-purple-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" stroke-width="2"/></svg>
                        </div>
                        <input type="text" name="nfc_code" id="nfc_code" 
                               value="{{ old('nfc_code', $participant->nfc_code) }}"
                               placeholder="Scanner ou saisir le code..."
                               class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-purple-600 focus:bg-white focus:ring-0 transition-all uppercase font-mono tracking-widest placeholder-gray-300">
                    </div>
                </div>

         
                <div class="space-y-3">
                    <label for="balance" class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Solde Actuel</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-emerald-500 group-focus-within:text-emerald-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                        </div>
                        <input type="number" step="0.01" name="balance" id="balance" 
                               value="{{ old('balance', $participant->balance) }}"
                               class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-lg font-black focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all text-gray-900">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-xs font-black text-gray-400 uppercase">
                            pts
                        </div>
                    </div>
                </div>

            </div>

          
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.participants.index', $event->id) }}" class="w-1/3 py-5 text-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="w-2/3 py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-purple-600 hover:shadow-lg hover:shadow-purple-200 hover:-translate-y-1 transition-all duration-300">
                    Sauvegarder
                </button>
            </div>

        </form>
    </div>
</div>
@endsection