@extends('layouts.app')

@section('title', 'Gestion des Locataires')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10" x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }">

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show"
                class="relative mb-8 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xs font-black text-emerald-900 uppercase tracking-widest">Opération réussie</h3>
                    <p class="text-[11px] font-bold text-emerald-700 mt-0.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-auto p-2 group"><svg
                        class="h-5 w-5 text-emerald-300 group-hover:text-emerald-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Locataires</h1>
                <p class="text-emerald-600 font-bold uppercase text-[10px] tracking-[0.3em] mt-2 flex items-center">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping mr-2"></span> liste des dossiers et
                    gestion des locataires
                </p>
            </div>
            {{-- Barre de recherche Locataires --}}
            <form action="{{ route('locataires.index') }}" method="GET" class="relative w-full max-w-lg group">
                <div class="relative flex items-center">
                    <span class="absolute left-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </span>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nom, prénom ou téléphone..."
                        class="w-full bg-white border border-gray-100 py-5 pl-14 pr-28 rounded-[2rem] shadow-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 font-black text-[11px] uppercase tracking-wider text-slate-700 placeholder:text-slate-300 placeholder:font-bold transition-all outline-none italic">

                    <div class="absolute right-2">
                        <button type="submit"
                            class="bg-emerald-500 text-white px-5 py-3 rounded-[1.5rem] font-black text-[9px] uppercase tracking-widest hover:bg-emerald-600 transition-all active:scale-95">
                            Rechercher
                        </button>
                    </div>
                </div>

                @if (request('search'))
                    @if ($locataires->isEmpty())
                        {{-- Cas : Aucun résultat trouvé --}}
                        <a href="{{ route('locataires.index') }}"
                            class="absolute -bottom-6 left-6 text-[9px] font-black text-red-500 uppercase tracking-tighter hover:text-red-700 transition-colors">
                            <i class="fas fa-times-circle mr-1"></i> Aucun élément trouvé pour "{{ request('search') }}".
                            Réinitialiser la recherche.
                        </a>
                    @else
                        {{-- Cas : Résultats trouvés, on affiche un bouton pour annuler --}}
                        <a href="{{ route('locataires.index') }}"
                            class="absolute -bottom-6 left-6 text-[9px] font-black text-emerald-500 uppercase tracking-tighter hover:text-emerald-700 transition-colors">
                            <i class="fas fa-check-circle mr-1"></i> {{ $locataires->count() }} résultat(s) trouvé(s).
                            Cliquez pour effacer.
                        </a>
                    @endif
                @endif
            </form>
            <button @click="openModal = true"
                class="px-8 py-4 bg-emerald-500 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-emerald-600 hover:-translate-y-1 transition-all shadow-lg shadow-emerald-100 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-lg"></i> Nouveau Dossier
            </button>
        </div>

        {{-- Liste des Locataires (Tableau de ta capture) --}}
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Locataire</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Bail Actif</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($locataires as $locataire)
                        <tr class="hover:bg-emerald-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-black text-xs border border-gray-100 shadow-sm overflow-hidden">
                                        @if ($locataire->photo)
                                            <img src="{{ asset('storage/' . $locataire->photo) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ substr($locataire->nom, 0, 1) }}{{ substr($locataire->prenoms, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 uppercase text-sm italic">
                                            {{ $locataire->nom }} {{ $locataire->prenoms }}</div>
                                        <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">
                                            {{ $locataire->profession }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-gray-700">{{ $locataire->telephone }}</div>
                                <div class="text-[10px] text-gray-400 font-medium">{{ $locataire->email }}</div>
                            </td>
                            <td class="px-8 py-6">
                                @if ($locataire->contrats->count() > 0)
                                    <span
                                        class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-tighter italic border border-emerald-100">
                                        {{ $locataire->contrats->first()->bien->titre }}
                                    </span>
                                @else
                                    <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest">En
                                        attente</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('locataires.show', $locataire) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-600 hover:text-white transition-all"><i
                                            class="fas fa-eye text-xs"></i></a>
                                    <a href="{{ route('locataires.edit', $locataire) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all"><i
                                            class="fas fa-edit text-xs"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openModal = false"></div>

                <div class="relative bg-gray-50 w-full max-w-6xl rounded-[3.5rem] shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">

                    <form action="{{ route('locataires.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="flex justify-between items-center mb-10">
                            <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">Création Dossier
                                Locataire</h2>
                            <button type="button" @click="openModal = false"
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-white text-gray-400 hover:text-red-500 shadow-sm transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            <div class="lg:col-span-8 space-y-8">

                                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                    <h3
                                        class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-8 flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mr-3"><i
                                                class="fas fa-user"></i></span>
                                        État Civil & Identité
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2">Civilité
                                                *</label>
                                            <select name="civilite"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                                                <option value="M">Monsieur</option>
                                                <option value="Mme">Madame</option>
                                                <option value="Mlle">Mademoiselle</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Nom
                                                *</label>
                                            <input type="text" name="nom"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold uppercase"
                                                required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2">Prénoms
                                                *</label>
                                            <input type="text" name="prenoms"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold"
                                                required>
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Date
                                                de naissance</label>
                                            <input type="date" name="date_naissance"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Lieu
                                                de naissance</label>
                                            <input type="text" name="lieu_naissance"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                                        </div>
                                        <div class="md:col-span-6">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2">Situation
                                                Matrimoniale</label>
                                            <select name="situation_matrimoniale"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                                                <option value="celibataire">CELIBATAIRE</option>
                                                <option value="marie">MARIE(E)</option>
                                                <option value="divorce">DIVORCE(E)</option>
                                                <option value="veuf">VEUF/VEUVE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                    <h3
                                        class="text-xs font-black text-blue-600 uppercase tracking-widest mb-8 flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3"><i
                                                class="fas fa-id-card"></i></span>
                                        Justificatifs d'identité
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Type
                                                de pièce *</label>
                                            <select name="type_piece"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold"
                                                required>
                                                <option value="cni">CNI</option>
                                                <option value="passeport">Passeport</option>
                                                <option value="attestation_identite">Attestation</option>
                                                <option value="carte_consulaire">Carte Consulaire</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Numéro
                                                de pièce *</label>
                                            <input type="text" name="numero_piece"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold"
                                                required>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 md:col-span-2">
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase mb-2">Délivrée
                                                    le</label>
                                                <input type="date" name="date_delivrance_piece"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-xs">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase mb-2 text-red-500">Expire
                                                    le</label>
                                                <input type="date" name="date_expiration_piece"
                                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-xs">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                                    <h3
                                        class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-8 flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center mr-3"><i
                                                class="fas fa-briefcase"></i></span>
                                        Profil Professionnel
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2">Profession</label>
                                            <input type="text" name="profession"
                                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 italic">Revenus
                                                Mensuels (FCFA)</label>
                                            <input type="number" name="revenus_mensuels"
                                                class="w-full px-5 py-4 bg-indigo-50 border-none rounded-2xl font-black text-indigo-600 text-lg shadow-inner">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-6 rounded-3xl">
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-gray-400 uppercase mb-2">Employeur</label>
                                            <input type="text" name="employeur"
                                                class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">Tél.
                                                Employeur</label>
                                            <input type="text" name="telephone_employeur"
                                                class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">Adresse
                                                Employeur</label>
                                            <input type="text" name="adresse_employeur"
                                                class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-xs">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-4 space-y-8">

                                <div class="bg-gray-900 p-8 rounded-[3rem] shadow-2xl text-white">
                                    <div class="flex justify-center mb-6">
                                        <div class="relative group" x-data="{ photoPreview: null }">
                                            <div
                                                class="w-36 h-36 rounded-full bg-gray-800 border-4 border-emerald-500 overflow-hidden">
                                                <img id="preview"
                                                    :src="photoPreview ? photoPreview :
                                                        'https://ui-avatars.com/api/?name=Locataire&background=0D8ABC&color=fff&size=128'"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <label
                                                class="absolute bottom-0 right-0 bg-emerald-500 p-3 rounded-full cursor-pointer hover:scale-110 transition-transform shadow-lg">
                                                <i class="fas fa-camera text-sm text-white"></i>
                                                <input type="file" name="photo" class="hidden"
                                                    @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file); }">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-[9px] font-black uppercase text-gray-500 mb-2">Mobile
                                                Principal *</label>
                                            <input type="text" name="telephone"
                                                class="w-full bg-white/10 border-none rounded-2xl py-4 px-5 font-black text-emerald-400 focus:ring-2 focus:ring-emerald-500 text-center text-lg"
                                                required>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[9px] font-black uppercase text-gray-500 mb-2 text-center">Email
                                                de contact</label>
                                            <input type="email" name="email"
                                                class="w-full bg-white/5 border-none rounded-2xl py-3 px-5 text-sm font-bold text-gray-300 text-center">
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-orange-50 p-8 rounded-[2.5rem] border border-orange-100">
                                    <h3
                                        class="text-xs font-black text-orange-600 uppercase tracking-widest mb-6 flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center mr-3"><i
                                                class="fas fa-ambulance"></i></span>
                                        En cas d'urgence
                                    </h3>
                                    <div class="space-y-4">
                                        <input type="text" name="personne_urgence_nom" placeholder="NOM DU CONTACT"
                                            class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-[10px] uppercase shadow-sm">
                                        <input type="text" name="personne_urgence_telephone"
                                            placeholder="TÉLÉPHONE URGENCE"
                                            class="w-full px-5 py-4 bg-white border-none rounded-2xl font-black text-orange-600 text-xs shadow-sm">
                                        <select name="personne_urgence_lien"
                                            class="w-full px-5 py-4 bg-white border-none rounded-2xl font-bold text-[10px] shadow-sm">
                                            <option value="">LIEN DE PARENTÉ...</option>
                                            <option value="conjoint">Conjoint(e)</option>
                                            <option value="parent">Parent</option>
                                            <option value="ami">Ami(e) / Proche</option>
                                            <option value="collegue">Collègue</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
                                    <div x-data="{ files: [] }">
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">
                                            Pièces Jointes (Scan)
                                        </label>

                                        <div class="relative">
                                            <input type="file" name="documents[]" multiple
                                                @change="files = Array.from($event.target.files)"
                                                class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">

                                            <template x-if="files.length > 0">
                                                <button type="button"
                                                    @click="files = []; $el.previousElementSibling.value = ''"
                                                    class="absolute right-0 top-0 text-[9px] font-black text-red-500 uppercase">
                                                    Réinitialiser
                                                </button>
                                            </template>
                                        </div>

                                        <div class="mt-3 space-y-2">
                                            <template x-for="(file, index) in files" :key="index">
                                                <div
                                                    class="flex items-center justify-between bg-blue-50/50 p-2 rounded-xl border border-blue-100">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-file-pdf text-blue-500 mr-2"
                                                            x-show="file.type.includes('pdf')"></i>
                                                        <i class="fas fa-file-image text-emerald-500 mr-2"
                                                            x-show="file.type.includes('image')"></i>
                                                        <span
                                                            class="text-[10px] font-bold text-blue-900 truncate max-w-[200px]"
                                                            x-text="file.name"></span>
                                                    </div>
                                                    <span class="text-[9px] text-blue-400 font-black"
                                                        x-text="(file.size / 1024).toFixed(1) + ' KB'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Observations</label>
                                        <textarea name="notes" rows="3"
                                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-[11px] font-medium italic text-gray-500"
                                            placeholder="Notes particulières..."></textarea>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full py-6 bg-emerald-500 text-white rounded-[2.5rem] font-black text-sm uppercase tracking-[0.2em] shadow-xl shadow-emerald-200 hover:bg-emerald-600 hover:-translate-y-2 transition-all duration-300">
                                    Finaliser l'Inscription
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
