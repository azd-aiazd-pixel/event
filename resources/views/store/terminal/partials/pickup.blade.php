<div class="h-full w-full flex flex-col p-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Retrait des Commandes</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                Scannez le bracelet ou tapez le N° de commande
            </p>
        </div>

        <div class="flex items-center gap-4 w-1/2">
            <button x-show="scannedParticipantId" @click="resetPickupFilter()" x-transition
                    class="px-4 py-4 bg-red-50 text-red-500 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                Annuler Filtre
            </button>

            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 4l-1 1m-1 7l1 1m-7 1v1m-7-1l1-1m-1-7l1-1"></path></svg>
                </div>
                <input type="text" x-model="pickupSearch" @keydown.enter="processPickupScan()"
                       placeholder="SCANNER LE BRACELET..."
                       class="w-full pl-12 pr-4 py-4 bg-white border-2 border-slate-200 rounded-2xl text-slate-900 font-mono text-sm focus:border-indigo-500 focus:ring-0 transition-all shadow-sm uppercase tracking-widest">
            </div>
        </div>
    </div>

    <div class="flex-grow overflow-y-auto custom-scrollbar pr-2">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
          <template x-for="group in pickupGroups" :key="group.pId">
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-200 flex flex-col h-[500px] relative transition-all hover:shadow-xl">
        
        <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4 flex-shrink-0">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Client ID <span class="text-slate-800 text-xs">#<span x-text="group.pId"></span></span>
            </span>
            <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest animate-pulse">
                Prêt
            </span>
        </div>

        <div class="flex-grow overflow-y-auto mb-6 space-y-4 custom-scrollbar pr-2">
            <template x-for="order in group.orders" :key="order.id">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 flex justify-between">
                        <span>Ticket #<span x-text="order.id"></span></span>
                    </div>
                    <ul class="space-y-2">
                        <template x-for="item in order.items" :key="item.id">
                            <li class="flex items-start gap-2 text-xs font-bold text-slate-700 leading-tight">
                                <span class="bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded text-[10px]" x-text="item.quantity + 'x'"></span>
                                <span x-text="item.product ? item.product.name : 'Produit inconnu'" class="pt-0.5"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>
        </div>

        <div class="flex-shrink-0">
            <button @click="collectOrders(group.pId, group.orders.map(o => o.id))"
                    class="w-full bg-slate-900 text-white rounded-xl py-4 font-black uppercase text-[10px] tracking-widest hover:bg-indigo-600 transition-colors shadow-lg shadow-slate-900/20 flex justify-center items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                Tout Livrer
            </button>
        </div>
    </div>
</template>
            
            <template x-if="pickupGroups.length === 0">
                <div class="col-span-full py-32 flex flex-col items-center justify-center text-slate-400">
                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <p class="font-black uppercase tracking-widest text-xs">Aucune commande en attente</p>
                </div>
            </template>

        </div>
    </div>
</div>