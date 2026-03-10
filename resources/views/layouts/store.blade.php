<!DOCTYPE html>
<html lang="fr" x-data="{ sidebarOpen: $persist(true).as('store_sidebar_state') }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Manager</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('header_scripts')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>

</head>

<body class="bg-[#F9FAFB] text-black antialiased overflow-x-hidden">

    @if(!Route::is('store.select', 'store.terminal.index'))
        @include('store.components.sidebar')
    @endif

    <div class="transition-all duration-300 min-h-screen flex flex-col"
        :class="{{ Route::is('store.select', 'store.terminal.index') ? 'true' : 'false' }} ? 'pl-0' : (sidebarOpen ? 'pl-72' : 'pl-0')">

        @if(!Route::is('store.select', 'store.terminal.index'))
            @include('store.components.navbar')
        @endif


        <main class="{{ Route::is('store.terminal.index') ? '' : 'p-8' }}">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>