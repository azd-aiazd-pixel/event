<nav class="fixed top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

          
            <div class="flex items-center gap-10">
                
        <a href="{{ route('admin.dashboard') }}" class="flex items-center group">
    <span class="text-xl font-black tracking-tighter text-gray-900 uppercase">
        Events<span class="text-purple-600">Access</span>
    </span>
    
    
    <div class="h-4 w-[1.5px] bg-gray-200 mx-6 hidden md:block"></div>
</a>

                <div class="hidden md:flex items-center gap-1">
<a href="{{ route('admin.events.index') }}" 
   class="relative px-4 py-2 text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300
   {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.participants.*')
       ? 'text-gray-900' 
       : 'text-gray-400 hover:text-gray-600' }}">
    
    Événements


    @if(request()->routeIs('admin.events.*') || request()->routeIs('admin.participants.*'))
        <div class="absolute -bottom-[21px] left-0 w-full h-[3px] bg-purple-600 rounded-t-full"></div>
    @endif
</a>
                </div>
            </div>

 
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center gap-3 pl-2 pr-1 py-1 rounded-full hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100 group">
                    
                    <div class="text-right hidden md:block">
                      
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Admin</p>
                    </div>

                    <div class="h-9 w-9 rounded-full bg-gray-900 flex items-center justify-center text-white font-black text-xs shadow-md group-hover:bg-purple-600 transition-colors">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </button>

             
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                    
                    <div class="px-5 py-3 border-b border-gray-50">
    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Compte</p>

    <a href="{{ route('admin.profile.edit') }}"
       class="block text-xs font-bold text-gray-900 mt-1 hover:underline cursor-pointer">
        Mon profil
    </a>
</div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>