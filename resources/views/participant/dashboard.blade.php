@extends('layouts.participant')

@section('title', 'Mon Espace')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-12">

        <div
            class="mb-6 sm:mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-slate-100 pb-6 sm:pb-8 gap-4 sm:gap-6">
            <form method="GET" action="{{ route('participant.dashboard') }}"
                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="text" id="datePicker" name="date_range" value="{{ $dateRange ?? '' }}"
                        placeholder="Choisir une période..."
                        class="pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-black cursor-pointer w-full sm:w-64">
                </div>
                <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                    <button type="submit"
                        class="flex-1 sm:flex-none px-4 sm:px-6 py-3 bg-black text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200 text-center">
                        Filtrer
                    </button>
                    <a href="{{ route('participant.dashboard') }}"
                        class="flex-1 sm:flex-none px-4 py-3 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-colors text-center inline-block">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- 1. Les KPIs (Affichage plus compact sur mobile : grille 2x2 ou horizontal) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-10">

            {{-- Solde Actuel --}}
            <div
                class="bg-white border border-slate-100 p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm relative overflow-hidden group">
                <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 truncate">Solde
                    Actuel</h3>
                <div class="flex flex-col sm:flex-row sm:items-baseline gap-0.5 sm:gap-1.5 mt-1 sm:mt-2">
                    <span
                        class="text-xl sm:text-3xl font-black tracking-tighter text-emerald-600 truncate">{{ number_format($currentBalance, 0, ',', ' ') }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-emerald-400">PTS</span>
                </div>
                <div class="absolute bottom-0 right-0 w-8 sm:w-16 h-8 sm:h-16 bg-emerald-50 rounded-tl-full opacity-50">
                </div>
            </div>

            {{-- Dépenses Totales --}}
            <div
                class="bg-white border border-slate-100 p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm relative overflow-hidden">
                <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 truncate">
                    Dépenses</h3>
                <div class="flex flex-col sm:flex-row sm:items-baseline gap-0.5 sm:gap-1.5 mt-1 sm:mt-2">
                    <span
                        class="text-xl sm:text-3xl font-black tracking-tighter text-slate-900 truncate">{{ number_format($totalSpent, 0, ',', ' ') }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-400">PTS</span>
                </div>
                <div class="absolute bottom-0 right-0 w-8 sm:w-16 h-8 sm:h-16 bg-red-50 rounded-tl-full opacity-50"></div>
            </div>

            {{-- Commandes --}}
            <div
                class="bg-white border border-slate-100 p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm relative overflow-hidden">
                <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 truncate">
                    Commandes</h3>
                <div class="flex flex-col sm:flex-row sm:items-baseline gap-0.5 sm:gap-1.5 mt-1 sm:mt-2">
                    <span
                        class="text-xl sm:text-3xl font-black tracking-tighter text-slate-900 truncate">{{ $ordersCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-400">Tickets</span>
                </div>
                <div class="absolute bottom-0 right-0 w-8 sm:w-16 h-8 sm:h-16 bg-blue-50 rounded-tl-full opacity-50"></div>
            </div>

            {{-- Articles Achetés --}}
            <div
                class="bg-white border border-slate-100 p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] shadow-sm relative overflow-hidden">
                <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 truncate">
                    Articles</h3>
                <div class="flex flex-col sm:flex-row sm:items-baseline gap-0.5 sm:gap-1.5 mt-1 sm:mt-2">
                    <span
                        class="text-xl sm:text-3xl font-black tracking-tighter text-slate-900 truncate">{{ $itemsPurchased }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-400">Unités</span>
                </div>
                <div class="absolute bottom-0 right-0 w-8 sm:w-16 h-8 sm:h-16 bg-amber-50 rounded-tl-full opacity-50"></div>
            </div>

        </div>

        {{-- 2. Les Graphiques --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

            {{-- Courbe des Dépenses --}}
            <div
                class="lg:col-span-2 bg-white border border-slate-100 p-5 sm:p-8 rounded-3xl sm:rounded-[2.5rem] shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 sm:mb-6">{{ $chartTitle }}
                </h3>
                <div class="relative h-64 sm:h-72 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Top Produits --}}
            <div class="bg-white border border-slate-100 p-5 sm:p-8 rounded-3xl sm:rounded-[2.5rem] shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 sm:mb-6">Top 5 Produits
                    Achetés</h3>
                @if(count($topProductsNames) > 0)
                    <div class="relative h-56 sm:h-64 w-full flex items-center justify-center">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                @else
                    <div class="h-56 sm:h-64 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Aucune dépense sur la période
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        window.ParticipantDashboardConfig = {
            dateRange: @json($dateRange ?? 'today'),
            chartLabels: @json($chartLabels),
            chartData: @json($chartData),
            topProductsNames: @json($topProductsNames),
            topProductsQuantities: @json($topProductsQuantities)
        };
    </script>
    @vite('resources/js/participant/dashboard.js')
@endsection