<div class="h-full w-full flex overflow-hidden">
    
    <div class="w-1/3 h-full flex flex-col bg-white border-r border-slate-300 shadow-2xl z-20">
        
        <div class="p-6 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Panier Actuel</span>
            <button @click="cart = []; selectedItemIndex = null" class="px-3 py-1 bg-red-50 text-red-500 rounded-lg text-[9px] font-black uppercase hover:bg-red-500 hover:text-white transition-all">Vider</button>
        </div>

        <div class="flex-grow overflow-y-auto custom-scrollbar bg-white">
            <div class="divide-y divide-slate-100">
                <template x-for="(item, index) in cart" :key="index">
                    <div @click="selectItem(index)"
                         class="p-4 flex items-center gap-4 cursor-pointer transition-all border-l-4"
                         :class="selectedItemIndex === index ? 'bg-indigo-50 border-indigo-600' : 'border-transparent hover:bg-slate-50'">
                        
                        <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 shadow-sm">
                            <img :src="item.picture ? '/storage/' + item.picture : '/images/placeholder.webp'" 
                                 class="w-full h-full object-cover">
                        </div>

                        <div class="flex-grow min-w-0">
                            <h3 class="font-bold text-slate-800 text-xs truncate uppercase leading-tight" x-text="item.name"></h3>
                            <span class="text-[10px] font-black text-indigo-600" x-text="formatPrice(item.price) + ' DH'"></span>
                        </div>

                        <div class="bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm">
                            <span class="text-xs font-black text-slate-700" x-text="'x' + item.qty"></span>
                        </div>

                        <div class="text-right min-w-[80px]">
                            <span class="font-black text-slate-900 text-xs" x-text="formatPrice(item.qty * item.price) + ' DH'"></span>
                        </div>
                    </div>
                </template>
            </div>
            
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center opacity-20 grayscale">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Aucun article</span>
                </div>
            </template>
        </div>

        <div class="bg-slate-800 p-6">
            <div class="flex justify-between items-end mb-6">
                <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Total à payer</span>
                <span class="text-4xl font-black text-white tracking-tighter" x-text="formatPrice(total) + ' DH'"></span>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 4l-1 1m-1 7l1 1m-7 1v1m-7-1l1-1m-1-7l1-1" stroke-width="2.5" stroke-linecap="round"/></svg>
                    </div>
                    <input type="text" x-model="nfcCode" placeholder="SCANNER LE BRACELET ICI..." 
                           @keydown.enter.prevent="processPayment()" 
                           :disabled="isProcessing"
                           class="w-full pl-12 pr-4 py-4 bg-slate-800 border-2 border-slate-700 rounded-2xl text-white font-mono text-sm focus:border-indigo-500 focus:ring-0 transition-all placeholder-slate-600 shadow-inner uppercase tracking-widest disabled:opacity-50">
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-3 grid grid-cols-3 gap-2">
                    <template x-for="n in ['1','2','3','4','5','6','7','8','9','0','00','C']">
                        <button type="button" @click="handleNumpad(n)" 
                                :disabled="isProcessing"
                                class="h-14 rounded-xl bg-slate-800 text-white text-xl font-bold hover:bg-slate-700 active:bg-indigo-600 transition-all shadow-sm disabled:opacity-50">
                            <span x-text="n"></span>
                        </button>
                    </template>
                </div>
                <div class="col-span-1 flex flex-col gap-2">
                    <button type="button" @click="removeItem()" :disabled="isProcessing" class="h-14 bg-red-500/10 text-red-500 rounded-xl flex items-center justify-center font-black text-xs hover:bg-red-500 hover:text-white transition-all disabled:opacity-50">DEL</button>
                    <button type="button" @click="processPayment()" 
                            :disabled="isProcessing || !nfcCode || cart.length === 0"
                            class="flex-grow bg-emerald-500 text-white rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-emerald-400 disabled:opacity-20 transition-all shadow-lg shadow-emerald-500/20 flex flex-col items-center justify-center">
                        <template x-if="!isProcessing"><span>Payer</span></template>
                        <template x-if="isProcessing">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                    </button>
                </div>
            </div>
            
            
        </div>
    </div>

    <div class="w-2/3 h-full flex flex-col bg-[#F8FAFC]">
        <div class="bg-white border-b border-slate-200 p-6 shadow-sm">
            <div class="relative mb-6 max-w-2xl mx-auto">
                <input type="text" x-model="search" placeholder="Chercher un article..." 
                       class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all">
                <svg class="absolute left-4 top-4 w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" stroke-linecap="round"/></svg>
            </div>

            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1 px-2">
                <button @click="activeCategory = null" 
                        :class="activeCategory === null ? 'bg-slate-900 text-white shadow-lg' : 'bg-slate-100 text-slate-500'"
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all shadow-sm">
                    Tous les articles
                </button>
                @foreach($categories as $cat)
                <button @click="activeCategory = {{ $cat->id }}" 
                        :class="activeCategory === {{ $cat->id }} ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-slate-500 border-slate-200'"
                        class="px-8 py-3 rounded-xl border text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="flex-grow overflow-y-auto p-6 custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    <button @click="addToCart(product)"
                         class="bg-white rounded-[2.5rem] p-4 shadow-sm border border-slate-200/60 flex flex-col text-left hover:shadow-2xl hover:-translate-y-1 transition-all group">
                        <div class="aspect-square rounded-[1.8rem] bg-slate-50 mb-4 overflow-hidden relative">
                            <img :src="product.picture ? '/storage/' + product.picture : '/images/placeholder.webp'" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <h3 class="font-black text-slate-800 text-[11px] truncate uppercase px-1 mb-1" x-text="product.name"></h3>
                        <div class="flex justify-between items-center mt-2 px-1">
                          <span class="text-[9px] font-bold uppercase tracking-wider" 
                                  :class="!product.is_stockable ? 'text-blue-500' : (product.quantity <= 0 ? 'text-red-500' : 'text-slate-400')"
                                  x-text="!product.is_stockable ? 'Illimité' : (product.quantity <= 0 ? 'Rupture' : product.quantity + ' STK')">
                            </span>
                            <span class="text-indigo-600 font-black text-xs" x-text="formatPrice(product.unit_price) + ' DH'"></span>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>