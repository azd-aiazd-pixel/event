@extends('layouts.participant')

@section('title', 'Ticket de Caisse')

@section('content')
<div class="max-w-md mx-auto w-full pb-8">
    
    

    <div class="bg-white rounded-[2rem] shadow-sm border border-zinc-100 overflow-hidden relative">
        
        <div class="pt-8 pb-6 px-6 flex flex-col items-center border-b-2 border-zinc-100 border-dashed relative">
            
            <div class="absolute -bottom-3 -left-3 w-6 h-6 bg-zinc-50 rounded-full border-r border-zinc-100"></div>
            <div class="absolute -bottom-3 -right-3 w-6 h-6 bg-zinc-50 rounded-full border-l border-zinc-100"></div>

            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-4 border-[6px] border-emerald-50 shadow-inner">
                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-black text-zinc-900 mb-2">Paiement Validé</h2>
            
            <span class="bg-zinc-100 text-zinc-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mt-1">
                Commande #{{ $order->id }}
            </span>
        </div>

        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-1">Boutique</p>
                <p class="text-xl font-black text-zinc-900">{{ $order->store->name ?? 'Stand du Festival' }}</p>
                <p class="text-xs text-zinc-400 mt-1">{{ $order->created_at->format('d/m/Y • H:i') }}</p>
            </div>

            <div class="bg-zinc-50 rounded-2xl p-5 mb-6 border border-zinc-100">
                <ul class="space-y-4">
                    @foreach($order->items as $item)
                    <li class="flex justify-between items-center">
                        <div class="flex-grow pr-4">
                            <span class="block text-zinc-900 font-bold text-sm leading-tight">{{ $item->product->name }}</span>
                            <span class="text-zinc-500 text-xs font-semibold mt-0.5 block">{{ $item->quantity }} x {{ $item->unit_price }} Pts</span>
                        </div>
                        <span class="text-zinc-900 font-black text-sm">{{ $item->unit_price * $item->quantity }} Pts</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex justify-between items-center pt-2 px-1">
                <span class="text-zinc-400 font-bold uppercase tracking-wider text-sm">Total</span>
                <span class="text-3xl font-black text-zinc-900">{{ $order->total_points }} <span class="text-lg text-zinc-400 font-bold">Pts</span></span>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('participant.stores.index') }}" class="w-full flex items-center justify-center bg-zinc-900 text-white font-bold py-4 px-6 rounded-2xl hover:bg-zinc-800 active:scale-95 transition-all shadow-xl shadow-zinc-900/20 text-lg">
            Continuer mes achats
        </a>
    </div>

</div>
@endsection