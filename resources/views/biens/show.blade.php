@extends('layouts.app')
@section('title', '' . $bien->reference)
@section('content')
    <div class="p-4 md:p-8 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- Barre de Navigation Supérieure --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <a href="{{ route('biens.index') }}"
                        class="group inline-flex items-center text-sm font-bold text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        RETOUR AU PATRIMOINE
                    </a>
                    <h2 class="text-2xl font-black text-[#1E293B] mt-2 uppercase tracking-tight">Détails du Patrimoine</h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('biens.edit', $bien) }}"
                        class="flex items-center bg-white text-[#1E293B] border border-gray-200 px-6 py-3 rounded-2xl font-bold hover:bg-gray-50 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        MODIFIER LE BIEN
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- BLOC GAUCHE (8 colonnes) : IMAGE ET INFOS TECHNIQUES --}}
                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

                        {{-- IMAGE PRINCIPALE & GALERIE DYNAMIQUE --}}
                        <div class="relative bg-gray-100" x-data="{
                            activePhoto: 0,
                            photos: {{ json_encode(is_array($bien->photos) ? $bien->photos : json_decode($bien->photos, true) ?? []) }}
                        }">
                            {{-- Affichage de la photo active --}}
                            <div class="relative aspect-video overflow-hidden group">
                                <template x-if="photos.length > 0">
                                    <img :src="'/storage/' + photos[activePhoto]"
                                        class="w-full h-full object-cover transition-all duration-700 ease-in-out transform scale-100 group-hover:scale-105"
                                        :key="activePhoto">
                                </template>

                                <template x-if="photos.length === 0">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span class="font-black uppercase tracking-tighter">Aucune photo disponible</span>
                                    </div>
                                </template>

                                {{-- Badge Statut --}}
                                <div class="absolute top-8 left-8 z-20">
                                    <span
                                        class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-2xl {{ $bien->statut == 'disponible' ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}">
                                        ● {{ $bien->statut }}
                                    </span>
                                </div>

                                {{-- Navigation Flèches (si plusieurs photos) --}}
                                <template x-if="photos.length > 1">
                                    <div
                                        class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            @click="activePhoto = activePhoto === 0 ? photos.length - 1 : activePhoto - 1"
                                            class="bg-white/20 backdrop-blur-md hover:bg-white/40 text-white p-3 rounded-full transition-all">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button
                                            @click="activePhoto = activePhoto === photos.length - 1 ? 0 : activePhoto + 1"
                                            class="bg-white/20 backdrop-blur-md hover:bg-white/40 text-white p-3 rounded-full transition-all">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            {{-- Miniatures (Thumbnails) --}}
                            <template x-if="photos.length > 1">
                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-30">
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <button @click="activePhoto = index"
                                            class="w-12 h-12 rounded-xl border-2 overflow-hidden transition-all shadow-lg"
                                            :class="activePhoto === index ? 'border-indigo-500 scale-110' :
                                                'border-white/50 scale-100 hover:scale-105'">
                                            <img :src="'/storage/' + photo" class="w-full h-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- CONTENU TECHNIQUE --}}
                        <div class="p-8 md:p-12">
                            <div class="mb-10">
                                <h1 class="text-4xl font-black text-[#1E293B] mb-2 uppercase italic leading-none">
                                    {{ $bien->titre }}</h1>
                                <p class="text-gray-400 flex items-center font-bold text-sm">
                                    Rue, Lot, Ilot : {{ $bien->adresse }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
                                <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Référence</p>
                                    <p class="font-black text-[#1E293B]">{{ $bien->reference }}</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Type</p>
                                    <p class="font-black text-[#1E293B] uppercase">{{ $bien->type }}</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Loyer du bien</p>
                                    @php $contratActif = $bien->contrats->where('statut', 'actif')->first(); @endphp
                                    <p class="font-black text-indigo-600">
                                        {{ number_format($contratActif ? $contratActif->loyer_mensuel : $bien->prix_loyer, 0, ',', ' ') }}
                                        <span class="text-[8px]">FCFA</span>
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Pièces</p>
                                    <p class="font-black text-[#1E293B]">
                                        {{ $bien->nombre_pieces ?? ($bien->nb_pieces ?? 'N/A') }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3
                                    class="text-[11px] font-black text-gray-400 uppercase tracking-widest flex items-center">
                                    <span class="w-8 h-[2px] bg-indigo-500 mr-3"></span> Description du bien
                                </h3>
                                <div class="text-gray-600 font-medium leading-relaxed bg-gray-50/50 p-6 rounded-3xl italic">
                                    {{ $bien->description ?: 'Aucune description détaillée disponible pour ce bien.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BLOC DROIT (4 colonnes) : LOCATAIRE ET FINANCE --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- CARTE LOCATAIRE STYLE "DASHBOARD" --}}
                    <div class="bg-[#1E293B] rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8">Situation
                                Locative</h3>

                            @if ($contratActif)
                                <div class="flex flex-col items-center text-center">
                                    <div class="relative mb-6">
                                        <div class="h-28 w-28 rounded-full border-4 border-indigo-500/30 p-1">
                                            @if ($contratActif->locataire->photo)
                                                <img src="{{ asset('storage/' . $contratActif->locataire->photo) }}"
                                                    class="w-full h-full rounded-full object-cover shadow-2xl">
                                            @else
                                                <div
                                                    class="w-full h-full bg-indigo-600 rounded-full flex items-center justify-center text-3xl font-black italic">
                                                    {{ substr($contratActif->locataire->nom, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div
                                            class="absolute bottom-1 right-1 h-6 w-6 bg-green-500 border-4 border-[#1E293B] rounded-full">
                                        </div>
                                    </div>

                                    <p class="text-xs text-indigo-300 font-black uppercase tracking-tighter mb-1">Locataire
                                        Actuel</p>
                                    <p class="text-2xl font-black uppercase italic mb-8 tracking-tight">
                                        {{ $contratActif->locataire->nom }} {{ $contratActif->locataire->prenoms }}</p>

                                    <div class="w-full grid grid-cols-1 gap-3 mb-8">
                                        <div
                                            class="bg-white/5 border border-white/5 p-4 rounded-2xl flex justify-between items-center">
                                            <span class="text-xs font-bold text-gray-400 uppercase">Depuis le</span>
                                            <span
                                                class="font-black text-sm">{{ \Carbon\Carbon::parse($contratActif->date_debut)->format('d/m/Y') }}</span>
                                        </div>
                                        <div
                                            class="bg-white/5 border border-white/5 p-4 rounded-2xl flex justify-between items-center">
                                            <span class="text-xs font-bold text-gray-400 uppercase">Loyer mensuel</span>
                                            <span
                                                class="font-black text-indigo-400">{{ number_format($contratActif->loyer_mensuel, 0, ',', ' ') }}
                                                <span class="text-[10px]">CFA</span></span>
                                        </div>
                                    </div>

                                    <a href="{{ route('contrats.show', $contratActif) }}"
                                        class="group w-full bg-indigo-600 hover:bg-indigo-700 py-4 rounded-[1.2rem] font-black transition-all text-[10px] uppercase tracking-widest shadow-xl flex items-center justify-center">
                                        VOIR LE CONTRAT
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div
                                        class="w-20 h-20 bg-green-500/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-green-400 font-black text-xl italic uppercase">Libre de suite</p>
                                    <p class="text-gray-500 text-[10px] font-bold mt-2 uppercase tracking-widest">Aucun
                                        contrat actif</p>
                                </div>
                            @endif
                        </div>
                        {{-- Effet de lumière --}}
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
