@extends('layouts.admin')

@section('header')
<div class="pb-6 pt-4 border-b border-gray-100">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-purple-600 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
            <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase mt-2">
                Éditer <span class="text-purple-600">{{ $event->name }}</span>
            </h1>
        </div>
        
        {{-- Badge Statut Actuel --}}
        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $event->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
            {{ $event->is_active ? 'En ligne' : 'Hors ligne' }}
        </span>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-xl mx-auto mt-8 space-y-8">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
           
            <ul class="list-disc list-inside text-xs font-bold text-red-600 uppercase tracking-tight">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- FORMULAIRE D'ÉDITION --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-purple-500/5 overflow-hidden relative">
        
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="p-10">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                
                {{-- CHAMP 1 : NOM --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nom du Festival</label>
                    <input type="text" name="name" value="{{ old('name', $event->name) }}" required
                           class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-purple-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-300 text-gray-900 uppercase tracking-wider">
                </div>

                {{-- CHAMP 2 : STATUT (is_active) --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Statut du Festival</label>
                    <div class="relative">
                        <select name="is_active" class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-purple-600 focus:bg-white focus:ring-0 transition-all text-gray-900 appearance-none cursor-pointer">
                            {{-- Value 1 = TRUE, Value 0 = FALSE --}}
                            <option value="1" {{ old('is_active', $event->is_active) == 1 ? 'selected' : '' }}>🟢 ACTIF (Visible)</option>
                            <option value="0" {{ old('is_active', $event->is_active) == 0 ? 'selected' : '' }}>🔴 INACTIF (Caché)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-6 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- CHAMP 3 & 4 : DATES --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Date de Début</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required
                               class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-purple-600 focus:bg-white focus:ring-0 transition-all text-gray-900 cursor-pointer">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Date de Fin</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}" required
                               class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-purple-600 focus:bg-white focus:ring-0 transition-all text-gray-900 cursor-pointer">
                    </div>
                </div>

            </div>

            <button type="submit" class="w-full mt-8 py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-purple-600 hover:shadow-lg hover:shadow-purple-200 hover:-translate-y-1 transition-all duration-300">
                Sauvegarder les modifications
            </button>
        </form>
    </div>

    {{-- ZONE DE DANGER (SUPPRESSION) --}}
    <div class="border-t-2 border-dashed border-gray-200 pt-8">
        <div class="bg-red-50 rounded-[2rem] p-8 flex flex-col sm:flex-row items-center justify-between gap-6 border border-red-100">
            <div>
                <h3 class="text-red-900 font-black uppercase tracking-wide text-sm">Zone de Danger</h3>
                <p class="text-red-600/70 text-[10px] font-bold mt-1">
                    archive les participants et stores et l'event
                </p>
            </div>
            
            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce festival ? Cette action est irréversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-white border-2 border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm">
                    Supprimer le festival
                </button>
            </form>
        </div>
    </div>

</div>
@endsection