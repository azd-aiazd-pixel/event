@extends('layouts.admin')

@section('header')
    <div class="pb-6 pt-4 border-b border-gray-100">
        <a href="{{ route('admin.participants.index', $event->id) }}"
            class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-purple-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>

        <div class="flex items-baseline gap-3">
            <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase">
                Nouveau <span class="text-purple-600">Participant</span>
            </h1>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">| Festival : {{ $event->name }}</p>
        </div>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto mt-8" x-data="{ tab: 'manual', fileName: null }">

        <div class="flex justify-center mb-8">
            <div class="bg-gray-100 p-1.5 rounded-2xl flex items-center shadow-inner">
                <button @click="tab = 'manual'"
                    :class="tab === 'manual' ? 'bg-white text-gray-900 shadow-md transform scale-105' : 'text-gray-400 hover:text-gray-600'"
                    class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 ease-out">
                    Ajout Manuel
                </button>
                <button @click="tab = 'import'"
                    :class="tab === 'import' ? 'bg-white text-gray-900 shadow-md transform scale-105' : 'text-gray-400 hover:text-gray-600'"
                    class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 ease-out">
                    Import Fichier
                </button>
            </div>
        </div>

        <div x-show="tab === 'manual'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-purple-500/5 overflow-hidden relative">

            <div
                class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none">
            </div>

            <form action="{{ route('admin.participants.store', $event->id) }}" method="POST" class="p-10 relative"
                x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf

                @if($errors->any())
                    <div
                        class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-[11px] font-black uppercase tracking-widest">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-8 mb-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Code NFC </label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-purple-500 group-focus-within:text-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                                        stroke-width="2" />
                                </svg>
                            </div>
                            <input type="text" name="nfc_code" placeholder="nfc code..." required autofocus
                                class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-purple-600 focus:bg-white focus:ring-0 transition-all font-mono uppercase tracking-wider placeholder-gray-300">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Solde de
                            départ</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-emerald-500 group-focus-within:text-emerald-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        stroke-width="2" />
                                </svg>
                            </div>
                            <input type="number" step="0.01" name="balance" value="0.00" required
                                class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-lg font-black focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all text-gray-900">
                            <div
                                class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-xs font-black text-gray-400 uppercase">
                                pts
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="isSubmitting"
                    :class="isSubmitting ? 'opacity-50 cursor-not-allowed bg-gray-500' : 'bg-gray-900 hover:bg-purple-600 hover:shadow-lg hover:shadow-purple-200 hover:-translate-y-1'"
                    class="w-full py-5 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl transition-all duration-300">
                    <span x-show="!isSubmitting">Créer le Participant</span>
                    <span x-show="isSubmitting" x-cloak>Création en cours... ⏳</span>
                </button>
            </form>
        </div>

        <div x-show="tab === 'import'" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-purple-500/5 overflow-hidden">

            <form action="{{ route('admin.participants.import', $event->id) }}" method="POST" enctype="multipart/form-data"
                class="p-12 text-center" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf

                @if($errors->any())
                    <div
                        class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-[11px] font-black uppercase tracking-widest text-left">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="border-3 border-dashed rounded-3xl p-10 hover:border-purple-400 hover:bg-purple-50/30 transition-all group cursor-pointer relative"
                    :class="fileName ? 'border-emerald-400 bg-emerald-50/30' : 'border-gray-200'">

                    <input type="file" name="file" accept=".txt,.csv" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        @change="fileName = $event.target.files.length ? $event.target.files[0].name : null">

                    <div x-show="!fileName">
                        <div
                            class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400 group-hover:scale-110 group-hover:bg-purple-100 group-hover:text-purple-600 transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-2">Glisser-déposer ou
                            cliquer</h3>
                        <p class="text-xs text-gray-500 font-medium">Accepte les fichiers <span
                                class="font-bold text-gray-800">.CSV</span> ou <span
                                class="font-bold text-gray-800">.TXT</span></p>
                        <p
                            class="text-[10px] text-gray-400 mt-4 font-mono bg-gray-50 inline-block px-3 py-1 rounded-lg border border-gray-100">
                            Format: code_nfc par ligne</p>
                    </div>

                    <div x-show="fileName" x-cloak>
                        <div
                            class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 scale-110 shadow-lg shadow-emerald-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-emerald-900 uppercase tracking-tight mb-2">Fichier prêt !</h3>
                        <p class="text-sm font-bold text-gray-900 bg-white px-4 py-2 rounded-xl inline-block shadow-sm border border-emerald-100"
                            x-text="fileName"></p>
                        <p class="text-[10px] text-gray-400 mt-4 font-bold uppercase tracking-widest">Cliquez pour changer
                        </p>
                    </div>
                </div>

                <div class="mt-8 text-left max-w-sm mx-auto bg-gray-50 p-6 rounded-3xl border border-gray-100">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1 block mb-3">Crédit
                        initial </label>

                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-emerald-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <input type="number" step="0.01" name="initial_balance" value="0" placeholder="0.00"
                            class="w-full pl-14 pr-12 py-4 bg-white border-2 border-gray-200 rounded-2xl text-xl font-black focus:border-emerald-500 focus:ring-0 transition-all text-gray-900 placeholder-gray-300">

                        <div
                            class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-xs font-black text-gray-400 uppercase">
                            pts</div>
                    </div>
                </div>

                <button type="submit" :disabled="isSubmitting"
                    :class="isSubmitting ? 'opacity-50 cursor-not-allowed bg-gray-500' : 'bg-gray-900 hover:bg-purple-600 hover:shadow-lg hover:shadow-purple-200 hover:-translate-y-1'"
                    class="mt-8 w-full py-5 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl transition-all duration-300">
                    <span x-show="!isSubmitting">Lancer l'importation</span>
                    <span x-show="isSubmitting" x-cloak>Importation en cours... ⏳</span>
                </button>
            </form>
        </div>

    </div>

@endsection