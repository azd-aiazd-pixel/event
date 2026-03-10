@extends('layouts.store')

@section('title', 'Mon Profil')

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-12 space-y-8">

        <div class="mb-2">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mon Profil</h1>
            <p class="text-slate-500 mt-2">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>

        {{-- Alerte succès --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Carte 1 : Informations personnelles --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 pt-8 pb-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Informations Personnelles</h2>
                    <p class="text-xs text-slate-500">Mettez à jour votre nom et votre adresse e-mail.</p>
                </div>
            </div>

            <form action="{{ route('store.profile.update.info', $store) }}" method="POST" class="px-8 pb-8 sm:px-10 sm:pb-10">
                @csrf
                @method('PATCH')
                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-900 mb-2">Nom complet</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-900 mb-2">Adresse E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-4 bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        {{-- Carte 2 : Sécurité --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 pt-8 pb-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Sécurité du compte</h2>
                    <p class="text-xs text-slate-500">Utilisez un mot de passe long et aléatoire.</p>
                </div>
            </div>

            <form action="{{ route('store.profile.update.password', $store) }}" method="POST" class="px-8 pb-8 sm:px-10 sm:pb-10">
                @csrf
                @method('PATCH')
                <div class="space-y-5">
                    <div>
                        <label for="current_password" class="block text-sm font-bold text-slate-900 mb-2">Mot de passe
                            actuel</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('current_password') border-red-400 @enderror">
                        @error('current_password')
                            <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-900 mb-2">Nouveau mot de
                                passe</label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('password') border-red-400 @enderror">
                            @error('password')
                                <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-900 mb-2">Confirmer
                                le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition">
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-4 bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                        Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection