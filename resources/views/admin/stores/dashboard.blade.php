@extends('layouts.admin')

@section('content')


    <div>
        <a href="{{ route('admin.stores.index', $event->id) }}"
            class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-indigo-500 mb-4 flex items-center gap-2 transition-colors inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux boutiques
        </a>
        <h1 class="text-3xl font-black tracking-tighter uppercase text-slate-900 leading-none">
            {{ $store->name }} <span class="text-slate-300 text-2xl">| Performances</span>
        </h1>
    </div>

    {{-- FORMULAIRE FLATPICKR (Avec les bonnes routes Admin) --}}
    <form method="GET" action="{{ route('admin.stores.dashboard', [$event, $store]) }}" class="flex items-center gap-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <input type="text" id="datePicker" name="date_range" value="{{ $dateRange ?? '' }}"
                placeholder="Choisir une période..."
                class="pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-black cursor-pointer w-64">
        </div>
        <button type="submit"
            class="px-6 py-3 bg-black text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
            Filtrer
        </button>
        <a href="{{ route('admin.stores.dashboard', [$event, $store]) }}"
            class="px-4 py-3 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-colors">
            Reset
        </a>
    </form>
    </div>

    {{-- 1. Les KPIs (4 Cartes) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        {{-- CA --}}
        <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden group">
            <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Chiffre d'Affaires</h3>
            <div class="flex items-baseline gap-1.5 mt-2">
                <span
                    class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($revenue, 2, ',', ' ') }}</span>
                <span class="text-xs font-bold text-slate-400">PTS</span>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-emerald-50 rounded-tl-full opacity-50"></div>
        </div>

        {{-- Commandes --}}
        <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden">
            <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Commandes</h3>
            <div class="flex items-baseline gap-1.5 mt-2">
                <span class="text-3xl font-black tracking-tighter text-slate-900">{{ $ordersCount }}</span>
                <span class="text-xs font-bold text-slate-400">Tickets</span>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-blue-50 rounded-tl-full opacity-50"></div>
        </div>

        {{-- Panier Moyen --}}
        <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden">
            <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Panier Moyen</h3>
            <div class="flex items-baseline gap-1.5 mt-2">
                <span
                    class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($averageOrderValue, 2, ',', ' ') }}</span>
                <span class="text-xs font-bold text-slate-400">PTS / Cmd</span>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-purple-50 rounded-tl-full opacity-50"></div>
        </div>

        {{-- Articles Vendus --}}
        <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden">
            <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Volume de Produits</h3>
            <div class="flex items-baseline gap-1.5 mt-2">
                <span class="text-3xl font-black tracking-tighter text-slate-900">{{ $itemsSold }}</span>
                <span class="text-xs font-bold text-slate-400">Unités</span>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-amber-50 rounded-tl-full opacity-50"></div>
        </div>

    </div>

    {{-- 2. Les Graphiques --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Courbe des Ventes --}}
        <div class="lg:col-span-2 bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">{{ $chartTitle }}</h3>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Top Produits --}}
        <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Top 5 Produits</h3>
            @if(count($topProductsNames) > 0)
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="topProductsChart"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Aucune donnée sur la période
                    </p>
                </div>
            @endif
        </div>

    </div>
    </div>

    <script>
        window.StoreDashboardConfig = {
            dateRange: @json($dateRange ?? ''),
            eventStartDate: @json(\Carbon\Carbon::parse($event->start_date)->format('Y-m-d')),
            eventEndDate: @json(\Carbon\Carbon::parse($event->end_date)->format('Y-m-d')),
            chartLabels: @json($chartLabels),
            chartData: @json($chartData),
            topProductsNames: @json($topProductsNames),
            topProductsQuantities: @json($topProductsQuantities)
        };
    </script>
    @vite('resources/js/Admin/stores/dashboard.js')
@endsection