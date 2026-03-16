@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6" x-data="{ tab: 'manual', fileName: null, imagePreview: null, isActive: true, isStockable: true }">
    
    <div class="flex justify-center mb-10">
        <div class="inline-flex bg-slate-100 p-1.5 rounded-2xl shadow-inner">
            <button @click="tab = 'manual'" 
                    :class="tab === 'manual' ? 'bg-white shadow-sm text-black' : 'text-slate-400'"
                    class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                Saisie Manuelle
            </button>
            <button @click="tab = 'import'" 
                    :class="tab === 'import' ? 'bg-white shadow-sm text-black' : 'text-slate-400'"
                    class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                Importation CSV
            </button>
        </div>
    </div>

    <div x-show="tab === 'manual'" x-transition:enter="transition ease-out duration-300">
        <form action="{{ route('store.products.store', $store) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-2xl shadow-slate-200/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                   
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Photo du produit</label>
                        <div class="relative group aspect-square rounded-[2.5rem] overflow-hidden bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center transition-all hover:border-black">
                            
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>

                            <div x-show="!imagePreview" class="text-center p-6">
                                <svg class="w-12 h-12 mx-auto text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Glissez une image ici</span>
                            </div>

                            <input type="file" name="picture" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }">
                        </div>
                    </div>

                    <div class="space-y-6">
                 
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Nom du produit</label>
                            <input type="text" name="name" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-black transition-all" placeholder="Nom du produit">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Catégorie</label>
                                <select name="category_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-black appearance-none cursor-pointer">
                                    <option value="">Général</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Prix (DH)</label>
                                <input type="number" step="0.01" name="unit_price" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-black focus:ring-2 focus:ring-black" placeholder="0.00">
                            </div>
                        </div>

                      
                        <div class="p-5 bg-slate-50 rounded-[2rem] space-y-4 border border-slate-100 transition-colors"
                             :class="!isStockable ? 'bg-slate-100/50' : ''">
                            
                            <div class="flex items-center justify-between ml-1">
                                <label class="text-[10px] font-black uppercase tracking-widest transition-colors"
                                       :class="isStockable ? 'text-slate-400' : 'text-slate-600'">
                                    <span x-text="isStockable ? 'Inventaire initial' : 'Produit Illimité'">Inventaire initial</span>
                                </label>

                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="is_stockable" value="1" class="sr-only peer" x-model="isStockable">
                                    <div class="w-7 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-slate-800"></div>
                                    <span class="text-[9px] font-black uppercase text-slate-400 ml-1.5 group-hover:text-slate-600 transition-colors" x-text="isStockable ? 'Suivi' : 'Illimité'">Suivi</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-3 relative">
                                <div class="flex-grow">
                                    <input type="number" name="quantity" 
                                           x-bind:required="isStockable"
                                           x-bind:readonly="!isStockable"
                                           :class="isStockable ? 'bg-white focus:ring-black text-black' : 'bg-slate-100/50 text-slate-400 cursor-not-allowed'"
                                           class="w-full px-6 py-4 border-none rounded-2xl text-sm font-black focus:ring-2 transition-all" 
                                           placeholder="Quantité">
                                </div>
                                <div class="w-1/3">
                                    <select name="unit_measure_id" 
                                            x-bind:disabled="!isStockable"
                                            :class="isStockable ? 'bg-white focus:ring-black text-black' : 'bg-slate-100/50 text-slate-400 cursor-not-allowed'"
                                            class="w-full px-4 py-4 border-none rounded-2xl text-xs font-bold focus:ring-2 appearance-none cursor-pointer text-center transition-all">
                                         <option value=""></option>
                                    @foreach($unitMeasures as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="!isStockable" x-transition.opacity
                                     class="absolute inset-0 flex items-center justify-center bg-slate-100/80 rounded-2xl backdrop-blur-[1px]">
                                    <span class="px-4 py-1.5 bg-slate-800 text-white text-[10px] font-black uppercase rounded-xl tracking-widest shadow-md">
                                       Illimité
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-6 rounded-[2rem] transition-colors duration-300"
                             :class="isActive ? 'bg-emerald-50' : 'bg-slate-100'">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest block"
                                      :class="isActive ? 'text-emerald-700' : 'text-slate-500'">Statut de vente</span>
                                <span class="text-[9px] font-medium" :class="isActive ? 'text-emerald-600/70' : 'text-slate-400'">Visible sur le terminal</span>
                            </div>
                            
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="isActive" class="sr-only peer">
                                <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-6 bg-black text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-[2.5rem] hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 active:scale-[0.98]">
                Confirmer et créer le produit
            </button>
        </form>
    </div>

    <div x-show="tab === 'import'" x-cloak x-transition:enter="transition ease-out duration-300">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
            <form action="{{ route('store.products.import', $store) }}" method="POST" enctype="multipart/form-data" class="p-12 text-center">
                @csrf
                
                <div class="border-3 border-dashed rounded-[2.5rem] p-12 hover:border-black hover:bg-slate-50/50 transition-all group cursor-pointer relative"
                     :class="fileName ? 'border-emerald-400 bg-emerald-50/30' : 'border-slate-200'">
                    
                    <input type="file" name="file" accept=".csv .txt" required 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           @change="fileName = $event.target.files.length ? $event.target.files[0].name : null">
                    
                    <div x-show="!fileName">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 group-hover:scale-110 group-hover:bg-black group-hover:text-white transition-all duration-300 shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-2">Importation massive</h3>
                        <p class="text-xs text-slate-400 font-medium">Déposez votre catalogue au format <span class="font-bold text-slate-800">.CSV .TXT</span></p>
                        
                        <div class="mt-10 pt-8 border-t border-slate-50 flex flex-col items-center">
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-4">Structure du fichier :</p>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-mono text-slate-600 border border-slate-200">nom</span>
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-mono text-slate-600 border border-slate-200">prix</span>
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-mono text-slate-600 border border-slate-200">picture url</span>
                             </div>
                        </div>
                    </div>

                    <div x-show="fileName" x-cloak>
                        <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 scale-110 shadow-lg shadow-emerald-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-emerald-900 uppercase tracking-tight mb-2">Fichier prêt</h3>
                        <p class="text-sm font-bold text-slate-900 bg-white px-6 py-3 rounded-2xl inline-block shadow-sm border border-emerald-100" x-text="fileName"></p>
                    </div>
                </div>

                <button type="submit" class="mt-10 w-full py-6 bg-emerald-600 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-[2rem] hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100">
                    Lancer l'importation
                </button>
            </form>
        </div>
    </div>
</div>
@endsection