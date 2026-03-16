<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased flex flex-col min-h-screen">

    @include('admin.components.navbar')


    <main class="container mx-auto pt-20 px-4 sm:px-6 lg:px-8 flex-grow pb-10">

        <div class="mb-8">
            @yield('header')
        </div>

        @yield('content')
    </main>

    @vite('resources/js/layouts/admin.js')
</body>

</html>
