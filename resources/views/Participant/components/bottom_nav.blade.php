<nav
    class="fixed bottom-0 w-full bg-white/90 backdrop-blur-md border-t border-zinc-200/50 flex justify-around items-center h-[76px] z-50 pb-safe">

    <a href="{{ route('participant.dashboard') }}"
        class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 {{ request()->routeIs('participant.dashboard') ? 'text-zinc-900' : 'text-zinc-400 hover:text-zinc-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 mb-1.5 {{ request()->routeIs('participant.dashboard') ? 'stroke-[2.5px]' : 'stroke-2' }}"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="text-[10px] font-bold tracking-widest uppercase">Accueil</span>
        @if(request()->routeIs('participant.dashboard'))
            <div class="absolute bottom-1.5 w-1 h-1 bg-zinc-900 rounded-full"></div>
        @endif
    </a>

    <a href="{{ route('participant.stores.index') }}"
        class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 {{ request()->routeIs('participant.stores.*') ? 'text-zinc-900' : 'text-zinc-400 hover:text-zinc-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 mb-1.5 {{ request()->routeIs('participant.stores.*') ? 'stroke-[2.5px]' : 'stroke-2' }}"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span class="text-[10px] font-bold tracking-widest uppercase">Boutiques</span>
        @if(request()->routeIs('participant.stores.*'))
            <div class="absolute bottom-1.5 w-1 h-1 bg-zinc-900 rounded-full"></div>
        @endif
    </a>
    <a href="{{ route('participant.orders.index') }}"
        class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 {{ request()->routeIs('participant.orders.*') ? 'text-zinc-900' : 'text-zinc-400 hover:text-zinc-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 mb-1.5 {{ request()->routeIs('participant.orders.*') ? 'stroke-[2.5px]' : 'stroke-2' }}"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="text-[10px] font-bold tracking-widest uppercase">Achats</span>
        @if(request()->routeIs('participant.orders.*'))
            <div class="absolute bottom-1.5 w-1 h-1 bg-zinc-900 rounded-full"></div>
        @endif
    </a>

    <a href="{{ route('participant.profile.edit') }}"
        class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 {{ request()->routeIs('participant.profile.*') ? 'text-zinc-900' : 'text-zinc-400 hover:text-zinc-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 mb-1.5 {{ request()->routeIs('participant.profile.*') ? 'stroke-[2.5px]' : 'stroke-2' }}"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span class="text-[10px] font-bold tracking-widest uppercase">Profil</span>
        @if(request()->routeIs('participant.profile.*'))
            <div class="absolute bottom-1.5 w-1 h-1 bg-zinc-900 rounded-full"></div>
        @endif
    </a>
</nav>