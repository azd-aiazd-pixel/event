@extends('layouts.admin')

@section('title', 'Global Reporting')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-12">
        {{-- Filtre --}}
        <div class="mb-10 flex justify-end w-full">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-3 w-full md:w-auto">

                <div class="relative flex-grow md:w-64">
                    <input type="text" id="datePicker" name="date_range" value="{{ $dateRange ?? '' }}"
                        placeholder="Période (ex: 30 derniers jours)"
                        class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-600 cursor-pointer h-12 text-center">
                </div>

                <button type="submit"
                    class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-blue-600 transition-colors shadow-lg shadow-slate-200 h-12">
                    Filtrer
                </button>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-3 bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-50 transition-colors flex items-center justify-center h-12">
                    Reset
                </a>
            </form>
        </div>
        {{-- --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            {{-- Total TopUp --}}
            <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden group">
                <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Argent Entrant (TopUp)
                </h3>
                <div class="flex items-baseline gap-1.5 mt-2">
                    <span
                        class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($totalTopUp, 2, ',', ' ') }}</span>
                    <span class="text-xs font-bold text-slate-400">PTS</span>
                </div>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-blue-50 rounded-tl-full opacity-50"></div>
            </div>

            {{-- Total Payment --}}
            <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden group">
                <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Argent Dépensé (CA)
                </h3>
                <div class="flex items-baseline gap-1.5 mt-2">
                    <span
                        class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($totalPayment, 2, ',', ' ') }}</span>
                    <span class="text-xs font-bold text-slate-400">PTS</span>
                </div>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-emerald-50 rounded-tl-full opacity-50"></div>
            </div>

            {{-- Trésorerie Dormante --}}
            <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden group">
                <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Trésorerie Restante
                </h3>
                <div class="flex items-baseline gap-1.5 mt-2">
                    <span
                        class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($dormant, 2, ',', ' ') }}</span>
                    <span class="text-xs font-bold text-slate-400">PTS</span>
                </div>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-amber-50 rounded-tl-full opacity-50"></div>
            </div>

            {{-- Bracelets Actifs --}}
            <div class="bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm relative overflow-hidden group">
                <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Bracelets Actifs
                </h3>
                <div class="flex items-baseline gap-1.5 mt-2">
                    <span
                        class="text-3xl font-black tracking-tighter text-slate-900">{{ number_format($activeWristbands, 0, ',', ' ') }}</span>
                    <span class="text-xs font-bold text-slate-400">Clients</span>
                </div>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-purple-50 rounded-tl-full opacity-50"></div>
            </div>

        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

            {{-- Graphique 1 --}}
            <div class="lg:col-span-2 bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Flux Entrants vs Sortants
                    </h3>
                </div>
                <div class="relative flex-grow min-h-[250px] w-full">
                    <canvas id="fluxChart"></canvas>
                </div>
            </div>

            {{-- Graphique 2 --}}
            <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm flex flex-col">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Répartition des
                    Fonds</h3>

                @if($totalTopUp > 0)
                    <div class="relative flex-grow min-h-[250px] w-full flex items-center justify-center">
                        <canvas id="ratioChart"></canvas>
                    </div>
                @else
                    <div class="flex-grow flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Aucun rechargement</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Graphique 3 --}}
        <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Top 5 des Événements</h3>

            @if(count($topEventNames) > 0)
                <div class="relative h-72 w-full">
                    <canvas id="topEventsChart"></canvas>
                </div>
            @else
                <div class="h-72 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Aucune dépense enregistrée</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        window.AdminDashboardConfig = {
            dateRange: @json($dateRange ?? ''),
            startDate: @json(\Carbon\Carbon::parse($startDate)->format('Y-m-d')),
            endDate: @json(\Carbon\Carbon::parse($endDate)->format('Y-m-d')),
            chartLabels: @json($chartLabels),
            dataTopUp: @json($chartDataTopUp),
            dataPayment: @json($chartDataPayment),
            topEventNames: @json($topEventNames),
            topEventRevenues: @json($topEventRevenues),
            totalPayment: {{ $totalPayment }},
            totalRefund: {{ $totalRefund }},
            dormant: {{ max(0, $dormant) }}
        };
    </script>
    @vite('resources/js/Admin/users/dashboard.js')
@endsection