<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Espace Participant')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#F8F9FA] text-zinc-900 font-sans flex flex-col min-h-screen selection:bg-zinc-200">

    <header
        class="bg-white/85 backdrop-blur-md border-b border-zinc-200/50 sticky top-0 z-40 px-5 py-4 flex justify-between items-center">
        <div class="font-extrabold text-xl tracking-tighter text-zinc-900 truncate pr-2">
            {{ Auth::user()->participant->event->name }}
        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('participant.wishlist.index') }}"
                class="relative p-1.5 text-zinc-600 hover:text-zinc-900 transition-colors active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </a>

            <a href="{{ route('participant.cart.index') }}"
                class="relative p-1.5 text-zinc-600 hover:text-zinc-900 transition-colors active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span id="globalCartBadge"
                    class="hidden absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full border-2 border-white">
                    0
                </span>
            </a>

            <div
                class="bg-zinc-900 text-white px-4 py-1.5 rounded-full flex items-baseline shadow-md shadow-zinc-900/20">
                <span
                    class="font-black text-sm tracking-tight">{{ (float) (Auth::user()->participant->available_balance ?? 0) }}</span>
                <span class="text-[10px] font-bold text-zinc-400 ml-1 uppercase tracking-wider">Pts</span>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-6 px-5 flex-grow pb-28">
        @yield('content')
    </main>

    @include('Participant.components.bottom_nav')

    @vite('resources/js/layouts/participant.js')

    @stack('scripts')
</body>

</html>