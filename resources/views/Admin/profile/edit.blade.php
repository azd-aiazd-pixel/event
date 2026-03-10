@extends('layouts.admin')

@section('header')
<div class="max-w-3xl mx-auto mb-4">
    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Mon Profil</h1>
    <p class="text-sm font-medium text-gray-500 mt-1">Gérez vos informations personnelles et la sécurité de votre compte.</p>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

  
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-50">
            <h2 class="text-lg font-black text-gray-900">Informations Personnelles</h2>
            <p class="text-xs text-gray-500 mt-1">Mettez à jour votre nom et votre adresse e-mail.</p>
        </div>

        <form action="{{ route('admin.profile.update.info') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="p-6 sm:p-8 space-y-6">
              
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nom complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-black focus:border-black block p-3 transition-colors @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ Email --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Adresse E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-black focus:border-black block p-3 transition-colors @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 sm:px-8 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl text-sm font-black hover:bg-gray-800 transition-colors shadow-sm">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    {{-- Carte 2 : Sécurité (Mot de passe) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-50">
            <h2 class="text-lg font-black text-gray-900">Sécurité du compte</h2>
            <p class="text-xs text-gray-500 mt-1">Assurez-vous d'utiliser un mot de passe long et aléatoire pour rester en sécurité.</p>
        </div>

        <form action="{{ route('admin.profile.update.password') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Mot de passe actuel --}}
                <div>
                    <label for="current_password" class="block text-sm font-bold text-gray-700 mb-2">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-black focus:border-black block p-3 transition-colors @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Nouveau mot de passe --}}
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-black focus:border-black block p-3 transition-colors @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmation du nouveau mot de passe --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-black focus:border-black block p-3 transition-colors">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 sm:px-8 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl text-sm font-black hover:bg-gray-800 transition-colors shadow-sm">
                    Mettre à jour le mot de passe
                </button>
            </div>
        </form>
    </div>

</div>
@endsection