@extends('layouts.admin')

@section('header')
<div class="pb-6 pt-4 border-b border-gray-100">
    
    <a href="{{ route('admin.stores.index', $event->id) }}" class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Retour à la liste
    </a>

    <div class="flex items-baseline gap-3">
        <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase">
            Modifier <span class="text-blue-600">Store</span>
        </h1>
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">| Festival : {{ $event->name }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto mt-8">

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
            <ul class="list-disc list-inside text-xs font-bold text-red-600 uppercase tracking-tight">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-blue-500/5 overflow-hidden relative">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none"></div>

        <form action="{{ route('admin.stores.update', [$event->id, $store->id]) }}" method="POST" enctype="multipart/form-data" class="p-10 relative">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 mb-8">
                
                <div class="space-y-3">
                    <label for="name" class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nom de la Boutique</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-blue-500 group-focus-within:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $store->name) }}"
                               placeholder="Ex: Pizza Hut Stand A"
                               class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-blue-600 focus:bg-white focus:ring-0 transition-all uppercase tracking-widest placeholder-gray-300 text-gray-900">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Gérant Responsable</label>
                    
                    <select id="userSelect" name="user_id" class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl p-4">
                        @if($store->user)
                            <option value="{{ $store->user->id }}" selected>
                                {{ $store->user->name }} ({{ $store->user->email }})
                            </option>
                        @endif
                    </select>
                    <p class="text-[9px] text-gray-400 mt-2 ml-1">Tapez pour rechercher un autre vendeur si vous souhaitez changer l'affectation.</p>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Logo de la boutique</label>
                    <div class="flex items-center gap-6 p-4 border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50/50">
                        
                        <div class="w-16 h-16 rounded-xl bg-white border border-gray-100 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                            @if($store->logo)
                                <img src="{{ Storage::url($store->logo) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>

                        <div class="flex-1">
                            <input type="file" name="logo" class="block w-full text-[10px] text-gray-500 font-bold uppercase file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                            <p class="text-[9px] text-gray-400 mt-2">LAISSER VIDE POUR GARDER LE LOGO ACTUEL (JPG, PNG, MAX 2MO)</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Statut Opérationnel</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="active" class="peer sr-only" {{ $store->status === 'active' ? 'checked' : '' }}>
                            <div class="p-4 rounded-2xl border-2 border-gray-50 bg-gray-50 text-gray-400 text-center text-xs font-black uppercase tracking-widest peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all">
                                Actif
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="inactive" class="peer sr-only" {{ $store->status === 'inactive' ? 'checked' : '' }}>
                            <div class="p-4 rounded-2xl border-2 border-gray-50 bg-gray-50 text-gray-400 text-center text-xs font-black uppercase tracking-widest peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 transition-all">
                                Inactif
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.stores.index', $event->id) }}" class="w-1/3 py-5 text-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="w-2/3 py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-1 transition-all duration-300">
                    Mettre à jour
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#userSelect').select2({
            placeholder: 'Rechercher un vendeur...',
            ajax: {
                url: '{{ route("admin.users.search-vendors") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name + ' (' + item.email + ')',
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>

<style>
    .select2-container .select2-selection--single {
        height: 60px !important;
        background-color: #F9FAFB !important;
        border: 2px solid #F9FAFB !important;
        border-radius: 1rem !important; 
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important; 
        font-weight: 900 !important;
        font-size: 0.875rem !important; 
        text-transform: uppercase !important;
        padding-left: 1.5rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 60px !important;
        right: 1rem !important;
    }
</style>
@endsection