@extends('layouts.admin')

@section('header')
<div class="pb-6 pt-4 border-b border-gray-100">
    <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-purple-600 transition-colors">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à la liste
    </a>
    <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase mt-2">
        Nouveau <span class="text-purple-600">Festival</span>
    </h1>
</div>
@endsection

@section('content')
<div class="max-w-xl mx-auto mt-8">

    {{-- Gestion des erreurs --}}
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
        
        <form action="{{ route('admin.events.store') }}" method="POST" class="p-10">
            @csrf
            
            <div class="space-y-6">
                
                {{-- CHAMP 1 : NOM --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">nom de l’événement</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="nom de l’événement"
                           class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-purple-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-300 text-gray-900 uppercase tracking-wider">
                </div>

                {{-- CHAMP 2 & 3 : DATES (Grid) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Date de Début</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required
                               class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-purple-600 focus:bg-white focus:ring-0 transition-all text-gray-900 cursor-pointer">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Date de Fin</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                               class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-purple-600 focus:bg-white focus:ring-0 transition-all text-gray-900 cursor-pointer">
                    </div>
                </div>

            </div>

            <button type="submit" class="w-full mt-8 py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-purple-600 hover:shadow-lg hover:shadow-purple-200 hover:-translate-y-1 transition-all duration-300">
                Créer l'événement
            </button>
        </form>
    </div>
</div>
@endsection