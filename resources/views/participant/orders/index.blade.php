@extends('layouts.participant')

@section('title', 'Mon Historique')

@section('content')
<div class="max-w-md mx-auto w-full pb-8">
    
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-zinc-900 tracking-tight">Mes Achats</h1>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-zinc-100">
            <div class="text-4xl mb-4">🛒</div>
            <h3 class="text-lg font-bold text-zinc-800">Aucun achat pour le moment</h3>
            <p class="text-zinc-500 text-sm mt-2">Vos commandes apparaîtront ici.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('participant.orders.show', $order->id) }}" class="block bg-white rounded-2xl p-5 shadow-sm border border-zinc-100 hover:shadow-md active:scale-[0.98] transition-all">
                    <div class="flex justify-between items-center mb-3">
                        <span class="bg-zinc-100 text-zinc-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                            #{{ $order->id }}
                        </span>
                        <span class="text-zinc-400 text-xs font-semibold">{{ $order->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-end">
                        <div>
                            <h3 class="font-bold text-zinc-900">{{ $order->store->name ?? 'Boutique' }}</h3>
                            <p class="text-xs text-zinc-500 mt-1">{{ $order->items->count() }} article(s)</p>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-lg text-zinc-900">{{ $order->total_points }} <span class="text-xs text-zinc-400">Pts</span></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection