<aside x-cloak 
       x-show="sidebarOpen"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed inset-y-0 left-0 w-72 bg-white border-r border-gray-100 z-50 flex flex-col">
    

       @php
    
        $activeStore = $store ?? request()->route('store');
    @endphp
    <div class="h-20 flex items-center px-8 border-b border-gray-50">
        <span class="text-lg font-black tracking-tighter uppercase">
            Shop<span class="text-gray-400">Panel</span>
        </span>
    </div>

    <nav class="flex-grow p-6 space-y-2">
        <p class="px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest mb-6">Menu Principal</p>

       <a href="{{ route('store.dashboard', $activeStore) }}" 
           class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all
           {{ request()->routeIs('store.dashboard') ? 'bg-black text-white shadow-2xl shadow-black/10' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Dashboard</span>
        </a>

     <a href="{{ route('store.products.index', $activeStore) }}" 
           class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all
           {{ request()->routeIs('store.products.*') ? 'bg-black text-white shadow-2xl shadow-black/10' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Gestion Produits</span>
        </a>

@if($activeStore && $activeStore->workflow_type === 'queue')
        <a href="{{ route('store.queue.index', $activeStore) }}" 
           class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all
           {{ request()->routeIs('store.queue.index') ? 'bg-black text-white shadow-2xl shadow-black/10' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="text-[10px] font-black uppercase tracking-widest">File d'attente</span>
        </a>
        @endif






        <a href="{{ route('store.terminal.index', $activeStore) }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-gray-400 hover:bg-gray-50 hover:text-black transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span class="text-[10px] font-black uppercase tracking-widest">terminal</span>
        </a>

<a href="{{ route('store.orders.index', $activeStore) }}" 
   class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all
   {{ request()->routeIs('store.orders.*') ? 'bg-black text-white shadow-2xl shadow-black/10' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span class="text-[10px] font-black uppercase tracking-widest">Historique Ventes</span>
</a>
   <a href="{{ route('store.refunds.index', $activeStore) }}" 
           class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all
           {{ request()->routeIs('store.refunds.*') ? 'bg-black text-white shadow-2xl shadow-black/10' : 'text-gray-400 hover:bg-red-50 hover:text-red-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
            </svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Remboursements</span>
        </a>     
    </nav>
<div class="p-6 border-t border-gray-50">
        <a href="{{ route('store.select') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-red-500 hover:bg-red-50 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Changer Boutique</span>
        </a>
    </div>
    
</aside>