@extends('layouts.app')

@section('title', 'Modifier le Bien Patrimonial')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 space-y-8">

    {{-- ENTÊTE & NAVIGATION --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('biens.index') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest flex items-center mb-2 hover:text-indigo-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour au patrimoine
            </a>
            <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Modifier la fiche Technique</h1>
            <p class="text-emerald-600 font-bold uppercase text-[10px] tracking-[0.3em] mt-2 flex items-center">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-2"></span>
                Référence : <span class="font-mono ml-1">BIE-{{ $bien->id }}</span>
            </p>
        </div>
        <div class="bg-white px-6 py-4 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="text-right">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Dernière mise à jour</p>
                <p class="text-xs font-bold text-gray-600">{{ $bien->updated_at->format('d/m/Y') }} A {{ $bien->updated_at->format('H:i') }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>

    {{-- ALERTES --}}
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-2xl shadow-sm flex justify-between items-center">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
            <span class="text-emerald-800 font-black uppercase text-[10px] tracking-widest">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600"><i class="fas fa-times"></i></button>
    </div>
    @endif

    <form action="{{ route('biens.update', $bien->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- COLONNE GAUCHE : CARACTÉRISTIQUES --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- Localisation & Type --}}
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-8 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center mr-3"><i class="fas fa-map-marker-alt"></i></span>
                        Localisation & Identité
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                        <div class="md:col-span-6">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Désignation du Bien *</label>
                            <input type="text" name="titre" value="{{ old('titre', $bien->titre) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Commune *</label>
                            <input type="text" name="commune" value="{{ old('commune', $bien->commune) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Quartier</label>
                            <input type="text" name="quartier" value="{{ old('quartier', $bien->quartier) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $bien->ville) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Type de Bien</label>
                            <select name="type" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                                @foreach(['villa', 'appartement', 'studio', 'magasin', 'bureau', 'terrain'] as $t)
                                <option value="{{ $t }}" {{ $bien->type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">RUE/LOT/ILOT</label>
                            <input type="text" name="adresse" value="{{ old('adresse', $bien->adresse) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                        </div>
                    </div>
                </div>

                {{-- Détails Techniques --}}
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-8 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mr-3"><i class="fas fa-ruler-combined"></i></span>
                        Configuration Technique
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nombre de pièces</label>
                            <input type="number" name="nombre_pieces" value="{{ old('nombre_pieces', $bien->nombre_pieces) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                        </div>
                        <!-- <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Chambres</label>
                            <input type="number" name="nombre_chambres" value="{{ old('nombre_chambres', $bien->nombre_chambres) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                        </div> -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Superficie (m²)</label>
                            <input type="number" name="superficie" value="{{ old('superficie', $bien->superficie) }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                        </div>
                    </div>

                    <div class="mt-8">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Équipements inclus</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php $mesEquips = is_array($bien->equipements) ? $bien->equipements : json_decode($bien->equipements, true) ?? []; @endphp
                            @foreach(['Piscine', 'Garage', 'Groupe Élec.', 'Ascenseur', 'Climatisation', 'Sécurité 24h/7', 'Wifi', 'Balcon', 'Cuisine Équipée'] as $item)
                            <label class="flex items-center p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-emerald-50 transition-all border border-transparent has-[:checked]:border-emerald-200 has-[:checked]:bg-emerald-50/50 group">
                                <input type="checkbox" name="equipements[]" value="{{ $item }}" {{ in_array($item, $mesEquips) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 text-[9px] font-bold text-gray-400 group-hover:text-emerald-700 uppercase">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-4 flex items-center">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i> Description détaillée
                    </label>
                    <textarea name="description" rows="4" class="w-full bg-gray-50 border-none rounded-3xl p-6 font-medium text-sm text-gray-600 italic focus:ring-2 focus:ring-indigo-500">{{ old('description', $bien->description) }}</textarea>
                </div>
            </div>

            {{-- COLONNE DROITE : STATUT / PRIX / PHOTO --}}
            <div class="lg:col-span-4 space-y-8">

                {{-- Bloc Financier --}}
                <div class="bg-gray-900 p-8 rounded-[3rem] shadow-2xl text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>

                    <div class="relative space-y-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-2 tracking-widest">Loyer Mensuel (HT)</label>
                            <div class="flex items-center bg-white/5 rounded-2xl p-4 border border-white/10">
                                <input type="number" name="prix_loyer" value="{{ old('prix_loyer', $bien->prix_loyer) }}" class="bg-transparent border-none w-full p-0 text-3xl font-black text-indigo-400 focus:ring-0" required>
                                <span class="text-xs font-black opacity-30 ml-2">FCFA</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-2 tracking-widest">Caution </label>
                            <input type="number" name="prix_caution" value="{{ old('prix_caution', $bien->prix_caution) }}" class="w-full bg-white/5 border-none rounded-2xl p-4 font-bold text-sm text-gray-300 focus:ring-2 focus:ring-indigo-500">
                            
                            <!-- <span class="text-[9px] text-gray-400 italic mt-1 block">L'équivalent de 1 à 2 mois de loyer est généralement requis.</span> -->
                            
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-2 tracking-widest">Statut du Bien</label>
                            <select name="statut" class="w-full bg-indigo-600 border-none rounded-2xl p-4 font-black uppercase text-xs tracking-widest cursor-pointer hover:bg-indigo-500 transition-colors">
                                <option value="disponible" {{ $bien->statut == 'disponible' ? 'selected' : '' }}>✅ Disponible</option>
                                <option value="occupe" {{ $bien->statut == 'occupe' ? 'selected' : '' }}>🏠 Occupé</option>
                                <option value="maintenance" {{ $bien->statut == 'maintenance' ? 'selected' : '' }}>🛠️ Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Bloc Photo --}}
                {{-- Bloc Photo mis à jour --}}
                {{-- Bloc Photo Dynamique --}}
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100"
                    x-data="{ 
        {{-- On récupère les photos existantes depuis la base de données --}}
        existingPhotos: {{ json_encode(is_array($bien->photos) ? $bien->photos : json_decode($bien->photos, true) ?? []) }},
        newPhotos: [],
        photosToDelete: [],
        maxPhotos: 5,
        
        handleNewFiles(event) {
            const files = Array.from(event.target.files);
            const remainingSlot = this.maxPhotos - (this.existingPhotos.length + this.newPhotos.length);
            
            files.slice(0, remainingSlot).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.newPhotos.push({ src: e.target.result, file: file });
                };
                reader.readAsDataURL(file);
            });
        },
        removeExisting(index) {
            {{-- On l'ajoute à la liste des suppressions pour le serveur --}}
            this.photosToDelete.push(this.existingPhotos[index]);
            {{-- On l'enlève de l'affichage --}}
            this.existingPhotos.splice(index, 1);
        },
        removeNew(index) {
            this.newPhotos.splice(index, 1);
        }
     }">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-[#1E293B] uppercase tracking-widest flex items-center">
                            
                            Galerie Photos (Max 5)
                        </h3>
                        <span class="text-[10px] font-bold text-gray-400" x-text="(existingPhotos.length + newPhotos.length) + '/5 photos'"></span>
                    </div>

                    {{-- Inputs cachés pour communiquer avec le Controller --}}
                    <template x-for="path in photosToDelete">
                        <input type="hidden" name="removed_photos[]" :value="path">
                    </template>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        {{-- 1. Affichage des photos déjà présentes sur le serveur --}}
                        <template x-for="(photo, index) in existingPhotos" :key="'old-'+index">
                            <div class="relative aspect-square group">
                                <img :src="'/storage/' + photo" class="w-full h-full object-cover rounded-2xl border border-gray-100 shadow-sm">
                                <button type="button" @click="removeExisting(index)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-all opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                                {{-- On garde une trace des photos maintenues --}}
                                <input type="hidden" name="old_photos[]" :value="photo">

                                <template x-if="index === 0">
                                    <span class="absolute -top-2 -left-2 bg-emerald-500 text-white text-[8px] font-black px-2 py-1 rounded-lg uppercase shadow-lg">Icône</span>
                                </template>
                            </div>
                        </template>

                        {{-- 2. Prévisualisation des nouvelles photos sélectionnées --}}
                        <template x-for="(img, index) in newPhotos" :key="'new-'+index">
                            <div class="relative aspect-square group border-2 border-indigo-200 rounded-2xl p-1">
                                <img :src="img.src" class="w-full h-full object-cover rounded-xl shadow-sm">
                                <button type="button" @click="removeNew(index)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-all">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                                <template x-if="existingPhotos.length === 0 && index === 0">
                                    <span class="absolute -top-2 -left-2 bg-emerald-500 text-white text-[8px] font-black px-2 py-1 rounded-lg uppercase shadow-lg">Nouvelle Icône</span>
                                </template>
                            </div>
                        </template>

                        {{-- 3. Bouton Ajouter (si < 5) --}}
                        <template x-if="(existingPhotos.length + newPhotos.length) < maxPhotos">
                            <label class="relative aspect-square flex flex-col items-center justify-center border-2 border-dashed border-indigo-100 rounded-2xl cursor-pointer hover:bg-indigo-50 transition-all group">
                                <i class="fas fa-plus text-indigo-300 group-hover:text-indigo-500 transition-colors"></i>
                                <span class="text-[8px] font-black text-indigo-300 uppercase mt-2">Ajouter</span>
                                <input type="file" name="photos[]" multiple accept="image/*" class="hidden" @change="handleNewFiles">
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Zone d'upload --}}
                <!-- <div class="relative group" x-show="existingPhotos.length < maxPhotos">
                    <label class="flex flex-col items-center justify-center w-full py-8 border-2 border-dashed border-gray-200 rounded-[2rem] cursor-pointer hover:bg-gray-50 transition-colors">
                        <i class="fas fa-plus-circle text-gray-300 text-2xl mb-2 group-hover:text-indigo-400"></i>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Ajouter des photos</span>
                        <input type="file" name="photos[]" multiple accept="image/*" class="hidden" @change="handleNewFiles">
                    </label>
                </div> -->

                <p class="text-[9px] text-gray-400 italic text-center">
                    Note : La première photo de la liste reste l'icône principale.
                    <br>Pour changer l'icône, téléchargez une nouvelle galerie complète.
                </p>
            </div>
        </div>

        {{-- Propriétaire --}} 
        <div class="bg-indigo-50/50 p-8 rounded-[3rem] border border-indigo-100">
            <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-4">Propriétaire Mandant</h3>
            <div class="space-y-3">
                <input type="text" name="nom_proprietaire" value="{{ old('nom_proprietaire', $bien->nom_proprietaire) }}" placeholder="Nom complet" class="w-full px-5 py-3 bg-white border-none rounded-xl font-bold text-xs shadow-sm">
                <input type="text" name="telephone_proprietaire" value="{{ old('telephone_proprietaire', $bien->telephone_proprietaire) }}" placeholder="Téléphone" class="w-full px-5 py-3 bg-white border-none rounded-xl font-bold text-xs shadow-sm">
            </div>
        </div>

        {{-- Action --}}
        <button type="submit" class="w-full py-6 bg-emerald-500 text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-xl shadow-emerald-100 hover:bg-emerald-600 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
            <i class="fas fa-save"></i> Mettre à jour le bien
        </button>
</div>
</div>
</form>
</div>
@endsection