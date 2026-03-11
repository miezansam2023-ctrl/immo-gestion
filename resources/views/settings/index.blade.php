@extends('layouts.app')

@section('title', 'Paramètres du Compte')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10" x-data="{ tab: 'profile' }">



        <div class="mb-10 flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Paramètres</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-indigo-500">
                    Gestion de votre compte et sécurité
                </p>
            </div>

            <div
                class="flex items-center bg-white border border-gray-100 px-4 py-2 rounded-2xl shadow-sm self-start md:self-center">
                <div class="flex items-center justify-center w-6 h-6 bg-indigo-50 rounded-lg mr-3">
                    <i class="fas fa-history text-[10px] text-indigo-400"></i>
                </div>
                <div class="flex flex-col">
                    <h4 class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                        Dernière mise à jour
                    </h4>

                    {{-- <span class="text-[10px] font-black text-indigo-600 uppercase leading-none">
                        {{ auth()->user()->updated_at ? auth()->user()->updated_at->diffForHumans() : 'Jamais' }}, 
                        {{ auth()->user()->updated_at ? auth()->user()->updated_at->translatedFormat('d F Y à H\hi') : 'Jamais' }}
                    </span> --}}
                    
                    <span class="text-[10px] font-black text-indigo-600 uppercase leading-none">
                        @if (auth()->user()->updated_at)
                            @php
                                $label = match (session('updated_section')) {
                                    'profile' => '· Profil',
                                    'password' => '· Sécurité',
                                    default => '',
                                };
                            @endphp
                            {{ auth()->user()->updated_at->diffForHumans() }},
                            {{ auth()->user()->updated_at->translatedFormat('d F Y à H\hi') }}
                            @if ($label)
                                <span class="text-indigo-400">{{ $label }}</span>
                            @endif
                        @else
                            Jamais
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-8">

            <div class="w-full md:w-72 space-y-2">
                <button @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'bg-blue-600 text-white shadow-xl shadow-blue-100' :
                        'bg-white text-gray-500 hover:bg-gray-50'"
                    class="w-full flex items-center px-6 py-4 rounded-[24px] transition-all duration-300 group">
                    <i class="fas fa-user-circle mr-3 text-[14px]"></i>
                    <span class="text-[11px] font-black uppercase tracking-widest">Mon Profil</span>
                </button>

                <button @click="tab = 'password'"
                    :class="tab === 'password' ? 'bg-gray-900 text-white shadow-xl shadow-gray-200' :
                        'bg-white text-gray-500 hover:bg-gray-50'"
                    class="w-full flex items-center px-6 py-4 rounded-[24px] transition-all duration-300 group">
                    <i class="fas fa-shield-alt mr-3 text-[14px]"></i>
                    <span class="text-[11px] font-black uppercase tracking-widest">Sécurité</span>
                </button>

                <div class="p-6 bg-indigo-50 rounded-[32px] mt-10">
                    <p class="text-[9px] font-black text-indigo-400 uppercase mb-2">Besoin d'aide ?</p>
                    <p class="text-[10px] text-indigo-900 font-bold leading-relaxed">Pour toute modification de rôle,
                        contactez l'administrateur système.</p>
                </div>
            </div>

            <div class="flex-1">

                <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                        <h2 class="text-xl font-black text-gray-800 mb-8 tracking-tighter">Informations Personnelles</h2>

                        <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-8">
                            @csrf
                            @method('PUT')

                            <div
                                class="flex flex-col md:flex-row gap-10 items-center md:items-start border-b border-gray-50 pb-10">
                                <!-- <div class="relative group" x-data="{ preview: null }">
                                    <div class="w-32 h-32 rounded-[40px] bg-gray-100 overflow-hidden border-4 border-white shadow-md relative">
                                        <template x-if="!preview">
                                            <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?background=EBF4FF&color=7F9CF5&name=' . urlencode(auth()->user()->name) }}"
                                                 class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="preview">
                                            <img :src="preview" class="w-full h-full object-cover">
                                        </template>
                                    </div>
                                    <label class="absolute -bottom-2 -right-2 bg-blue-600 text-white w-10 h-10 rounded-2xl flex items-center justify-center cursor-pointer hover:scale-110 transition-transform shadow-lg shadow-blue-200">
                                        <i class="fas fa-camera text-xs"></i>
                                        <input type="file" name="photo" class="hidden" @change="let file = $event.target.files[0]; if (file) { let reader = new FileReader(); reader.onload = (e) => { preview = e.target.result }; reader.readAsDataURL(file); }">
                                    </label>
                                </div> -->

                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Nom</label>
                                        <input type="text" name="nom" value="{{ old('nom', auth()->user()->nom) }}"
                                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Prénoms</label>
                                        <input type="text" name="prenoms"
                                            value="{{ old('prenoms', auth()->user()->prenoms) }}"
                                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Email
                                            professionnel</label>
                                        <input type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}"
                                            class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 hover:shadow-xl transition-all duration-300">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="tab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                        <h2 class="text-xl font-black text-gray-800 mb-2 tracking-tighter">Mot de passe</h2>

                        <form action="{{ route('settings.password.update') }}" method="POST" class="max-w-xl space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Mot de passe
                                    actuel</label>
                                <input type="password" name="current_password" required
                                    class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-red-400 transition-all">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nouveau
                                        mot de passe</label>
                                    <input type="password" name="password" required
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmation</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                                </div>
                            </div>

                            <div class="flex justify-start mt-10">
                                <button type="submit"
                                    class="bg-gray-900 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black hover:shadow-xl transition-all duration-300">
                                    Mettre à jour la sécurité
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
