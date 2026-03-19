@extends('layouts.store')

@section('header_scripts')
    <style>
        .flatpickr-calendar {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .flatpickr-day.selected {
            background: #3f3e4b !important;
            border-color: #31304d !important;
        }

        .flatpickr-day.inRange {
            background: #f0f3ff !important;
            box-shadow: -5px 0 0 #f0f3ff, 5px 0 0 #f0f3ff !important;
        }
    </style>
@endsection

@section('content')
    <div class="p-8 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Journal des ventes</h1>

                    <div
                        class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                        Période : {{ request('date_range') ?: 'Aujourd\'hui' }}
                    </div>
                </div>

                <form action="{{ route('store.orders.index', $store) }}" method="GET"
                    class="flex gap-2 bg-white p-1.5 rounded-xl shadow-sm border border-slate-200">
                    <input type="text" name="date_range" id="booking_picker" value="{{ request('date_range') }}"
                        placeholder="Filtrer par dates..."
                        class="w-64 pl-4 py-2 bg-transparent border-none text-sm font-medium text-slate-700 focus:ring-0 cursor-pointer">
                    <button type="submit"
                        class="bg-slate-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-slate-700 transition-colors">
                        Appliquer
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div
                    class="bg-gradient-to-br from-white to-slate-50 p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-white">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Revenus de la période</span>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-slate-600">{{ number_format($totalRevenue, 2) }}</span>
                        <span class="text-sm font-medium text-slate-400">DH</span>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-br from-white to-slate-50 p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-white">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Volume de
                        transactions</span>
                    <div class="mt-2">
                        <span class="text-3xl font-bold text-slate-900">{{ $orders->total() }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                @php 
                    $groupedOrders = $orders->groupBy(fn($order) => $order->created_at->format('d F Y'));
                @endphp

                @forelse($groupedOrders as $dateDisplay => $dayOrders)
                    @php
                        $dateKey = $dayOrders->first()->created_at->format('Y-m-d');
                        $realDailyTotal = $dailyTotals[$dateKey] ?? 0;
                    @endphp

                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-4">
                            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $dateDisplay }}</h2>
                            <span class="text-xs font-medium text-slate-400">
                                {{-- On affiche le total réel calculé en base de données --}}
                                Total réel du jour : <span class="text-slate-600 font-bold">{{ number_format($realDailyTotal, 2) }} DH</span>
                            </span>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden text-sm">
                            <table class="w-full text-left border-collapse">
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($dayOrders as $order)
                                    {{-- On utilise x-data sur un tbody pour isoler chaque ligne et son accordéon --}}
                                    <tbody class="border-none" x-data="{ open: false }">
                                        <tr class="hover:bg-indigo-50/30 transition-all duration-200 cursor-pointer group" @click="open = !open">
                                            <td class="px-6 py-4 font-semibold text-slate-500">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="px-6 py-4 font-medium text-slate-400">{{ $order->created_at->format('H:i') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-2.5 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600">
                                                    {{ $order->items->count() }} items
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-slate-600 text-base">
                                                {{ number_format($order->total_points, 2) }} <span class="text-[10px]">DH</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <svg class="w-4 h-4 text-slate-300 transition-transform duration-300" :class="open ? 'rotate-180 text-indigo-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </td>
                                        </tr>
                                        <tr x-show="open" x-cloak x-transition>
                                            <td colspan="5" class="px-8 py-6 bg-slate-50/80 border-t border-slate-100 shadow-inner">
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($order->items as $item)
                                                    <div class="bg-white p-3 rounded-xl border border-slate-200 flex justify-between items-center shadow-sm">
                                                        <div>
                                                            <span class="text-[10px] font-bold text-indigo-500 uppercase">{{ $item->quantity }}x</span>
                                                            <span class="ml-1 text-xs font-semibold text-slate-700 uppercase tracking-tight">{{ $item->product->name }}</span>
                                                        </div>
                                                        <span class="text-[10px] font-bold text-slate-400">{{ number_format($item->unit_price, 2) }} DH</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-slate-200 text-slate-400">
                        Aucune vente enregistrée sur cette période.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.flatpickr !== 'undefined') {
            flatpickr("#booking_picker", {
                mode: "range",
                showMonths: 2,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                locale: "fr",
                maxDate: "today",
                conjunction: " au ",
                disableMobile: true
            });
        }
    });
</script>
@endpush
