@extends('layouts.admin')

@section('header')
<div class="pb-6 pt-4 border-b border-gray-100">

    @if($returnToEventId)
        <a href="{{ route('admin.stores.create', $returnToEventId) }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la boutique
        </a>
    @else
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour Dashboard
        </a>
    @endif

    <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase mt-2">
        Nouveau <span class="text-blue-600">Vendeur</span>
    </h1>
</div>
@endsection

@section('content')
<div class="max-w-xl mx-auto mt-8">

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
            <ul class="list-disc list-inside text-xs font-bold text-red-600 uppercase tracking-tight">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-blue-500/5 overflow-hidden relative">
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-10">
            @csrf
            
        
            <input type="hidden" name="return_to_event_id" value="{{ $returnToEventId }}">

            <div class="space-y-6">
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nom Complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-blue-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-300 text-gray-900">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Email (Connexion)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-blue-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-300 text-gray-900">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Mot de passe</label>
                        <input type="password" name="password" required
                               class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-blue-600 focus:bg-white focus:ring-0 transition-all text-gray-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Confirmation</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-bold focus:border-blue-600 focus:bg-white focus:ring-0 transition-all text-gray-900">
                    </div>
                </div>

            </div>

            <button type="submit" class="w-full mt-8 py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-1 transition-all duration-300">
                Créer et Sélectionner
            </button>
        </form>
    </div>
</div>
@endsection