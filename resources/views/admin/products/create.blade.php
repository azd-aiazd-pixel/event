@extends('layouts.admin')

@section('header')
    <div class="pb-6 pt-4 border-b border-gray-100">
        <a href="{{ route('admin.stores.products.index', $store->id) }}"
            class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au catalogue
        </a>

        <div class="flex items-baseline gap-3">
            <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase">
                Nouveau <span class="text-blue-600">Produit</span>
            </h1>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">| Boutique : {{ $store->name }}</p>
        </div>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto mt-8" x-data="{ tab: 'manual', fileName: null, isStockable: true, imagePreview: null, imageFileName: null }">

        <div class="flex justify-center mb-6">
            <div class="bg-gray-100 p-1.5 rounded-2xl flex items-center shadow-inner">
                <button @click="tab = 'manual'"
                    :class="tab === 'manual' ? 'bg-white text-gray-900 shadow-md transform scale-105' : 'text-gray-400 hover:text-gray-600'"
                    class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 ease-out">
                    Ajout Manuel
                </button>
                <button @click="tab = 'import'"
                    :class="tab === 'import' ? 'bg-white text-gray-900 shadow-md transform scale-105' : 'text-gray-400 hover:text-gray-600'"
                    class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 ease-out">
                    Import Excel/CSV
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
                <ul class="list-disc list-inside text-[10px] font-black text-red-600 uppercase tracking-tight">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div x-show="tab === 'manual'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-blue-500/5 overflow-hidden relative">

            <div
                class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none">
            </div>

            <form action="{{ route('admin.stores.products.store', $store->id) }}" method="POST"
                enctype="multipart/form-data" class="p-10 relative">
                @csrf

                <div class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nom de
                            l'article</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-blue-500 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="EX: BURGER CHEESE, COCA..."
                                class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-blue-600 focus:bg-white focus:ring-0 transition-all uppercase placeholder-gray-300">
                        </div>
                    </div>



                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Prix
                                Unitaire</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-blue-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="number" step="0.01" name="unit_price" value="{{ old('unit_price') }}" required
                                    placeholder="0.00"
                                    class="w-full pl-14 pr-12 py-5 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black focus:border-blue-600 focus:ring-0 transition-all">
                                <div
                                    class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-xs font-black text-gray-400 uppercase">
                                    DH</div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between ml-1">
                                <label class="text-[10px] font-black uppercase tracking-widest transition-colors"
                                    :class="isStockable ? 'text-gray-400' : 'text-blue-600'">
                                    <span x-text="isStockable ? 'Stock Initial' : 'Disponibilité'">Stock Initial</span>
                                </label>

                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="is_stockable" value="1" class="sr-only peer"
                                        x-model="isStockable">
                                    <div
                                        class="w-7 h-4 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                    <span
                                        class="text-[9px] font-black uppercase text-gray-400 ml-1.5 group-hover:text-gray-600 transition-colors"
                                        x-text="isStockable ? 'Suivi' : 'Illimité'">Suivi</span>
                                </label>
                            </div>

                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors"
                                    :class="isStockable ? 'text-blue-500' : 'text-gray-300'">
                                    <svg x-show="isStockable" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <svg x-show="!isStockable" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>

                                <input type="number" name="quantity" value="{{ old('quantity', 0) }}"
                                    x-bind:required="isStockable" x-bind:readonly="!isStockable"
                                    :class="isStockable 
                                                ? 'bg-white border-gray-100 text-gray-900 focus:border-blue-600' 
                                                : 'bg-gray-100 border-gray-100 text-gray-300 cursor-not-allowed select-none'"
                                    class="w-full pl-14 pr-6 py-5 border-2 rounded-2xl text-sm font-black focus:ring-0 transition-all">

                                <div x-show="!isStockable" x-transition.opacity
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-700 text-[9px] font-black uppercase rounded-full tracking-wider">
                                        Illimité
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-50"></div>

                    <div class="space-y-2 flex flex-col items-center justify-center">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest text-center mb-1">Photo
                            du produit</label>
                        <div class="relative group">
                            <div x-show="imagePreview"
                                class="w-36 h-36 rounded-[2rem] border-4 border-white shadow-2xl overflow-hidden relative transition-transform duration-300 group-hover:scale-105"
                                style="display: none;">
                                <img :src="imagePreview" class="w-full h-full object-cover" />
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </div>
                            </div>

                            <div x-show="!imagePreview"
                                class="w-36 h-36 rounded-[2rem] bg-gray-50 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center hover:bg-blue-50 hover:border-blue-300 transition-all cursor-pointer group-hover:scale-105">
                                <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Ajouter</span>
                            </div>

                            <input name="picture" type="file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*"
                                @change="
                                    if ($event.target.files && $event.target.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { imagePreview = e.target.result; };
                                        reader.readAsDataURL($event.target.files[0]);
                                        imageFileName = $event.target.files[0].name;
                                    } else {
                                        imagePreview = null;
                                        imageFileName = null;
                                    }
                                " />
                        </div>

                        <div class="text-center h-4">
                            <p x-show="imageFileName" x-text="imageFileName"
                                class="text-[10px] font-black text-blue-600 uppercase tracking-tighter" style="display: none;"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Visibilité</label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="1" checked class="text-blue-600 focus:ring-0">
                                <span class="text-[10px] font-black uppercase">Actif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="0" class="text-red-600 focus:ring-0">
                                <span class="text-[10px] font-black uppercase text-gray-400">Désactivé</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-1 transition-all duration-300">
                        Ajouter au catalogue
                    </button>
                </div>
            </form>
        </div>

        <div x-show="tab === 'import'" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-blue-500/5 overflow-hidden">

            <form action="{{ route('admin.stores.products.import', $store->id) }}" method="POST"
                enctype="multipart/form-data" class="p-12 text-center">
                @csrf


                <div class="border-3 border-dashed rounded-3xl p-10 hover:border-blue-400 hover:bg-blue-50/30 transition-all group cursor-pointer relative"
                    :class="fileName ? 'border-emerald-400 bg-emerald-50/30' : 'border-gray-200'">


                    <input type="file" name="file" accept=".csv,.txt" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        @change="fileName = $event.target.files.length ? $event.target.files[0].name : null">

                    <div x-show="!fileName">
                        <div
                            class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400 group-hover:scale-110 group-hover:bg-blue-100 group-hover:text-blue-600 transition-all duration-300">
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
                            Colonnes: name; unit_price; picture(chemin)</p>
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

                <button type="submit"
                    class="mt-8 w-full py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-1 transition-all duration-300">
                    Lancer l'importation
                </button>
            </form>
        </div>

    </div>



@endsection