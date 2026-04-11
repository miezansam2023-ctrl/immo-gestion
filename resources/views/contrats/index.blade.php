@extends('layouts.app')

@section('title', 'Gestion des Contrats')


@section('content')
    <div class="max-w-7xl mx-auto pb-12 space-y-8 px-4 py-8 italic">

        {{-- 1. EN-TÊTE & ACTION --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Gestion des Baux</h1>
                <p class="text-indigo-600 font-bold text-[10px] tracking-[0.3em] uppercase mt-1 flex items-center">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-ping mr-2"></span> Registre des contrats
                </p>
            </div>
            {{-- BARRE DE RECHERCHE & FILTRES --}}
            <div class="relative group max-w-2xl">
                <form action="{{ route('contrats.index') }}" method="GET" class="relative flex items-center">
                    <div class="absolute left-6 text-gray-400 group-focus-within:text-dark-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un contrat..."
                        class="w-full bg-white border border-gray-100 py-5 pl-14 pr-28 rounded-[2rem] shadow-sm text-sm font-bold text-gray-700 placeholder:text-gray-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none italic">

                    <div class="absolute right-2">
                        <button type="submit"
                            class="bg-gray-900 text-white px-6 py-3 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:bg-black-600 transition-all active:scale-95">
                            Rechercher
                        </button>
                    </div>

                    @if (request('search'))
                        @if ($contrats->isEmpty())
                            {{-- Cas : Aucun résultat trouvé --}}
                            <a href="{{ route('contrats.index') }}"
                                class="absolute -bottom-6 left-6 text-[9px] font-black text-red-500 uppercase tracking-tighter hover:text-red-700 transition-colors">
                                <i class="fas fa-times-circle mr-1"></i> Aucun élément trouvé pour
                                "{{ request('search') }}". Réinitialiser la recherche.
                            </a>
                        @else
                            {{-- Cas : Résultats trouvés, on affiche un bouton pour annuler --}}
                            <a href="{{ route('contrats.index') }}"
                                class="absolute -bottom-6 left-6 text-[9px] font-black text-emerald-500 uppercase tracking-tighter hover:text-emerald-700 transition-colors">
                                <i class="fas fa-check-circle mr-1"></i> {{ $contrats->count() }} résultat(s) trouvé(s).
                                Cliquez pour effacer.
                            </a>
                        @endif
                    @endif
                </form>
            </div>
            <button onclick="document.getElementById('modal-contrat').classList.remove('hidden')"
                class="bg-gray-900 text-white px-8 py-4 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl hover:bg-indigo-600 transition-all active:scale-95 flex items-center group">
                <i class="fas fa-plus-circle mr-3 group-hover:rotate-90 transition-transform"></i> Nouveau Contrat
            </button>
        </div>

        {{-- 3. REGISTRE DES CONTRATS (TABLEAU PRINCIPAL) --}}
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Référence & Bail
                            </th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Locataire & Bien
                            </th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 text-center tracking-widest">
                                Échéance du Contrat</th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Conditions
                                Financières</th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 text-right tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 italic">
                        @forelse($contrats as $contrat)
                            <tr class="group hover:bg-indigo-50/30 transition-all">
                                {{-- RÉFÉRENCE --}}
                                <td class="p-8">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-indigo-50 flex flex-col items-center justify-center text-indigo-500 mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-file-signature text-xs mb-1"></i>
                                            <span class="text-[8px] font-black">DOC</span>
                                        </div>
                                        <div>
                                            <span
                                                class="font-mono font-black text-gray-900 text-sm tracking-tighter">{{ $contrat->numero }}</span>
                                            <div class="text-[9px] text-gray-400 font-bold uppercase mt-1">Signé le
                                                {{ $contrat->date_signature->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- LOCATAIRE & BIEN --}}
                                <td class="p-8">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-gray-900">
                                            <i class="fas fa-user-circle mr-2 text-gray-400 text-xs"></i>
                                            <span
                                                class="font-black uppercase text-xs">{{ $contrat->locataire->nom }}{{ $contrat->locataire->prenoms }}</span>
                                        </div>
                                        <div
                                            class="flex items-center text-[10px] text-gray-400 font-bold uppercase tracking-tight ml-5">
                                            <i class="fas fa-home mr-1.5 opacity-50"></i> {{ $contrat->bien->titre }}
                                        </div>
                                    </div>
                                </td>

                                {{-- ÉCHÉANCE --}}
                                <td class="p-8 text-center">
                                    <div
                                        class="inline-block px-5 py-3 rounded-[1.5rem] bg-white border border-gray-100 shadow-sm group-hover:border-indigo-200 transition-colors">
                                        <span
                                            class="block text-xs font-black text-gray-800 mb-1">{{ $contrat->date_fin_fr }}</span>
                                        <div class="flex items-center justify-center gap-1.5">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full {{ $contrat->jours_restants < 30 ? 'bg-red-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                                            <span
                                                class="text-[9px] font-black {{ $contrat->jours_restants < 30 ? 'text-red-500' : 'text-emerald-500' }} uppercase tracking-tighter">
                                                {{ floor($contrat->jours_restants) }} jours restants
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- MODALITÉS --}}
                                <td class="p-8">
                                    <div class="bg-indigo-50/50 p-3 rounded-2xl border border-indigo-100/50">
                                        <div class="font-black text-indigo-600 text-[13px] flex items-baseline gap-1">
                                            LOYER: {{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }} 
                                            <span class="text-[8px] uppercase">CFA / mois</span>
                                        </div>
                                        <div class="flex gap-3 mt-1 px-1">
                                            <div class="text-[8px] font-bold text-gray-400 uppercase">Caution: <span
                                                    class="text-gray-600">{{ number_format($contrat->caution, 0, ',', ' ') }}
                                                    CFA</span></div>
                                            <div class="text-[8px] font-bold text-gray-400 uppercase"> Frais Agence: <span
                                                    class="text-gray-600">{{ number_format($contrat->frais_agence, 0, ',', ' ') }}
                                                    CFA</span></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="p-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('contrats.pdf', $contrat->id) }}"
                                            class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm group/btn">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('contrats.show', $contrat->id) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-600 hover:text-white transition-all"><i
                                                class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('contrats.edit', $contrat->id) }}"
                                            class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-500 rounded-xl hover:bg-indigo-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('contrats.destroy', $contrat->id) }}" method="POST"
                                            class="inline" onsubmit="event.preventDefault(); confirmDelete('Êtes-vous sûr de vouloir annuler ce bail ?').then(confirmed => { if(confirmed) this.submit(); })">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-300 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                {{ $contrats->links() }}
            </div>
        </div>

        {{-- 4. MODAL DE CRÉATION --}}
        <div id="modal-contrat" class="fixed inset-0 z-50 hidden overflow-y-auto italic">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                onclick="document.getElementById('modal-contrat').classList.add('hidden')"></div>

            <div class="relative min-h-screen flex items-start justify-center p-4 py-8">
                <div
                    class="relative bg-white w-full max-w-6xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col md:flex-row">

                    {{-- FORMULAIRE GAUCHE --}}
                    <div class="flex-1 overflow-y-auto max-h-[90vh]">

                        {{-- Header --}}
                        <div
                            class="sticky top-0 z-10 bg-white px-10 py-6 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Nouveau Bail</h2>
                                <p class="text-indigo-500 text-[9px] font-black uppercase tracking-widest">Remplir tous les
                                    champs obligatoires *</p>
                            </div>
                            <button onclick="document.getElementById('modal-contrat').classList.add('hidden')"
                                class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </button>
                        </div>

                        <form action="{{ route('contrats.store') }}" method="POST" id="form-contrat"
                            class="p-10 space-y-8">
                            @csrf
                            <input type="hidden" name="date_signature" value="{{ date('Y-m-d') }}">

                            {{-- SECTION 1 : PARTIES --}}
                            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
                                <h3
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center"><i
                                            class="fas fa-handshake text-indigo-500"></i></span>
                                    Parties du Contrat
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Bien
                                            Immobilier *</label>
                                        <div class="relative">
                                            <select name="bien_id" id="bien_selector"
                                                class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl py-4 px-5 font-bold text-gray-700 appearance-none"
                                                required>
                                                <option value="">Sélectionner un bien...</option>
                                                @foreach ($biens as $bien)
                                                    <option value="{{ $bien->id }}"
                                                        data-loyer="{{ $bien->prix_loyer }}"
                                                        data-caution="{{ $bien->prix_caution }}">
                                                        {{ $bien->reference }} — {{ $bien->titre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Locataire
                                            *</label>
                                        <div class="relative">
                                            <select name="locataire_id"
                                                class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl py-4 px-5 font-bold text-gray-700 appearance-none"
                                                required>
                                                <option value="">Sélectionner un locataire...</option>
                                                @foreach ($locataires as $locataire)
                                                    <option value="{{ $locataire->id }}">
                                                        {{ $locataire->nom }} {{ $locataire->prenoms }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 2 : DATES & DURÉE --}}
                            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
                                <h3
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center"><i
                                            class="fas fa-calendar-alt text-indigo-500"></i></span>
                                    Période & Durée
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Date
                                            de début *</label>
                                        <input type="date" name="date_debut"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Durée
                                            (mois) *</label>
                                        <input type="number" name="duree_mois" value="12" min="1"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Date
                                            d'établissement</label>
                                        <input type="date" name="date_signature" value="{{ date('Y-m-d') }}"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 3 : CONDITIONS FINANCIÈRES --}}
                            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
                                <h3
                                    class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center"><i
                                            class="fas fa-coins text-emerald-500"></i></span>
                                    Conditions Financières
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Loyer
                                            mensuel *</label>
                                        <div class="relative">
                                            <input type="number" name="loyer_mensuel" id="input_loyer"
                                                class="w-full bg-emerald-50 border-none rounded-2xl py-4 px-5 font-black text-emerald-600 text-lg focus:ring-2 focus:ring-emerald-500"
                                                placeholder="0" required>
                                            <span
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-emerald-400 opacity-60">FCFA</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Caution
                                            *</label>
                                        <div class="relative">
                                            <input type="number" name="caution" id="input_caution"
                                                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500"
                                                placeholder="0" required>
                                            <span
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-400 opacity-60">FCFA</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Frais
                                            agence</label>
                                        <div class="relative">
                                            <input type="number" name="frais_agence" id="input_agence"
                                                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500"
                                                placeholder="0">
                                            <span
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-400 opacity-60">FCFA</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Jour
                                            paiement *</label>
                                        <input type="number" name="jour_paiement" value="5" min="1"
                                            max="31"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500"
                                            required>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 4 : MODE DE RÈGLEMENT --}}
                            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
                                <h3
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center"><i
                                            class="fas fa-credit-card text-blue-500"></i></span>
                                    Mode de Règlement
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ([['especes', 'fa-money-bill-wave', 'Espèces'], ['virement', 'fa-university', 'Virement'], ['cheque', 'fa-money-check', 'Chèque'], ['mobile_money', 'fa-mobile-alt', 'Mobile Money']] as [$val, $icon, $label])
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="mode_paiement" value="{{ $val }}"
                                                class="peer sr-only" {{ $val === 'especes' ? 'checked' : '' }} required>
                                            <div
                                                class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                                <i
                                                    class="fas {{ $icon }} text-gray-400 peer-checked:text-indigo-500 mb-2 text-lg"></i>
                                                <span
                                                    class="text-[10px] font-black uppercase text-gray-500">{{ $label }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- SECTION 5 : CLAUSES & CONDITIONS --}}
                            <div class="bg-indigo-50/40 border border-indigo-100 rounded-[2.5rem] p-8">
                                <h3
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center"><i
                                            class="fas fa-file-alt text-indigo-500"></i></span>
                                    Clauses & Conditions
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">État
                                            des lieux d'entrée</label>
                                        <textarea name="etat_lieux_entree" rows="3"
                                            class="w-full bg-white border-none rounded-2xl p-4 font-medium text-sm shadow-sm focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Observations sur l'état du bien à l'entrée..."></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Notes
                                            / Clauses particulières</label>
                                        <textarea name="notes" rows="3"
                                            class="w-full bg-white border-none rounded-2xl p-4 font-medium text-sm shadow-sm focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Ex: Interdiction de fumer, jardin inclus..."></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Date
                                            état des lieux</label>
                                        <input type="date" name="date_etat_lieux_entree"
                                            class="w-full bg-white border-none rounded-2xl py-4 px-5 font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div class="flex flex-col gap-4 justify-center">
                                        <label
                                            class="flex items-center gap-3 cursor-pointer p-4 bg-white rounded-2xl shadow-sm hover:bg-indigo-50 transition-all">
                                            <input type="checkbox" name="renouvellement_automatique" value="1"
                                                class="w-5 h-5 rounded text-indigo-500 focus:ring-0 border-gray-200">
                                            <span
                                                class="text-[10px] font-black uppercase text-gray-600 tracking-widest">Renouvellement
                                                automatique</span>
                                        </label>
                                        <label
                                            class="flex items-center gap-3 cursor-pointer p-4 bg-white rounded-2xl shadow-sm hover:bg-indigo-50 transition-all">
                                            <input type="checkbox" name="animaux_autorises" value="1"
                                                class="w-5 h-5 rounded text-indigo-500 focus:ring-0 border-gray-200">
                                            <span
                                                class="text-[10px] font-black uppercase text-gray-600 tracking-widest">Animaux
                                                autorisés</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                    {{-- SIDEBAR DROITE --}}
                    <div
                        class="w-full md:w-[340px] bg-gray-900 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-600/20 blur-[80px] rounded-full"></div>
                        <div class="relative z-10 space-y-8">

                            {{-- Récapitulatif --}}
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Récapitulatif
                                    financier</p>
                                <div class="space-y-4">
                                    <div class="p-5 bg-white/5 rounded-2xl border border-white/10">
                                        <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Loyer mensuel</p>
                                        <p class="text-3xl font-black text-indigo-400" id="recap-loyer">0 <span
                                                class="text-sm">FCFA</span></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                                            <p class="text-[8px] font-black text-gray-400 uppercase mb-1">Caution</p>
                                            <p class="text-sm font-black text-white" id="recap-caution">0 FCFA</p>
                                        </div>
                                        <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                                            <p class="text-[8px] font-black text-gray-400 uppercase mb-1">Frais agence</p>
                                            <p class="text-sm font-black text-white" id="recap-agence">0 FCFA</p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-indigo-600/20 rounded-2xl border border-indigo-500/30">
                                        <p class="text-[8px] font-black text-indigo-300 uppercase mb-1">Total à encaisser
                                            (1er mois)</p>
                                        <p class="text-lg font-black text-indigo-300" id="recap-total">0 FCFA</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Infos --}}
                            <div class="space-y-3">
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5 flex items-start gap-3">
                                    <i class="fas fa-info-circle text-indigo-400 mt-0.5"></i>
                                    <p class="text-[10px] font-bold text-gray-300 leading-relaxed uppercase tracking-wide">
                                        Le numéro de contrat sera généré automatiquement.
                                    </p>
                                </div>
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5 flex items-start gap-3">
                                    <i class="fas fa-file-pdf text-orange-400 mt-0.5"></i>
                                    <p class="text-[10px] font-bold text-gray-300 leading-relaxed uppercase tracking-wide">
                                        Un PDF du bail sera généré automatiquement.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Bouton --}}
                        <div class="relative z-10 mt-8">
                            <button type="submit" form="form-contrat"
                                class="w-full bg-indigo-600 hover:bg-indigo-500 py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl transition-all group">
                                Créer le Bail
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bienSelector = document.getElementById('bien_selector');
            const inputLoyer = document.getElementById('input_loyer');
            const inputCaution = document.getElementById('input_caution');
            const inputAgence = document.getElementById('input_agence');

            function fmt(val) {
                return new Intl.NumberFormat('fr-FR').format(val || 0) + ' FCFA';
            }

            function updateRecap() {
                const loyer = parseFloat(inputLoyer.value) || 0;
                const caution = parseFloat(inputCaution.value) || 0;
                const agence = parseFloat(inputAgence.value) || 0;
                const total = loyer + caution + agence;

                document.getElementById('recap-loyer').innerHTML = fmt(loyer).replace(' FCFA', '') +
                    ' <span class="text-sm">FCFA</span>';
                document.getElementById('recap-caution').textContent = fmt(caution);
                document.getElementById('recap-agence').textContent = fmt(agence);
                document.getElementById('recap-total').textContent = fmt(total);
            }

            // Auto-remplissage depuis le bien sélectionné
            if (bienSelector) {
                bienSelector.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    if (opt.value) {
                        inputLoyer.value = opt.getAttribute('data-loyer') || 0;
                        inputCaution.value = opt.getAttribute('data-caution') || 0;
                        updateRecap();
                    }
                });
            }

            // Mise à jour dynamique du recap
            [inputLoyer, inputCaution, inputAgence].forEach(el => {
                if (el) el.addEventListener('input', updateRecap);
            });

            // Fermer avec Echap
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.getElementById('modal-contrat').classList.add('hidden');
                }
            });
        });
    </script>
@endsection
