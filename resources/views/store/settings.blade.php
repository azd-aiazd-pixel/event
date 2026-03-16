@extends('layouts.store')

@section('title', 'Paramètres de la Boutique')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-12 space-y-8">

        <div class="mb-2">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Paramètres de la Boutique</h1>
            <p class="text-slate-500 mt-2">Gérez l'identité, le fonctionnement et le design de votre boutique.</p>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 1 — IDENTITÉ --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">

            {{-- Card Header --}}
            <div class="px-8 pt-8 pb-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Identité</h2>
                    <p class="text-xs text-slate-500">Nom affiché et logo de votre boutique.</p>
                </div>
            </div>

            @if(session('success_identity'))
                <div
                    class="mx-8 mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="font-semibold text-sm">{{ session('success_identity') }}</span>
                </div>
            @endif

            <form action="{{ route('store.settings.update.identity', $store) }}" method="POST" enctype="multipart/form-data"
                class="px-8 pb-8 sm:px-10 sm:pb-10">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- Nom de la boutique --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-900 mb-2">Nom de la boutique</label>
                        <p class="text-xs text-slate-500 mb-3">Ce nom est visible par les participants sur la page de votre
                            boutique.</p>
                        <input type="text" id="name" name="name" value="{{ old('name', $store->name) }}"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition"
                            placeholder="Ex: Buvette principale">
                        @error('name')
                            <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-slate-100">

                    {{-- Logo --}}
                    <div>
                        <label for="logo" class="block text-sm font-bold text-slate-900 mb-2">Logo de la boutique</label>
                        <p class="text-xs text-slate-500 mb-3">Affiché dans l'en-tête de votre boutique (carré recommandé,
                            max 2 Mo).</p>

                        <input type="file" id="logo" name="logo" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">

                        @error('logo')
                            <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror

                        {{-- Logo actuel --}}
                        @if($store->logo)
                            <div class="mt-4 flex items-center gap-4">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 shadow-sm flex-shrink-0 flex items-center justify-center">
                                    <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" alt="Logo actuel"
                                        class="w-full h-full object-cover">
                                </div>
                                <label class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 cursor-pointer">
                                    <input type="checkbox" name="remove_logo" id="remove_logo" value="1"
                                        class="rounded border-slate-300 text-red-500 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <span class="font-bold">Supprimer le logo actuel</span>
                                </label>
                            </div>
                        @else
                            <div class="mt-4">
                                <div id="logo-preview-wrapper"
                                    class="hidden w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                                    <img id="logo-preview-new" src="#" alt="Aperçu" class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-4 bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                        Enregistrer l'identité
                    </button>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 2 — FONCTIONNEMENT --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">

            <div class="px-8 pt-8 pb-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Fonctionnement</h2>
                    <p class="text-xs text-slate-500">Mode de traitement des commandes.</p>
                </div>
            </div>

            @if(session('success_workflow'))
                <div
                    class="mx-8 mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="font-semibold text-sm">{{ session('success_workflow') }}</span>
                </div>
            @endif

            <form action="{{ route('store.settings.update.workflow', $store) }}" method="POST"
                class="px-8 pb-8 sm:px-10 sm:pb-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Option Direct --}}
                    <label for="workflow_direct" class="relative flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all
                                           {{ old('workflow_type', $store->workflow_type) === 'direct'
        ? 'border-slate-900 bg-slate-50'
        : 'border-slate-200 bg-white hover:border-slate-300' }}" id="label_direct">
                        <input type="radio" id="workflow_direct" name="workflow_type" value="direct"
                            class="sr-only workflow-radio" {{ old('workflow_type', $store->workflow_type) === 'direct' ? 'checked' : '' }}>
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0 text-xl">
                            ⚡</div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">Direct</p>
                            <p class="text-xs text-slate-500 mt-0.5">La commande est confirmée immédiatement. Idéal pour les
                                boutiques simples.</p>
                        </div>
                        <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all
                                                {{ old('workflow_type', $store->workflow_type) === 'direct'
        ? 'border-slate-900 bg-slate-900'
        : 'border-slate-300 bg-white' }}" id="dot_direct">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                    </label>

                    {{-- Option Queue --}}
                    <label for="workflow_queue" class="relative flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all
                                           {{ old('workflow_type', $store->workflow_type) === 'queue'
        ? 'border-slate-900 bg-slate-50'
        : 'border-slate-200 bg-white hover:border-slate-300' }}" id="label_queue">
                        <input type="radio" id="workflow_queue" name="workflow_type" value="queue"
                            class="sr-only workflow-radio" {{ old('workflow_type', $store->workflow_type) === 'queue' ? 'checked' : '' }}>
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 text-xl">
                            📋</div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">File d'attente</p>
                            <p class="text-xs text-slate-500 mt-0.5">Les commandes s'accumulent. Le vendeur les traite une
                                par une depuis le terminal.</p>
                        </div>
                        <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all
                                                {{ old('workflow_type', $store->workflow_type) === 'queue'
        ? 'border-slate-900 bg-slate-900'
        : 'border-slate-300 bg-white' }}" id="dot_queue">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                    </label>

                </div>

                @error('workflow_type')
                    <p class="mt-3 text-xs text-red-500 font-semibold">{{ $message }}</p>
                @enderror

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-4 bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                        Enregistrer le fonctionnement
                    </button>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 3 — DESIGN DU THÈME --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">

            <div class="px-8 pt-8 pb-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Design du Thème</h2>
                    <p class="text-xs text-slate-500">Couleurs et images vues par les participants.</p>
                </div>
            </div>

            @if(session('success_theme'))
                <div
                    class="mx-8 mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="font-semibold text-sm">{{ session('success_theme') }}</span>
                </div>
            @endif

            <form action="{{ route('store.settings.update', $store) }}" method="POST" enctype="multipart/form-data"
                class="px-8 pb-8 sm:px-10 sm:pb-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    {{-- Colonne gauche : contrôles --}}
                    <div class="space-y-6">
                        <div>
                            <label for="theme_primary_color" class="block text-sm font-bold text-slate-900 mb-2">Couleur
                                Primaire</label>
                            <p class="text-xs text-slate-500 mb-3">Utilisée pour les boutons d'action (ex: Ajouter au
                                panier) et les éléments mis en avant.</p>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-200 shadow-sm flex-shrink-0 relative cursor-pointer">
                                    <input type="color" id="theme_primary_color" name="theme_primary_color"
                                        value="{{ old('theme_primary_color', $store->theme_primary_color ?? '#18181b') }}"
                                        class="absolute -inset-4 w-24 h-24 cursor-pointer">
                                </div>
                                <code
                                    class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 font-mono color-hex-display">
                                                {{ old('theme_primary_color', $store->theme_primary_color ?? '#18181b') }}
                                            </code>
                            </div>
                            @error('theme_primary_color')
                                <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-slate-100">

                        <div>
                            <label for="theme_bg_color" class="block text-sm font-bold text-slate-900 mb-2">Couleur de
                                l'en-tête</label>
                            <p class="text-xs text-slate-500 mb-3">La couleur d'arrière-plan affichée tout en haut de votre
                                boutique.</p>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-200 shadow-sm flex-shrink-0 relative cursor-pointer">
                                    <input type="color" id="theme_bg_color" name="theme_bg_color"
                                        value="{{ old('theme_bg_color', $store->theme_bg_color ?? '#ffffff') }}"
                                        class="absolute -inset-4 w-24 h-24 cursor-pointer">
                                </div>
                                <code
                                    class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 font-mono color-hex-display">
                                                {{ old('theme_bg_color', $store->theme_bg_color ?? '#ffffff') }}
                                            </code>
                            </div>
                            @error('theme_bg_color')
                                <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-slate-100">

                        <div>
                            <label for="theme_text_color" class="block text-sm font-bold text-slate-900 mb-2">Couleur du
                                Texte (En-tête)</label>
                            <p class="text-xs text-slate-500 mb-3">Couleur du nom de la boutique. Choisissez une couleur
                                lisible sur votre fond.</p>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-200 shadow-sm flex-shrink-0 relative cursor-pointer">
                                    <input type="color" id="theme_text_color" name="theme_text_color"
                                        value="{{ old('theme_text_color', $store->theme_text_color ?? '#18181b') }}"
                                        class="absolute -inset-4 w-24 h-24 cursor-pointer">
                                </div>
                                <code
                                    class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 font-mono color-hex-display">
                                                {{ old('theme_text_color', $store->theme_text_color ?? '#18181b') }}
                                            </code>
                            </div>
                            @error('theme_text_color')
                                <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-slate-100">

                        <div>
                            <label for="theme_bg_image" class="block text-sm font-bold text-slate-900 mb-2">Image de Fond
                                (En-tête, optionnelle)</label>
                            <p class="text-xs text-slate-500 mb-3">Remplace la couleur de l'en-tête par une image (ratio
                                conseillé 16:9, max 2MB).</p>
                            <input type="file" id="theme_bg_image" name="theme_bg_image" accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">
                            @error('theme_bg_image')
                                <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                            @if($store->theme_bg_image)
                                <div class="mt-4 flex items-center gap-4">
                                    <div
                                        class="w-16 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                                        <img src="{{ asset('storage/' . $store->theme_bg_image) }}" alt="Image de fond actuelle"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <label
                                        class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 cursor-pointer">
                                        <input type="checkbox" name="remove_bg_image" id="remove_bg_image" value="1"
                                            class="rounded border-slate-300 text-red-500 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <span class="font-bold">Supprimer l'image actuelle</span>
                                    </label>
                                </div>
                            @endif
                        </div>

                        <hr class="border-slate-100">

                        <div>
                            <label for="theme_body_bg_image" class="block text-sm font-bold text-slate-900 mb-2">Image de
                                Fond (Boutique entière)</label>
                            <p class="text-xs text-slate-500 mb-3">Ajoute une image d'arrière-plan sur toute la page de la
                                boutique.</p>
                            <input type="file" id="theme_body_bg_image" name="theme_body_bg_image" accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">
                            @error('theme_body_bg_image')
                                <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                            @if($store->theme_body_bg_image)
                                <div class="mt-4 flex items-center gap-4">
                                    <div
                                        class="w-16 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                                        <img src="{{ asset('storage/' . $store->theme_body_bg_image) }}"
                                            alt="Image globale actuelle" class="w-full h-full object-cover">
                                    </div>
                                    <label
                                        class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 cursor-pointer">
                                        <input type="checkbox" name="remove_body_bg_image" id="remove_body_bg_image" value="1"
                                            class="rounded border-slate-300 text-red-500 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <span class="font-bold">Supprimer l'image actuelle</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Colonne droite : aperçu --}}
                    <div
                        class="bg-slate-50 w-full h-fit rounded-[2rem] p-4 lg:p-6 border border-slate-200 flex flex-col transition-all duration-300">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Aperçu en direct</h3>

                        <div class="flex-grow flex justify-center items-center py-2">
                            <div id="preview-body"
                                class="w-full max-w-[280px] h-[580px] bg-[#F8F9FA] bg-cover bg-center rounded-[2.5rem] overflow-hidden shadow-xl border-8 border-white transition-all duration-300 flex flex-col relative"
                                @if(!old('remove_body_bg_image') && $store->theme_body_bg_image)
                                    style="background-image: url('{{ asset('storage/' . $store->theme_body_bg_image) }}');"
                                @endif>

                                {{-- Mock Header --}}
                                <div id="preview-header" class="px-6 py-6 transition-all duration-300 bg-cover bg-center"
                                    style="background-color: {{ old('theme_bg_color', $store->theme_bg_color ?? '#ffffff') }}; @if(!old('remove_bg_image') && $store->theme_bg_image) background-image: url('{{ asset('storage/' . $store->theme_bg_image) }}'); @endif">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-black/10 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            @if($store->logo)
                                                <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xl">🏪</span>
                                            @endif
                                        </div>
                                        <h2 id="preview-title"
                                            class="text-xl font-extrabold tracking-tight transition-colors duration-300"
                                            style="color: {{ old('theme_text_color', $store->theme_text_color ?? '#18181b') }};">
                                            {{ $store->name }}
                                        </h2>
                                    </div>
                                </div>

                                {{-- Mock Content --}}
                                <div class="p-6 flex-grow flex flex-col gap-4">
                                    <div class="flex gap-2">
                                        <div class="w-20 h-6 rounded-full text-[10px] font-bold text-white flex items-center justify-center transition-colors duration-300"
                                            id="preview-pill"
                                            style="background-color: {{ old('theme_primary_color', $store->theme_primary_color ?? '#18181b') }};">
                                            Tous</div>
                                        <div
                                            class="w-20 h-6 border border-slate-200 rounded-full text-[10px] bg-white text-slate-400 flex items-center justify-center">
                                            Boissons</div>
                                    </div>

                                    <div
                                        class="mt-4 bg-white/95 backdrop-blur rounded-2xl p-3 shadow-sm border border-zinc-100 flex gap-4 items-center flex-shrink-0">
                                        <div
                                            class="w-14 h-14 rounded-xl bg-zinc-50 flex-shrink-0 border border-zinc-100 flex items-center justify-center text-2xl">
                                            🍔</div>
                                        <div class="flex-grow py-1">
                                            <div class="h-3 w-24 bg-zinc-200 rounded mb-3"></div>
                                            <div class="flex items-center justify-between">
                                                <div class="h-4 w-12 bg-zinc-800 rounded"></div>
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white transition-colors duration-300 shadow-sm"
                                                    id="preview-btn"
                                                    style="background-color: {{ old('theme_primary_color', $store->theme_primary_color ?? '#18181b') }};">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Mock Sticky Cart --}}
                                <div class="px-5 py-4 w-full flex justify-between items-center transition-colors duration-300 mt-auto rounded-t-3xl"
                                    id="preview-cart"
                                    style="background-color: {{ old('theme_primary_color', $store->theme_primary_color ?? '#18181b') }};">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] uppercase font-bold text-white/70">Panier en cours</span>
                                        <span class="text-sm font-extrabold text-white">1 art • 50 Pts</span>
                                    </div>
                                    <div class="bg-white px-4 py-2 rounded-full text-[10px] font-bold text-slate-900">Panier
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit"
                        class="px-8 py-4 bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
                        Enregistrer le Thème
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        window.StoreSettingsConfig = {
            hasThemeBgImage: @json((bool) $store->theme_bg_image),
            themeBgImageUrl: @json($store->theme_bg_image ? asset('storage/' . $store->theme_bg_image) : ''),
            hasThemeBodyBgImage: @json((bool) $store->theme_body_bg_image),
            themeBodyBgImageUrl: @json($store->theme_body_bg_image ? asset('storage/' . $store->theme_body_bg_image) : '')
        };
    </script>
    @vite('resources/js/store/settings.js')
@endsection