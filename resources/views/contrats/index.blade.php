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

        {{-- 2. ALERTES DYNAMIQUES --}}
        {{-- @if (session('success'))
            <div id="alert-success"
                class="flex items-center justify-between bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                    <div class="ml-3 text-emerald-800 font-black uppercase text-[10px] tracking-widest">
                        {{ session('success') }}</div>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-emerald-500 hover:rotate-90 transition-transform"><i class="fas fa-times"></i></button>
            </div>
        @endif --}}

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
                                            <span class="font-black uppercase text-xs">{{ $contrat->locataire->nom }}
                                                {{ $contrat->locataire->prenoms }}</span>
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
                                            class="block text-xs font-black text-gray-800 mb-1">{{ $contrat->date_fin->format('d M Y') }}</span>
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
                                            {{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }}
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
                                            class="inline" onsubmit="return confirm('Annuler ce bail ?')">
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
        </div>

        {{-- 4. MODAL DE CRÉATION --}}
        <div id="modal-contrat" class="fixed inset-0 z-50 hidden overflow-y-auto italic">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                onclick="document.getElementById('modal-contrat').classList.add('hidden')"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div
                    class="relative bg-white w-full max-w-6xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col md:flex-row">

                    {{-- Formulaire (Gauche 8/12) --}}
                    <div class="flex-1 p-8 md:p-12 overflow-y-auto max-h-[90vh]">
                        <div class="flex justify-between items-center mb-10">
                            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">Nouveau Bail
                            </h2>
                            <button onclick="document.getElementById('modal-contrat').classList.add('hidden')"
                                class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </button>
                        </div>

                        <form action="{{ route('contrats.store') }}" method="POST" id="form-contrat"
                            class="space-y-10">
                            @csrf
                            {{-- Section Identification --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Bien
                                        Immobilier *</label>
                                    <select name="bien_id" id="bien_selector"
                                        class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl py-4 px-5 font-bold text-gray-700 transition-all"
                                        required>
                                        <option value="">Sélectionner...</option>
                                        @foreach ($biens as $bien)
                                            <option value="{{ $bien->id }}" data-loyer="{{ $bien->prix_loyer }}"
                                                data-caution="{{ $bien->prix_caution }}">
                                                {{ $bien->reference }} - {{ $bien->titre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Locataire
                                        *</label>
                                    <select name="locataire_id"
                                        class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl py-4 px-5 font-bold text-gray-700 transition-all"
                                        required>
                                        <option value="">Sélectionner...</option>
                                        @foreach ($locataires as $locataire)
                                            <option value="{{ $locataire->id }}">{{ $locataire->nom }}
                                                {{ $locataire->prenoms }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Section Dates --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Date
                                        Début *</label>
                                    <input type="date" name="date_debut"
                                        class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold" required>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Durée
                                        (Mois) *</label>
                                    <input type="number" name="duree_mois" value="12"
                                        class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold" required>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Mode
                                        Règlement *</label>
                                    <select name="mode_paiement"
                                        class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700"
                                        required>
                                        <option value="Espèces">Espèces</option>
                                        <option value="Virement">Virement</option>
                                        <option value="Mobile Money">Mobile Money</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Section État des lieux --}}
                            <div class="bg-indigo-50/50 p-8 rounded-[2.5rem] space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <input type="date" name="date_signature" value="{{ date('Y-m-d') }}"
                                        class="hidden">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Commentaire
                                            Entrée</label>
                                        <textarea name="etat_des_lieux_entree" rows="2"
                                            class="w-full bg-white border-none rounded-2xl p-4 font-medium text-sm shadow-sm" placeholder="Observations..."></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Notes
                                            Clauses</label>
                                        <textarea name="notes" rows="2"
                                            class="w-full bg-white border-none rounded-2xl p-4 font-medium text-sm shadow-sm"
                                            placeholder="Clauses spécifiques..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Sidebar Finances (Droite 4/12) --}}
                    <div
                        class="w-full md:w-[400px] bg-gray-900 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/20 blur-[60px] rounded-full"></div>

                        <div class="relative z-10 space-y-8">
                            <div class="border-b border-white/10 pb-8">
                                <label class="block text-[10px] font-black uppercase opacity-40 mb-3 tracking-widest">Loyer
                                    HT / Mois</label>
                                <div class="flex items-baseline space-x-2">
                                    <input type="number" form="form-contrat" name="loyer_mensuel" id="input_loyer"
                                        class="w-full bg-transparent border-none p-0 text-5xl font-black text-indigo-400 focus:ring-0"
                                        placeholder="0" required>
                                    <span class="text-xs font-bold opacity-30">FCFA</span>
                                </div>
                            </div>

                            <div class="space-y-6 italic">
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase opacity-40 mb-2 tracking-widest">Caution</label>
                                    <input type="number" form="form-contrat" name="caution" id="input_caution"
                                        class="w-full bg-white/5 border-none rounded-2xl py-4 px-5 text-xl font-black text-white focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase opacity-40 mb-2 tracking-widest">Frais
                                        Agence</label>
                                    <input type="number" form="form-contrat" name="frais_agence"
                                        class="w-full bg-white/5 border-none rounded-2xl py-4 px-5 text-xl font-black text-white">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black uppercase opacity-40 mb-1">Jour de paiement
                                        (1-31)</label>
                                    <input type="number" name="jour_paiement" value="{{ old('jour_paiement', 5) }}"
                                        class="w-full bg-white/5 border-none rounded-xl py-3 px-4 text-xl font-bold">
                                </div>
                                <div class="pt-4 space-y-3">
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group p-3 rounded-2xl hover:bg-white/5 transition-all">
                                        <input type="checkbox" form="form-contrat" name="renouvellement_automatique"
                                            value="1"
                                            class="w-5 h-5 rounded border-none bg-white/20 text-indigo-500 focus:ring-0">
                                        <span class="text-[10px] font-black uppercase tracking-widest">Tacite
                                            Reconduction</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10 mt-10">
                            <button type="submit" form="form-contrat"
                                class="w-full bg-indigo-600 hover:bg-indigo-500 py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl transition-all group">
                                Créer le Bail <i
                                    class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal-contrat');
            const bienSelector = document.getElementById('bien_selector');
            const inputLoyer = document.getElementById('input_loyer');
            const inputCaution = document.getElementById('input_caution');
            const inputAgence = document.querySelector('input[name="frais_agence"]');

            // Gérer le changement de bien pour auto-remplir
            if (bienSelector) {
                bienSelector.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    if (selected.value !== "") {
                        const loyer = selected.getAttribute('data-loyer');
                        const caution = selected.getAttribute('data-caution');

                        inputLoyer.value = loyer || 0;
                        inputCaution.value = caution || 0;
                        if (inputAgence) inputAgence.value = loyer || 0;

                        [inputLoyer, inputCaution].forEach(el => {
                            el.style.transition = 'all 0.4s';
                            el.style.color = '#818cf8';
                            setTimeout(() => el.style.color = '', 500);
                        });
                    }
                });
            }

            // Fermer le modal avec Echap
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
