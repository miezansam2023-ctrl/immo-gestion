@extends('layouts.app')

@section('title', $locataire->reference)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

    {{-- BARRE DE NAVIGATION ET ACTIONS --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <a href="{{ route('locataires.index') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest flex items-center mb-2 hover:text-indigo-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
            <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Profil Locataire</h1>
            <!-- <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.2em] mt-1">ID Système : #LOC-{{ $locataire->id }}</p> -->
            <p class="text-emerald-600 font-bold uppercase text-[10px] tracking-[0.3em] mt-2 flex items-center">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-2"></span>
                Référence : <span class="font-mono ml-1">LOC-{{ $locataire->id }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('locataires.edit', $locataire->id) }}" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl font-black text-[10px] uppercase tracking-widest text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all shadow-sm">
                <i class="fas fa-edit mr-2 text-indigo-500"></i> Modifier le profil
            </a>
            <!-- <button class="w-12 h-12 flex items-center justify-center bg-gray-900 text-white rounded-2xl hover:bg-gray-800 transition-colors shadow-lg">
                <i class="fas fa-print"></i>
            </button> -->
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- COLONNE GAUCHE : CARTE D'IDENTITÉ --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gray-900 rounded-[3.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                {{-- Effet de fond décoratif --}}
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors duration-700"></div>

                <div class="relative z-10 flex flex-col items-center">
                    <span class="block text-[8px] font-black text-gray-400 uppercase">Inscrit le {{ $locataire->created_at->format('d/m/Y') }}</span>
                    <br>
                    {{-- Avatar --}}
                    <div class="relative mb-6">
                        <div class="w-28 h-28 rounded-[2.5rem] flex items-center justify-center text-4xl font-black bg-gradient-to-tr from-indigo-600 to-purple-600 shadow-2xl overflow-hidden border-4 border-gray-800 transform group-hover:rotate-3 transition-transform">
                            @if($locataire->photo && Storage::disk('public')->exists($locataire->photo))
                            <img src="{{ asset('storage/' . $locataire->photo) }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-white">{{ strtoupper(substr($locataire->nom, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 w-8 h-8 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <i class="fas fa-check text-[10px] text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black leading-tight tracking-tighter text-center uppercase">{{ $locataire->nom }}</h2>
                    <p class="text-indigo-400 font-bold italic text-xs tracking-widest uppercase mb-8">{{ $locataire->prenoms }}</p>

                    {{-- Data List --}}
                    <div class="w-full space-y-5">
                        <div class="flex flex-col bg-white/5 p-4 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Activité Professionnelle</span>
                            <span class="text-xs font-bold">{{ $locataire->profession ?? 'Non renseignée' }}</span>
                        </div>

                        <div class="flex flex-col bg-white/5 p-4 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Situation Matrimoniale</span>
                            <span class="text-xs font-bold">{{ strtoupper($locataire->situation_matrimoniale ?? 'Non renseignée') }}</span>
                        </div>

                        <div class="flex flex-col bg-white/5 p-4 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Coordonnées</span>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-indigo-300"><i class="fas fa-phone-alt mr-2 opacity-50"></i>{{ $locataire->telephone }}</span>
                                <span class="text-xs font-bold"><i class="fas fa-envelope mr-2 opacity-50"></i>{{ $locataire->email ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20">
                            <span class="text-[9px] font-black text-emerald-500/60 uppercase tracking-widest mb-1">Capacité Financière</span>
                            <span class="text-lg font-black text-emerald-400">{{ $locataire->revenus_mensuels ? number_format($locataire->revenus_mensuels, 0, ',', ' ') . ' FCFA' : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{-- SECTION DOCUMENTS --}}
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center justify-between">
                    <span>Justificatif d'identité</span>
                    <i class="fas fa-shield-alt text-indigo-500"></i>
                </h3>

                <div class="space-y-6">
                    @php
                    $rawPath = $locataire->copie_piece ?? $locataire->documents;
                    $cleanPath = null;
                    if (!empty($rawPath)) {
                    $decoded = is_array($rawPath) ? $rawPath : json_decode($rawPath, true);
                    $cleanPath = is_array($decoded) ? ($decoded[0] ?? null) : $rawPath;
                    }
                    @endphp

                    <div class="group relative aspect-video bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100 overflow-hidden transition-all hover:border-indigo-300">
                        @if($cleanPath && Storage::disk('public')->exists($cleanPath))
                        @php $extension = pathinfo($cleanPath, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                        <img src="{{ asset('storage/' . $cleanPath) }}" class="w-full h-full object-cover opacity-40 group-hover:opacity-100 transition-opacity">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-red-400">
                            <i class="fas fa-file-pdf text-4xl mb-2"></i>
                            <span class="text-[8px] font-black uppercase">Fichier PDF</span>
                        </div>
                        @endif
                        <a href="{{ asset('storage/' . $cleanPath) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-indigo-900/60 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="px-5 py-2 bg-white rounded-xl text-indigo-600 font-black text-[10px] uppercase shadow-2xl">Ouvrir le document</span>
                        </a>
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-200">
                            <i class="fas fa-file-excel text-4xl mb-2"></i>
                            <span class="text-[8px] font-black uppercase">Document manquant</span>
                        </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $locataire->type_piece ?? 'Type de pièce' }}</p>
                        <p class="text-sm font-mono font-black text-gray-800">{{ $locataire->numero_piece ?? '---' }}</p>
                    </div>
                </div>
            </div> -->
        </div>

        {{-- COLONNE DROITE : CONTRATS & NOTES --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- RÉSUMÉ DU BAIL --}}
            <div class="bg-white rounded-[3rem] p-10 border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8">
                    <i class="fas fa-file-contract text-6xl text-gray-50/50"></i>
                </div>

                <h3 class="text-[11px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-8 flex items-center">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span> Patrimoine Occupé
                </h3>

                @if($locataire->contrats && $locataire->contrats->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($locataire->contrats as $contrat)
                    <div class="group p-6 bg-white border border-gray-100 rounded-[2.5rem] hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/5 transition-all">
                        <div class="flex items-start gap-4">
                            {{-- Thumbnail Bien --}}
                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-100 flex-shrink-0 border-2 border-white shadow-md">
                                @php
                                $bien = $contrat->bien;
                                $photosArray = $bien && !empty($bien->photos) ? (is_array($bien->photos) ? $bien->photos : json_decode($bien->photos, true)) : [];
                                $photoPath = \Illuminate\Support\Arr::flatten($photosArray)[0] ?? null;
                                @endphp
                                @if($photoPath && Storage::disk('public')->exists($photoPath))
                                <img src="{{ asset('storage/' . $photoPath) }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-indigo-50">
                                    <i class="fas fa-home text-indigo-200"></i>
                                </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="block text-[8px] font-black text-emerald-500 uppercase tracking-widest mb-1">Contrat Actif</span>
                                <h4 class="text-sm font-black text-gray-900 truncate uppercase tracking-tighter">{{ $contrat->bien->titre ?? 'Sans titre' }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold mb-3"><i class="fas fa-map-marker-alt mr-1"></i> {{ $contrat->bien->commune }}</p>
                                <span class="text-xs font-black text-gray-500">PROPRIO : </span>
                                <span class="text-xs font-black text-gray-700">{{ $contrat->bien->nom_proprietaire ?? 'Propriétaire inconnu' }}</span>


                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <span class="text-xs font-black text-gray-500">LOYER : </span>
                                    <span class="text-xs font-black text-indigo-600">{{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }} <span class="text-[8px] opacity-60">CFA</span></span> <br>
                                    <!-- <a href="#" class="text-[9px] font-black uppercase text-gray-400 hover:text-indigo-600 transition-colors">Détails <i class="fas fa-chevron-right ml-1 text-[7px]"></i></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-16 flex flex-col items-center justify-center bg-gray-50 rounded-[2.5rem] border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                        <i class="fas fa-ghost text-gray-200 text-xl"></i>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aucune location enregistrée</p>
                    <button class="mt-4 px-5 py-2 bg-indigo-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all">Créer un bail</button>
                </div>
                @endif
            </div>

            {{-- URGENCE & NOTES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Contact d'urgence --}}
                <div class="bg-amber-50 rounded-[2.5rem] p-8 border border-amber-100">
                    <h3 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-6 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Contact d'urgence
                    </h3>
                    @if($locataire->personne_urgence_nom)
                    <div class="space-y-4">
                        <div>
                            <p class="text-[9px] font-black text-amber-900/40 uppercase">Nom & Relation</p>
                            <p class="text-xs font-bold text-amber-900">{{ $locataire->personne_urgence_nom }} ({{ $locataire->personne_urgence_lien }})</p>
                        </div>
                        <div class="inline-flex items-center px-4 py-2 bg-white rounded-xl shadow-sm border border-amber-200">
                            <i class="fas fa-phone-alt mr-2 text-amber-500 text-xs"></i>
                            <span class="text-xs font-black text-amber-900">{{ $locataire->personne_urgence_telephone }}</span>
                        </div>
                    </div>
                    @else
                    <p class="text-xs italic text-amber-700/50">Aucun contact d'urgence renseigné.</p>
                    @endif
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-indigo-500"></i> Observations
                    </h3>
                    <div class="text-xs text-gray-600 leading-relaxed italic">
                        {{ $locataire->notes ?? "Aucune note particulière pour ce profil." }}
                    </div>
                </div>
            </div>

            {{-- FOOTER STATS --}}
            <div class="flex items-center justify-between px-10 py-6 bg-gray-50 rounded-[2rem] border border-gray-100">
                <div class="flex gap-8">
                    <div>
                        {{-- SECTION DOCUMENTS --}}
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center justify-between">
                                <span>Justificatif d'identité</span>
                                <i class="fas fa-shield-alt text-indigo-500"></i>
                            </h3>

                            <div class="space-y-6">
                                @php
                                $rawPath = $locataire->copie_piece ?? $locataire->documents;
                                $cleanPath = null;
                                if (!empty($rawPath)) {
                                $decoded = is_array($rawPath) ? $rawPath : json_decode($rawPath, true);
                                $cleanPath = is_array($decoded) ? ($decoded[0] ?? null) : $rawPath;
                                }
                                @endphp

                                <div class="group relative aspect-video bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100 overflow-hidden transition-all hover:border-indigo-300">
                                    @if($cleanPath && Storage::disk('public')->exists($cleanPath))
                                    @php $extension = pathinfo($cleanPath, PATHINFO_EXTENSION); @endphp
                                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                    <img src="{{ asset('storage/' . $cleanPath) }}" class="w-full h-full object-cover opacity-40 group-hover:opacity-100 transition-opacity">
                                    @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-red-400">
                                        <i class="fas fa-file-pdf text-4xl mb-2"></i>
                                        <span class="text-[8px] font-black uppercase">Fichier PDF</span>
                                    </div>
                                    @endif
                                    <a href="{{ asset('storage/' . $cleanPath) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-indigo-900/60 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="px-5 py-2 bg-white rounded-xl text-indigo-600 font-black text-[10px] uppercase shadow-2xl">Ouvrir le document</span>
                                    </a>
                                    @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-200">
                                        <i class="fas fa-file-excel text-4xl mb-2"></i>
                                        <span class="text-[8px] font-black uppercase">Document manquant</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="bg-gray-50 p-4 rounded-2xl">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $locataire->type_piece ?? 'Type de pièce' }}</p>
                                    <p class="text-sm font-mono font-black text-gray-800">{{ $locataire->numero_piece ?? '---' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection