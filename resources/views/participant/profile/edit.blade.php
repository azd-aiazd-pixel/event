@extends('layouts.participant')

@section('title', 'Mon Profil')

@section('content')
    <div class="max-w-lg mx-auto px-4 py-10 pb-28 space-y-6">

        <div class="mb-2">
            <h1 class="text-2xl font-extrabold text-zinc-900 tracking-tight">Mon Profil</h1>
            <p class="text-zinc-500 text-sm mt-1">Gérez vos informations personnelles.</p>
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
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
            <div class="px-6 pt-6 pb-3 border-b border-zinc-50">
                <h2 class="text-base font-extrabold text-zinc-900">Informations Personnelles</h2>
                <p class="text-xs text-zinc-400 mt-0.5">Nom et adresse e-mail de votre compte.</p>
            </div>

            <form action="{{ route('participant.profile.update.info') }}" method="POST" class="px-6 py-6">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label for="name"
                            class="block text-xs font-black text-zinc-700 mb-1.5 uppercase tracking-widest">Nom
                            complet</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email"
                            class="block text-xs font-black text-zinc-700 mb-1.5 uppercase tracking-widest">Adresse
                            E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="w-full py-3.5 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-zinc-700 transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- Carte 2 : Sécurité --}}
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
            <div class="px-6 pt-6 pb-3 border-b border-zinc-50">
                <h2 class="text-base font-extrabold text-zinc-900">Sécurité du compte</h2>
                <p class="text-xs text-zinc-400 mt-0.5">Modifiez votre mot de passe.</p>
            </div>

            <form action="{{ route('participant.profile.update.password') }}" method="POST" class="px-6 py-6">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label for="current_password"
                            class="block text-xs font-black text-zinc-700 mb-1.5 uppercase tracking-widest">Mot de passe
                            actuel</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition @error('current_password') border-red-400 @enderror">
                        @error('current_password')
                            <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password"
                            class="block text-xs font-black text-zinc-700 mb-1.5 uppercase tracking-widest">Nouveau mot de
                            passe</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition @error('password') border-red-400 @enderror">
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="block text-xs font-black text-zinc-700 mb-1.5 uppercase tracking-widest">Confirmer le mot
                            de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent transition">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="w-full py-3.5 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-zinc-700 transition-colors">
                        Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection