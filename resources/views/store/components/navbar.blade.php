<nav class="h-20 bg-white border-b border-gray-50 flex items-center justify-between px-8 sticky top-0 z-40">

    <div class="flex items-center gap-6">

        @if(!Route::is('store.select'))
            <button @click="sidebarOpen = !sidebarOpen"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-black hover:bg-black hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7m0 0l7-7m-7 7h18" />
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        @endif

        <div>
            <h1 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-900">
                @if(Route::is('store.select'))
                    Configuration Session
                @else
                    @yield('title', '')
                @endif
            </h1>
        </div>
    </div>


    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false"
            class="flex items-center gap-4 group focus:outline-none">

            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-black text-black uppercase tracking-tight">{{ Auth::user()->name }}</p>
                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest leading-none">Mon Compte</p>
            </div>

            <div
                class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center font-black text-xs group-hover:bg-black group-hover:text-white transition-all shadow-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </button>

        {{-- Le Menu Dropdown --}}
        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">

            <div class="px-5 py-3 border-b border-gray-50">
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Options</p>
                @if(isset($store) && $store->id)
                    <a href="{{ route('store.settings.edit', $store) }}"
                        class="block text-[11px] font-bold text-gray-900 mt-2 hover:text-gray-500 transition-colors">
                        Paramètres de la Boutique
                    </a>
            
                    <a href="{{ route('store.profile.edit', $store) }}"
                        class="block text-[11px] font-bold text-gray-900 mt-2 hover:text-gray-500 transition-colors">
                        Mon profil
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>