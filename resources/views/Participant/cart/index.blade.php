@extends('layouts.participant')

@section('title', 'Mon Panier')

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="javascript:history.back()"
            class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-zinc-200 text-zinc-900 hover:bg-zinc-50 active:scale-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-extrabold text-zinc-900 tracking-tight">Mon Panier</h1>
    </div>

    <div id="cartContainer" class="space-y-6 pb-6">
    </div>

    <div id="emptyCartState" class="hidden bg-white rounded-3xl p-10 text-center shadow-sm border border-zinc-100 mt-4">
        <div class="flex justify-center mb-5">
            <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center border border-zinc-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-zinc-300" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        <h3 class="text-xl font-bold text-zinc-800">Votre panier est vide</h3>
        <p class="text-zinc-500 text-sm mt-2 mb-6">Découvrez les boutiques de l'événement et ajoutez des articles.</p>
        <a href="{{ route('participant.stores.index') }}"
            class="inline-block bg-zinc-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-zinc-800 active:scale-95 transition-all shadow-md shadow-zinc-900/20 text-center">
            Explorer les boutiques
        </a>
    </div>

    <script>
        window.ParticipantCartConfig = {
            csrfToken: '{{ csrf_token() }}',
            checkoutRoute: '{{ route("participant.cart.checkout") }}',
            orderShowRoute: '{{ route("participant.orders.show", ":id") }}'
        };
    </script>
    @vite('resources/js/participant/cart/index.js')
@endsection