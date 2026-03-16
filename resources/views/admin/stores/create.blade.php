@extends('layouts.admin')

@section('header')
    <div class="pb-6 pt-4 border-b border-gray-100">

        <a href="{{ route('admin.stores.index', $event->id) }}"
            class="inline-flex items-center gap-2 mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>

        <div class="flex items-baseline gap-3">
            <h1 class="text-xl font-black text-gray-900 tracking-tighter uppercase">
                Nouveau <span class="text-blue-600">Store</span>
            </h1>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">| Festival : {{ $event->name }}</p>
        </div>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto mt-8" x-data="{ imagePreview: null, imageFileName: null }">


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


            <div
                class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none">
            </div>


            <form action="{{ route('admin.stores.store', $event->id) }}" method="POST" enctype="multipart/form-data"
                class="p-10 relative">
                @csrf

                <div class="grid grid-cols-1 gap-8 mb-8">

                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nom de la
                            Boutique</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-blue-500 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <input type="text" name="name" required autofocus
                                class="w-full pl-14 pr-6 py-5 bg-gray-50 border-2 border-gray-50 rounded-2xl text-sm font-black focus:border-blue-600 focus:bg-white focus:ring-0 transition-all  tracking-widest ">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Vendeur
                                Responsable</label>

                            <a href="{{ route('admin.users.create', ['return_to_event' => $event->id]) }}"
                                class="text-[9px] font-black text-blue-600 hover:text-blue-800 hover:underline uppercase tracking-wider mb-1 transition-colors">
                                + Nouveau Vendeur
                            </a>
                        </div>

                        <div class="relative">
                            <select id="userSelect" name="user_id" class="w-full" required></select>
                        </div>
                        <p class="text-[9px] text-gray-400 ml-1">Tapez le nom ou l'email pour rechercher.</p>
                    </div>

                    <div class="space-y-6 flex flex-col items-center justify-center py-4">
    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Logo de la boutique</label>
    
    <div class="relative group">
        <div x-show="imagePreview" class="w-28 h-28 rounded-[2rem] border-4 border-white shadow-2xl overflow-hidden relative transition-transform duration-300 group-hover:scale-105" style="display: none;">
            <img :src="imagePreview" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
        </div>

        <div x-show="!imagePreview" class="w-28 h-28 rounded-[2rem] bg-gray-50 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center hover:bg-blue-50 hover:border-blue-300 transition-all cursor-pointer group-hover:scale-105">
            <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>

        <input name="logo" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" 
               @change="
                   if ($event.target.files && $event.target.files[0]) {
                       const reader = new FileReader();
                       reader.onload = (e) => { imagePreview = e.target.result; };
                       reader.readAsDataURL($event.target.files[0]);
                       imageFileName = $event.target.files[0].name;
                   } else {
                       imagePreview = null;
                       imageFileName = null;
                   }
               " />
    </div>

    <div class="text-center">
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Formats acceptés : JPG, PNG. Max 2Mo.</p>
        <p x-show="imageFileName" x-text="imageFileName" class="text-[10px] font-black text-blue-600 mt-2 uppercase tracking-tighter" style="display: none;"></p>
    </div>
</div>
                </div>

                <button type="submit"
                    class="w-full py-5 bg-gray-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-1 transition-all duration-300">
                    Créer la Boutique
                </button>
            </form>
        </div>

    </div>

    <script>
        $(document).ready(function () {

            var select = $('#userSelect').select2({
                placeholder: 'RECHERCHER UN VENDEUR...',
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


            @if(session('new_user_id'))
                var newOption = new Option("{{ session('new_user_name') }}", "{{ session('new_user_id') }}", true, true);
                select.append(newOption).trigger('change');
            @endif
        });



    </script>

    <style>
        .select2-container .select2-selection--single {
            height: 64px !important;
            background-color: #F9FAFB !important;
            border: 2px solid #F9FAFB !important;
            border-radius: 1rem !important;
            display: flex;
            align-items: center;
            padding-left: 0.5rem;
        }

        .select2-container--open .select2-selection--single {
            border-color: #2563EB !important;
            background-color: #FFFFFF !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827 !important;
            font-weight: 900 !important;
            font-size: 0.875rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #D1D5DB !important;
            font-weight: 900 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 60px !important;
            right: 1rem !important;
        }

        .select2-dropdown {
            border: 1px solid #F3F4F6 !important;
            border-radius: 1rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            margin-top: 8px !important;
        }
    </style>

@endsection