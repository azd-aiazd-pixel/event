@extends('layouts.admin')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center p-8">
 
    <div class="w-24 h-24 bg-purple-50 rounded-full flex items-center justify-center mb-6 text-purple-600">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
    </div>

    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-2">
        Fonctionnalité <span class="text-purple-600">En cours</span>
    </h1>
    


    <a href="{{ url()->previous() }}" class="px-8 py-3 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-purple-600 transition-all shadow-xl shadow-gray-200">
        Retour en arrière
    </a>
</div>
@endsection