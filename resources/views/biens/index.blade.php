@extends('layouts.app')

@section('title', 'Gestion des Biens')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ openModal: false }">

        {{-- Header avec Bouton Ajouter --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Parc Immobilier</h1>
                <p class="flex items-center text-indigo-600 font-bold uppercase text-[10px] tracking-[0.3em] mt-2">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-ping mr-2"></span>
                    Liste des biens enregistrés
                </p>
            </div>
            {{-- Filtres de recherche --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full max-w-2xl">
                {{-- Barre de recherche --}}
                <form action="{{ route('biens.index') }}" method="GET" class="relative flex-1 group">
                    <div class="relative flex items-center">
                        <span class="absolute left-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="fas fa-search text-sm"></i>
                        </span>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher un bien, une ville..."
                            class="w-full bg-white border border-gray-100 py-5 pl-14 pr-28 rounded-[2rem] shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-black text-[11px] uppercase tracking-wider text-slate-700 placeholder:text-slate-300 placeholder:font-bold transition-all outline-none italic">

                        <div class="absolute right-2">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-5 py-3 rounded-[1.5rem] font-black text-[9px] uppercase tracking-widest hover:bg-gray-900 transition-all active:scale-95">
                                Rechercher
                            </button>
                        </div>
                    </div>

                    {{-- Garder le paramètre statut si présent --}}
                    @if(request('statut') && request('statut') !== 'tous')
                        <input type="hidden" name="statut" value="{{ request('statut') }}">
                    @endif

                    @if (request('search'))
                        @if ($biens->isEmpty())
                            {{-- Cas : Aucun résultat trouvé --}}
                            <a href="{{ route('biens.index') }}"
                                class="absolute -bottom-6 left-6 text-[9px] font-black text-red-500 uppercase tracking-tighter hover:text-red-700 transition-colors">
                                <i class="fas fa-times-circle mr-1"></i> Aucun élément trouvé pour
                                "{{ request('search') }}". Réinitialiser la recherche.
                            </a>
                        @else
                            {{-- Cas : Résultats trouvés, on affiche un bouton pour annuler --}}
                            <a href="{{ route('biens.index') }}"
                                class="absolute -bottom-6 left-6 text-[9px] font-black text-emerald-500 uppercase tracking-tighter hover:text-emerald-700 transition-colors">
                                <i class="fas fa-check-circle mr-1"></i> {{ $biens->count() }} résultat(s) trouvé(s).
                                Cliquez pour effacer.
                            </a>
                        @endif
                    @endif
                </form>

                {{-- Filtre par statut --}}
                <form action="{{ route('biens.index') }}" method="GET" class="relative">
                    <select name="statut" onchange="this.form.submit()"
                        class="bg-white border border-gray-100 py-5 px-6 pr-12 rounded-[2rem] shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-black text-[11px] uppercase tracking-wider text-slate-700 transition-all outline-none appearance-none">
                        <option value="tous" {{ request('statut') == 'tous' || !request('statut') ? 'selected' : '' }}>
                            Tous les statuts
                        </option>
                        <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>
                            Disponible
                        </option>
                        <option value="occupe" {{ request('statut') == 'occupe' ? 'selected' : '' }}>
                            Occupé
                        </option>
                        <option value="maintenance" {{ request('statut') == 'maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>
                    </select>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </span>
                    {{-- Garder le paramètre search si présent --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
            </div>

            <button @click="openModal = true"
                class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-indigo-700 hover:-translate-y-1 transition-all shadow-lg shadow-indigo-100 flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>  Ajouter un nouveau bien
            </button>
            
        </div>

        {{-- DATA TABLE (La Vue Principale) --}}
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informations du
                            Bien</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Localisation
                        </th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Propriétaire
                        </th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                            Statut</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Loyer Mensuel
                        </th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($biens as $bien)
                        <tr class="hover:bg-indigo-50/20 transition-colors group">
                            {{-- COLONNE 1 : IMAGE + TITRE + PIÈCES --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 overflow-hidden border border-gray-100 shadow-sm">
                                        @php
                                            $rawPhotos = $bien->photos;
                                            $finalPath = null;
                                            if (!empty($rawPhotos)) {
                                                $flatArray = \Illuminate\Support\Arr::flatten(
                                                    is_array($rawPhotos) ? $rawPhotos : json_decode($rawPhotos, true),
                                                );
                                                $finalPath = $flatArray[0] ?? null;
                                            }
                                        @endphp
                                        @if ($finalPath)
                                            <img src="{{ asset('storage/' . $finalPath) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-building text-xl"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 uppercase text-[13px] leading-tight mb-1">
                                            {{ $bien->titre }}</div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[9px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded font-black uppercase">{{ $bien->type }}</span>
                                            <span
                                                class="text-[9px] text-indigo-500 font-bold uppercase">{{ $bien->nombre_pieces }}
                                                Pièces • {{ $bien->superficie }} m²</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- COLONNE 2 : LOCALISATION --}}
                            <td class="px-8 py-6">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-300 mt-1 text-xs"></i>
                                    <div>
                                        <div class="text-[11px] font-black text-gray-700 uppercase leading-tight">
                                            {{ $bien->commune }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                            {{ $bien->quartier }}, {{ $bien->ville }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- COLONNE 3 : PROPRIÉTAIRE --}}
                            <td class="px-8 py-6">
                                <div class="font-bold text-gray-900 uppercase text-xs">{{ $bien->nom_proprietaire }}</div>
                                <div class="text-[10px] text-gray-400 font-medium">{{ $bien->telephone_proprietaire }}
                                </div>
                            </td>

                            {{-- COLONNE 4 : STATUT --}}
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="inline-block px-4 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest {{ $bien->statut == 'disponible' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                    {{ $bien->statut }}
                                </span>
                            </td>

                            {{-- COLONNE 5 : PRIX --}}
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-indigo-600 italic">
                                    {{ number_format($bien->prix_loyer, 0, ',', ' ') }} <span
                                        class="text-[10px] not-italic text-gray-400">CFA</span>
                                </div>
                            </td>

                            {{-- COLONNE 6 : ACTIONS --}}
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('biens.show', $bien) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm"><i
                                            class="fas fa-eye text-xs"></i></a>
                                    <a href="{{ route('biens.edit', $bien->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"><i
                                            class="fas fa-edit text-xs"></i></a>
                                    <form action="{{ route('biens.destroy', $bien->id) }}" method="POST" class="inline"
                                        onsubmit="event.preventDefault(); confirmDelete('Êtes-vous sûr de vouloir supprimer ce bien ?').then(confirmed => { if(confirmed) this.submit(); })">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-300 hover:bg-red-50 hover:text-red-500 transition-all shadow-sm"><i
                                                class="fas fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $biens->links() }}
        </div>

        {{-- MODAL DU FORMULAIRE --}}
        <div x-show="openModal" class="fixed inset-0 z-[100] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" style="display: none;">

            {{-- Overlay sombre --}}
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="openModal = false"></div>

            {{-- Contenu du Modal --}}
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-[3rem] bg-gray-50 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl">

                    {{-- Header du Modal --}}
                    <div class="bg-white px-10 py-6 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Nouveau Bien Immobilier
                            </h2>
                            <p class="text-indigo-600 font-bold uppercase text-[9px] tracking-widest">Veuillez remplir tous
                                les champs obligatoires</p>
                        </div>
                        <button @click="openModal = false"
                            class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- Corps du Formulaire --}}
                    <div class="p-10 max-h-[75vh] overflow-y-auto">
                        <form id="main-form" action="{{ route('biens.store') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-8">
                            @csrf

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                                <div class="lg:col-span-8 space-y-8">

                                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                        <h3
                                            class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-8 flex items-center">
                                            <span
                                                class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center mr-3"><i
                                                    class="fas fa-home"></i></span>
                                            Caractéristiques du Bien
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Titre
                                                    / Désignation du Bien *</label>
                                                <input type="text" name="titre" value="{{ old('titre') }}"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500"
                                                    required>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Type
                                                    de Bien</label>
                                                <select name="type"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500">
                                                    <option value="villa">Villa</option>
                                                    <option value="appartement">Appartement</option>
                                                    <option value="studio">Studio</option>
                                                    <option value="magasin">Magasin</option>
                                                    <option value="bureau">Bureau</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Superficie
                                                    (m²)</label>
                                                <input type="number" step="0.01" name="superficie"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                                            </div>

                                            <div
                                                class="grid grid-cols-3 gap-4 md:col-span-2 bg-indigo-50/50 p-6 rounded-3xl">
                                                <div>
                                                    <label
                                                        class="block text-[9px] font-black text-indigo-400 uppercase mb-2 text-center">Pièces</label>
                                                    <input type="number" name="nombre_pieces"
                                                        class="w-full px-5 py-3 bg-white border-none rounded-xl text-center font-black text-indigo-600">
                                                </div>
                                                
                                                <div>
                                                    <label
                                                        class="block text-[9px] font-black text-indigo-400 uppercase mb-2 text-center">Salles
                                                        de bain</label>
                                                    <input type="number" name="nombre_salles_bain"
                                                        class="w-full px-5 py-3 bg-white border-none rounded-xl text-center font-black text-indigo-600">
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Étage
                                                    (Si appart.)</label>
                                                <input type="number" name="etage"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                                            </div>

                                            <div class="flex items-center space-x-4 px-5">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" name="meuble" value="1"
                                                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span
                                                        class="ml-3 text-[10px] font-black text-gray-500 uppercase tracking-widest">Bien
                                                        Meublé</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                        <h3
                                            class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-8 flex items-center">
                                            <span
                                                class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mr-3"><i
                                                    class="fas fa-plug"></i></span>
                                            Équipements & Commodités
                                        </h3>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @foreach (['Piscine', 'Garage', 'Groupe Élec.', 'Ascenseur', 'Climatisation', 'Sécurité 24h/7', 'Wifi', 'Balcon', 'Cuisine Équipée', 'Parking Visiteur'] as $item)
                                                <label
                                                    class="flex items-center p-4 bg-gray-50 rounded-2xl cursor-pointer hover:bg-emerald-50 transition-colors group">
                                                    <input type="checkbox" name="equipements[]"
                                                        value="{{ $item }}"
                                                        class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                                    <span
                                                        class="ml-3 text-[10px] font-bold text-gray-400 group-hover:text-emerald-700 uppercase tracking-tight">{{ $item }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                        <h3
                                            class="text-xs font-black text-blue-600 uppercase tracking-widest mb-8 flex items-center">
                                            <span
                                                class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3"><i
                                                    class="fas fa-map-marked-alt"></i></span>
                                            Localisation
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Rue/Lot/Ilot
                                                    *</label>
                                                <input type="text" name="adresse"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700"
                                                    required>
                                            </div>
                                            <div class="grid grid-cols-3 md:col-span-2 gap-4">
                                                <input type="text" name="quartier" placeholder="Quartier"
                                                    class="px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                                                <input type="text" name="commune" placeholder="Commune"
                                                    class="px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                                                <input type="text" name="ville" placeholder="Ville"
                                                    class="px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        
                                        <div class="bg-white p-8 rounded-[2rem] border-2 border-dashed border-gray-200"
                                            x-data="{
                                                images: [],
                                                handleFiles(event) {
                                                    const files = Array.from(event.target.files);
                                                    // On vérifie le quota de 5
                                                    if (this.images.length + files.length > 5) {
                                                        alert('Maximum 5 photos autorisées');
                                                        return;
                                                    }
                                                    files.forEach((file) => {
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => {
                                                            this.images.push({
                                                                src: e.target.result,
                                                                file: file // Utile si tu veux gérer l'envoi via AJAX plus tard
                                                            });
                                                        };
                                                        reader.readAsDataURL(file);
                                                    });
                                                },
                                                removePhoto(index) {
                                                    this.images.splice(index, 1);
                                                }
                                            }">

                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-4 text-center">
                                                Photos du bien (Max 5 - Glissez pour ordonner ou cliquez pour supprimer)
                                            </label>

                                            <div class="flex items-center justify-center mb-6">
                                                <label
                                                    class="cursor-pointer bg-indigo-50 text-indigo-700 px-6 py-3 rounded-full font-black text-[10px] uppercase hover:bg-indigo-100 transition-all">
                                                    Choisir des fichiers
                                                    <input type="file" name="photos[]" multiple accept="image/*"
                                                        class="hidden" @change="handleFiles">
                                                </label>
                                                <span class="ml-4 text-xs font-bold text-gray-400"
                                                    x-text="images.length + ' fichiers sélectionnés'"></span>
                                            </div>

                                            {{-- Grille de prévisualisation --}}
                                            <div class="grid grid-cols-5 gap-4 mt-6" x-show="images.length > 0">
                                                <template x-for="(img, index) in images" :key="index">
                                                    <div class="relative group aspect-[3/4]">
                                                        <img :src="img.src"
                                                            class="h-full w-full object-cover rounded-2xl border border-gray-100 shadow-sm">

                                                        {{-- Badge Icône (dynamique : tjs sur le premier de la liste) --}}
                                                        <template x-if="index === 0">
                                                            <span
                                                                class="absolute -top-2 -left-2 bg-emerald-500 text-white text-[8px] font-black px-2 py-1 rounded-lg uppercase shadow-lg z-10">Icône</span>
                                                        </template>

                                                        {{-- Bouton Supprimer --}}
                                                        <button type="button" @click="removePhoto(index)"
                                                            class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 hover:scale-110 transition-all z-20">
                                                            <i class="fas fa-times text-[10px]"></i>
                                                        </button>

                                                        {{-- Overlay avec numéro --}}
                                                        <div
                                                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center pointer-events-none">
                                                            <span class="text-white text-xs font-black"
                                                                x-text="index + 1"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="bg-white p-8 rounded-[2.5rem] border border-dashed border-gray-200">
                                            <h4
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
                                                Documents (Titre, Plan...)</h4>
                                            <input type="file" name="documents[]" multiple
                                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:col-span-4 space-y-8">

                                    <div
                                        class="bg-gray-900 p-8 rounded-[2.5rem] text-white shadow-2xl overflow-hidden relative">
                                        <h3 class="font-black text-xl uppercase tracking-tighter mb-8">Finances HT</h3>
                                        <div class="space-y-6 relative z-10">
                                            <div>
                                                <label
                                                    class="block text-[9px] font-black uppercase text-white/40 tracking-[0.2em] mb-2 text-right">Loyer
                                                    Mensuel</label>
                                                <div class="relative">
                                                    <input type="number" name="prix_loyer"
                                                        class="w-full bg-white/10 border-none rounded-2xl py-4 px-6 text-3xl font-black text-right focus:ring-2 focus:ring-indigo-400"
                                                        required>
                                                    <span class="absolute left-6 top-6 text-white/20 font-black">CFA</span>
                                                </div>
                                            </div>
                                            {{-- <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-[9px] font-black uppercase text-white/40 mb-2">Caution</label>
                                                    <input type="number" name="prix_caution"
                                                        class="w-full bg-white/5 border-none rounded-xl py-3 px-4 text-lg font-bold">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[9px] font-black uppercase text-white/40 mb-2">Charges</label>
                                                    <input type="number" name="charges"
                                                        class="w-full bg-white/5 border-none rounded-xl py-3 px-4 text-lg font-bold">
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div
                                            class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl">
                                        </div>
                                    </div>

                                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                        <h3
                                            class="text-xs font-black text-orange-600 uppercase tracking-widest mb-6 flex items-center">
                                            <span
                                                class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center mr-3"><i
                                                    class="fas fa-user-tie"></i></span>
                                            Propriétaire
                                        </h3>
                                        <div class="space-y-4">
                                            <input type="text" name="nom_proprietaire" placeholder="Nom Complet"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm">
                                            <input type="text" name="telephone_proprietaire" placeholder="Téléphone"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm">
                                            <input type="email" name="email_proprietaire" placeholder="Email"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm">
                                        </div>
                                    </div>

                                    <div class="bg-indigo-50 p-8 rounded-[2.5rem] border border-indigo-100">
                                        <div class="space-y-6">
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-2">Date
                                                    Acquisition</label>
                                                <input type="date" name="date_acquisition"
                                                    class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-indigo-600">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-2">Description
                                                    détaillée</label>
                                                <textarea name="description" rows="4"
                                                    class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-indigo-600 focus:ring-2 focus:ring-indigo-400"></textarea>
                                            </div>
                                            <div class="pt-4">
                                                <label
                                                    class="block text-[10px] font-black text-indigo-900 uppercase tracking-widest mb-3">Disponibilité</label>
                                                <select name="statut"
                                                    class="w-full px-5 py-4 bg-indigo-600 border-none rounded-2xl font-black text-white uppercase text-xs tracking-widest">
                                                    <option value="disponible">DISPONIBLE IMMÉDIATEMENT</option>
                                                    <!-- <option value="occupe">OCCUPÉ</option> -->
                                                    <option value="maintenance">SOUS MAINTENANCE</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Footer du Modal --}}
                    <div class="bg-white px-10 py-6 border-t border-gray-100 flex justify-end gap-4">
                        <button @click="openModal = false"
                            class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 hover:text-gray-600">
                            Annuler
                        </button>
                        <button form="main-form" type="submit"
                            class="px-10 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100">
                            Confirmer l'enregistrement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
