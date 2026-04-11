@extends('layouts.app')

@section('title', 'Modifier Dossier Locataire')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10">
        {{-- Fil de Retour --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('locataires.index') }}"
                    class="text-[10px] font-black text-emerald-500 uppercase tracking-widest flex items-center mb-2 hover:text-emerald-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Modifier le Dossier</h1>
                <p class="text-indigo-600 font-bold uppercase text-[10px] tracking-[0.3em] mt-2 flex items-center">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse mr-2"></span>
                    Édition : {{ $locataire->nom }} {{ $locataire->prenoms }}
                </p>
            </div>
            <div class="bg-white px-6 py-4 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Dernière mise à jour</p>
                    <p class="text-xs font-bold text-gray-600">{{ $locataire->updated_at->format('d/m/Y') }} A
                        {{ $locataire->updated_at->format('H:i') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-history"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('locataires.update', $locataire->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- COLONNE GAUCHE : INFOS GÉNÉRALES --}}
                <div class="lg:col-span-8 space-y-8">

                    {{-- État Civil --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                        <h3 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-8 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mr-3"><i
                                    class="fas fa-user"></i></span>
                            État Civil & Identité
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Civilité *</label>
                                <select name="civilite"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-emerald-500">
                                    <option value="M" {{ $locataire->civilite == 'M' ? 'selected' : '' }}>Monsieur
                                    </option>
                                    <option value="Mme" {{ $locataire->civilite == 'Mme' ? 'selected' : '' }}>Madame
                                    </option>
                                    <option value="Mlle" {{ $locataire->civilite == 'Mlle' ? 'selected' : '' }}>
                                        Mademoiselle</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nom *</label>
                                <input type="text" name="nom" value="{{ old('nom', $locataire->nom) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold uppercase"
                                    required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Prénoms *</label>
                                <input type="text" name="prenoms" value="{{ old('prenoms', $locataire->prenoms) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Date de
                                    naissance</label>
                                <input type="date" name="date_naissance"
                                    value="{{ old('date_naissance', $locataire->date_naissance ? \Carbon\Carbon::parse($locataire->date_naissance)->format('Y-m-d') : '') }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Lieu de
                                    naissance</label>
                                <input type="text" name="lieu_naissance"
                                    value="{{ old('lieu_naissance', $locataire->lieu_naissance) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Situation
                                    Matrimoniale</label>
                                <select name="situation_matrimoniale"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                                    @foreach (['celibataire' => 'CÉLIBATAIRE', 'marie' => 'MARIÉ(E)', 'divorce' => 'DIVORCÉ(E)', 'veuf' => 'VEUF/VEUVE'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ $locataire->situation_matrimoniale == $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Justificatifs --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                        <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-8 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3"><i
                                    class="fas fa-id-card"></i></span>
                            Justificatifs d'identité
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Type de pièce
                                    *</label>
                                <select name="type_piece"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold" required>
                                    @foreach (['cni' => 'CNI', 'passeport' => 'Passeport', 'attestation_identite' => 'Attestation', 'carte_consulaire' => 'Carte Consulaire'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ $locataire->type_piece == $val ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Numéro de pièce
                                    *</label>
                                <input type="text" name="numero_piece"
                                    value="{{ old('numero_piece', $locataire->numero_piece) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4 md:col-span-2">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Délivrée
                                        le</label>
                                    <input type="date" name="date_delivrance_piece"
                                        value="{{ old('date_delivrance_piece', $locataire->date_delivrance_piece ? \Carbon\Carbon::parse($locataire->date_delivrance_piece)->format('Y-m-d') : '') }}"
                                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 text-red-500">Expire
                                        le</label>
                                    <input type="date" name="date_expiration_piece"
                                        value="{{ old('date_expiration_piece', $locataire->date_expiration_piece ? \Carbon\Carbon::parse($locataire->date_expiration_piece)->format('Y-m-d') : '') }}"
                                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm">
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Lieu de délivrance</label>
                                <input type="text" name="lieu_delivrance_piece"
                                    value="{{ old('lieu_delivrance_piece', $locataire->lieu_delivrance_piece) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Profession --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                        <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-8 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center mr-3"><i
                                    class="fas fa-briefcase"></i></span>
                            Profil Professionnel
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Profession</label>
                                <input type="text" name="profession"
                                    value="{{ old('profession', $locataire->profession) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Revenus Mensuels
                                    (FCFA)</label>
                                <input type="number" name="revenus_mensuels"
                                    value="{{ old('revenus_mensuels', $locataire->revenus_mensuels) }}"
                                    class="w-full px-5 py-4 bg-indigo-50 border-none rounded-2xl font-black text-indigo-600 text-lg">
                            </div>
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-6 rounded-3xl">
                                <div>
                                    <label
                                        class="block text-[9px] font-black text-gray-400 uppercase mb-2">Employeur</label>
                                    <input type="text" name="employeur"
                                        value="{{ old('employeur', $locataire->employeur) }}"
                                        class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-xs">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">Tél.
                                        Employeur</label>
                                    <input type="text" name="telephone_employeur"
                                        value="{{ old('telephone_employeur', $locataire->telephone_employeur) }}"
                                        class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-xs">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">Adresse
                                        Employeur</label>
                                    <input type="text" name="adresse_employeur"
                                        value="{{ old('adresse_employeur', $locataire->adresse_employeur) }}"
                                        class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COLONNE DROITE : PHOTO & CONTACT --}}
                <div class="lg:col-span-4 space-y-8">

                    {{-- Bloc Photo & Contact Rapide --}}
                    <div class="bg-gray-900 p-8 rounded-[2.5rem] shadow-2xl text-white">
                        <div class="flex justify-center mb-6">
                            <div class="relative group">
                                <div
                                    class="w-32 h-32 rounded-full bg-gray-800 border-4 border-emerald-500 overflow-hidden shadow-inner">
                                    <img id="preview"
                                        src="{{ $locataire->photo ? asset('storage/' . $locataire->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($locataire->nom) . '&background=0D8ABC&color=fff&size=128' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <label
                                    class="absolute bottom-0 right-0 bg-emerald-500 p-2 rounded-full cursor-pointer hover:scale-110 transition-transform shadow-lg">
                                    <i class="fas fa-camera text-xs text-white"></i>
                                    <input type="file" name="photo" class="hidden"
                                        onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
                                </label>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[9px] font-black uppercase text-gray-500 mb-2 tracking-widest">Mobile
                                    Principal *</label>
                                <input type="text" name="telephone"
                                    value="{{ old('telephone', $locataire->telephone) }}"
                                    class="w-full bg-white/10 border-none rounded-2xl py-4 px-5 font-black text-emerald-400 focus:ring-2 focus:ring-emerald-500"
                                    required>
                            </div>
                            <div>
                                <label
                                    class="block text-[9px] font-black uppercase text-gray-500 mb-2 tracking-widest">Email</label>
                                <input type="email" name="email" value="{{ old('email', $locataire->email) }}"
                                    class="w-full bg-white/5 border-none rounded-2xl py-3 px-5 text-sm font-bold text-gray-300">
                            </div>
                        </div>
                    </div>

                    {{-- Urgence --}}
                    <div class="bg-orange-50 p-8 rounded-[2.5rem] border border-orange-100">
                        <h3 class="text-xs font-black text-orange-600 uppercase tracking-widest mb-6 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center mr-3"><i
                                    class="fas fa-ambulance"></i></span>
                            En cas d'urgence
                        </h3>
                        <div class="space-y-4">
                            <input type="text" name="personne_urgence_nom"
                                value="{{ old('personne_urgence_nom', $locataire->personne_urgence_nom) }}"
                                placeholder="Nom complet du contact"
                                class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-xs shadow-sm">
                            <input type="text" name="personne_urgence_telephone"
                                value="{{ old('personne_urgence_telephone', $locataire->personne_urgence_telephone) }}"
                                placeholder="Téléphone urgence"
                                class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-xs shadow-sm">
                            <select name="personne_urgence_lien"
                                class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-xs shadow-sm">
                                <option value="">Lien de parenté...</option>
                                @foreach (['conjoint' => 'Conjoint(e)', 'parent' => 'Parent', 'ami' => 'Ami(e) / Proche', 'collegue' => 'Collègue'] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ $locataire->personne_urgence_lien == $val ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Documents & Notes --}}
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">
                                Documents du dossier (Existants)
                            </label>

                            @if (
                                $locataire->documents &&
                                    count(is_array($locataire->documents) ? $locataire->documents : json_decode($locataire->documents, true) ?? []) > 0)
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach (is_array($locataire->documents) ? $locataire->documents : json_decode($locataire->documents, true) as $index => $path)
                                        <div x-data="{ isMarked: false }"
                                            :class="isMarked ? 'opacity-40 grayscale border-red-200 bg-red-50' :
                                                'bg-white border-gray-100'"
                                            class="flex items-center justify-between p-3 border rounded-2xl transition-all duration-300 shadow-sm">

                                            <div class="flex items-center">
                                                <i class="fas fa-file-alt mr-3"
                                                    :class="isMarked ? 'text-red-400' : 'text-indigo-500'"></i>
                                                <div>
                                                    <span class="text-[10px] font-black uppercase tracking-tighter"
                                                        :class="isMarked ? 'text-red-600' : 'text-gray-700'">
                                                        Document #{{ $index + 1 }}
                                                    </span>
                                                    <template x-if="isMarked">
                                                        <span
                                                            class="ml-2 text-[8px] font-bold text-red-500 uppercase italic">Suppression
                                                            prévue</span>
                                                    </template>
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                                    class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-500 hover:text-white transition-all">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                </a>

                                                <label
                                                    class="w-8 h-8 flex items-center justify-center rounded-xl cursor-pointer transition-all shadow-sm"
                                                    :class="isMarked ? 'bg-red-600 text-white rotate-12' :
                                                        'bg-red-50 text-red-500 hover:bg-red-100'">
                                                    <input type="checkbox" name="remove_documents[]"
                                                        value="{{ $path }}" class="hidden"
                                                        @change="isMarked = $el.checked">
                                                    <i class="fas"
                                                        :class="isMarked ? 'fa-times' : 'fa-trash text-[10px]'"></i>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[10px] text-gray-400 italic">Aucun document enregistré pour le moment.</p>
                            @endif
                        </div>

                        <div x-data="{ newFiles: [] }"
                            class="p-4 bg-blue-50/30 border border-dashed border-blue-200 rounded-3xl">
                            <label class="block text-[10px] font-black text-blue-500 uppercase mb-3 tracking-widest">
                                Ajouter de nouveaux scans
                            </label>

                            <input type="file" name="documents[]" multiple
                                @change="newFiles = Array.from($event.target.files)"
                                class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">

                            <template x-if="newFiles.length > 0">
                                <div class="mt-4 space-y-2">
                                    <template x-for="(file, index) in newFiles" :key="index">
                                        <div
                                            class="flex items-center text-[10px] bg-white p-2 rounded-lg border border-blue-100 shadow-sm text-blue-700 font-bold">
                                            <i class="fas fa-plus-circle mr-2 text-blue-400"></i>
                                            <span x-text="file.name"></span>
                                        </div>
                                    </template>
                                    <button type="button"
                                        @click="newFiles = []; $el.closest('div').querySelector('input').value = ''"
                                        class="text-[9px] font-black text-red-500 uppercase mt-2 hover:underline">
                                        Annuler l'ajout
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Action  --}}
                    <button type="submit"
                        class="w-full py-6 bg-indigo-600 text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                        <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                        Mettre à jour le dossier
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                </div>
            </div>
        </form>
    </div>
@endsection
