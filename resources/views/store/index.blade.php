@extends('layouts.store')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">
    
   
    <div class="mb-12 border-b border-slate-100 pb-8 flex items-baseline justify-between">
        <h1 class="text-3xl font-black tracking-tighter uppercase text-slate-900 leading-none">
            Sélection <span class="text-slate-400">Terminal</span>
        </h1>
        <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Session Active</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($stores as $store)
          <a href="{{ route('store.dashboard', $store->id) }}" class="block w-full h-full text-left group perspective">
    <div class="relative h-full bg-white border border-slate-100 p-8 rounded-[3rem] transition-all duration-500 
                shadow-[0_20px_50px_rgba(0,0,0,0.02)] 
                group-hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] 
                group-hover:-translate-y-2 group-active:scale-95 flex flex-col">
        
        {{-- Top Part: Logo & Status --}}
        <div class="flex justify-between items-start mb-10">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 group-hover:border-black/10 transition-colors overflow-hidden">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xl font-black text-slate-300 group-hover:text-black transition-colors">{{ substr($store->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
              
             @if($store->isActive())
               <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[8px] font-bold text-emerald-600 uppercase tracking-widest">Active</span>
                @else
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>       
                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Inactive</span>
                @endif
                
            </div>
        </div>

        {{-- Middle Part: Info --}}
        <div class="flex-grow">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $store->event->name }}
            </p>
            <h2 class="text-2xl font-black uppercase tracking-tighter text-slate-900 group-hover:text-black transition-colors">
                {{ $store->name }}
            </h2>
        </div>

        {{-- Bottom Part: Action --}}
        <div class="mt-12 pt-6 border-t border-slate-50 flex items-center justify-between">
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-300 group-hover:text-slate-900 transition-colors">
                Ouvrir le terminal
            </span>
            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-500 shadow-lg shadow-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </div>

    </div>
</a>
        @empty
            <div class="col-span-full py-20 bg-white/50 rounded-[3rem] border-2 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Aucune boutique disponible</p>
            </div>
        @endforelse
    </div>
</div>
@endsection