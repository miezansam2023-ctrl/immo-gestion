@extends('layouts.app')

@section('title', 'Gestion des Loyers')

@section('content')
<div class="max-w-7xl mx-auto pb-12 space-y-8 px-4 py-8 italic">
    
    {{-- 1. EN-TÊTE & ACTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Flux Financiers</h1>
            <p class="text-indigo-600 font-bold text-[10px] tracking-[0.3em] uppercase mt-1 flex items-center">
                <span class="w-2 h-2 bg-indigo-500 rounded-full animate-ping mr-2"></span> Historique des encaissements
            </p>
        </div>
        {{-- BARRE DE RECHERCHE PAIEMENTS --}}
        <form action="{{ route('paiements.index') }}" method="GET" class="relative w-full max-w-xl group">
            <div class="relative flex items-center">
                <span class="absolute left-5 text-slate-400 group-focus-within:text-black-600 transition-colors">
                    <i class="fas fa-search-dollar text-sm"></i>
                </span>

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Rechercher un paiement, un locataire..."
                    class="w-full bg-white border border-gray-100 py-5 pl-14 pr-28 rounded-[2rem] shadow-sm focus:ring-4 focus:ring-dark-500/10 focus:border-indigo-500 font-black text-[11px] uppercase tracking-wider text-slate-700 placeholder:text-slate-300 transition-all outline-none italic">

                <div class="absolute right-2">
                    <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-[1.5rem] font-black text-[9px] uppercase tracking-widest hover:bg-dark-600 transition-all active:scale-95">
                        Rechercher
                    </button>
                </div>
            </div>

            @if (request('search'))
                        @if ($paiements->isEmpty())
                            {{-- Cas : Aucun résultat trouvé --}}
                            <a href="{{ route('paiements.index') }}"
                                class="absolute -bottom-6 left-6 text-[9px] font-black text-red-500 uppercase tracking-tighter hover:text-red-700 transition-colors">
                                <i class="fas fa-times-circle mr-1"></i> Aucun élément trouvé pour
                                "{{ request('search') }}". Réinitialiser la recherche.
                            </a>
                        @else
                            {{-- Cas : Résultats trouvés, on affiche un bouton pour annuler --}}
                            <a href="{{ route('paiements.index') }}"
                                class="absolute -bottom-6 left-6 text-[9px] font-black text-emerald-500 uppercase tracking-tighter hover:text-emerald-700 transition-colors">
                                <i class="fas fa-check-circle mr-1"></i> {{ $paiements->count() }} résultat(s) trouvé(s).
                                Cliquez pour effacer.
                            </a>
                        @endif
                    @endif
        </form>
        <button onclick="document.getElementById('modal-paiement').classList.remove('hidden')"
            class="bg-gray-900 text-white px-8 py-4 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl hover:bg-indigo-600 transition-all active:scale-95 flex items-center group">
            <i class="fas fa-cash-register mr-3 group-hover:rotate-12 transition-transform"></i> Encaisser un loyer
        </button>
    </div>

    {{-- 2. STATS RAPIDES --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-hand-holding-usd text-8xl text-emerald-600"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Total Encaissé</p>
            <h3 class="text-3xl font-black text-emerald-600 tracking-tighter">
                {{ number_format($paiements->where('statut', 'paye')->sum('montant_paye'), 0, ',', ' ') }} <span class="text-sm">FCFA</span>
            </h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-exclamation-circle text-8xl text-orange-500"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Reste à Recouvrer</p>
            <h3 class="text-3xl font-black text-orange-500 tracking-tighter">
                {{ number_format($paiements->sum('reste_a_payer'), 0, ',', ' ') }} <span class="text-sm">FCFA</span>
            </h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-exchange-alt text-8xl text-indigo-600"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Volume Transactions</p>
            <h3 class="text-3xl font-black text-indigo-600 tracking-tighter">{{ $paiements->count() }} <span class="text-sm">REÇUS</span></h3>
        </div>
    </div>

    {{-- 3. TABLEAU DES PAIEMENTS --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Référence</th>
                        <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Locataire & Bien</th>
                        <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Période</th>
                        <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Montant</th>
                        <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Statut</th>
                        <th class="p-8 text-[10px] font-black uppercase text-gray-400 text-right tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($paiements as $paiement)
                    <tr class="group hover:bg-indigo-50/30 transition-all">
                        <td class="p-8">
                            <span class="font-mono font-black text-gray-400 text-xs bg-gray-100 px-3 py-1.5 rounded-lg group-hover:bg-white transition-colors">
                                {{ $paiement->numero }}
                            </span>
                        </td>
                        <td class="p-8">
                            <div class="space-y-1">
                                <span class="block font-black text-gray-800 uppercase text-xs tracking-tight">
                                    <i class="fas fa-user-circle mr-2 text-indigo-400 text-xs"></i>
                                    {{ $paiement->locataire->nom }} {{ $paiement->locataire->prenoms }}
                                </span>
                                <span class="block text-[9px] text-indigo-400 font-black uppercase tracking-widest">
                                    <i class="fas fa-home mr-1"></i> {{ $paiement->bien->titre }}
                                </span>
                            </div>
                        </td>
                        <td class="p-8">
                            <span class="text-xs font-black text-gray-600 uppercase">{{ $paiement->mois_annee }}</span>
                        </td>
                        <td class="p-8">
                            <div class="font-black text-gray-900 text-sm italic">
                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} <span class="text-[9px] opacity-50 tracking-tighter">FCFA</span>
                            </div>
                            @if($paiement->reste_a_payer > 0)
                            <div class="text-[9px] text-red-500 font-black uppercase mt-1">
                                Reliquat: {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                            </div>
                            @endif
                        </td>
                        <td class="p-8">
                            @php
                            $statusMap = [
                            'paye' => ['label' => 'Payé', 'color' => 'bg-emerald-100 text-emerald-600'],
                            'partiel' => ['label' => 'Partiel', 'color' => 'bg-orange-100 text-orange-600'],
                            'en_attente' => ['label' => 'Attente', 'color' => 'bg-gray-100 text-gray-500'],
                            'retard' => ['label' => 'Retard', 'color' => 'bg-red-100 text-red-600'],
                            ];
                            $current = $statusMap[$paiement->statut] ?? ['label' => $paiement->statut, 'color' => 'bg-gray-100 text-gray-500'];
                            @endphp
                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $current['color'] }}">
                                {{ $current['label'] }}
                            </span>
                        </td>
                        <td class="p-8">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('paiements.show', $paiement) }}" class="p-3 bg-gray-50 text-gray-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                <a href="{{ route('paiements.quittance', $paiement) }}" class="p-3 bg-gray-50 text-gray-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-file-pdf"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-32 text-center text-gray-300 font-black uppercase tracking-[0.4em] text-xs">
                            <i class="fas fa-receipt mb-4 text-6xl block opacity-10"></i>
                            Aucune transaction trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-8 bg-gray-50/50">
            {{ $paiements->links() }}
        </div>
    </div>

    {{-- 4. MODAL D'ENCAISSEMENT --}}
    <div id="modal-paiement" class="fixed inset-0 z-50 hidden overflow-y-auto italic">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="this.parentElement.classList.add('hidden')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden">
                <div class="flex flex-col md:flex-row">

                    {{-- Formulaire (Gauche) --}}
                    <div class="flex-1 p-10 md:p-12">
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">Encaissement</h2>
                            <button onclick="document.getElementById('modal-paiement').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </button>
                        </div>

                        <form action="{{ route('paiements.store') }}" method="POST" id="form-paiement" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- SELECT CONTRAT STYLISÉ --}}
                                <div class="relative group">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Contrat / Locataire *</label>
                                    <div class="relative">
                                        <select name="contrat_id" id="contrat_selector"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 transition-all cursor-pointer appearance-none focus:ring-2 focus:ring-indigo-500 outline-none"
                                            required>
                                            <option value="" disabled selected>Sélectionner le bail...</option>
                                            @foreach($contratsActifs ?? [] as $contrat)
                                            {{-- ATTENTION ICI : On ajoute le data-prix --}}
                                            <option value="{{ $contrat->id }}" data-prix="{{ $contrat->loyer_mensuel }}">
                                                {{ $contrat->locataire->nom }} - {{ $contrat->bien->titre }}
                                            </option>
                                            @endforeach
                                        </select>
                                        {{-- Flèche Custom --}}
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 group-focus-within:text-indigo-500">
                                            <i class="fas fa-chevron-down text-[10px]"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1 text-indigo-500">Loyer Initial (Réf)</label>
                                    <div class="relative">
                                        <input type="text" id="loyer_initial"
                                            class="w-full bg-indigo-50/50 border-none rounded-2xl py-4 px-5 font-black text-indigo-400 text-xl"
                                            placeholder="---" readonly>
                                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-30 tracking-tighter text-indigo-500">FCFA</span>
                                    </div>
                                </div>


                                {{-- MODE DE PAIEMENT --}}
                                <div class="group">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Mode de Règlement *</label>
                                    <div class="grid grid-cols-3 gap-4">
                                        {{-- Option Espèces --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="mode_paiement" value="especes" class="peer sr-only" checked required>
                                            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-white transition-all group">
                                                <i class="fas fa-money-bill-wave text-gray-400 group-hover:text-indigo-500 peer-checked:text-indigo-500 mb-2"></i>
                                                <span class="text-[10px] font-black uppercase text-gray-500">Espèces</span>
                                            </div>
                                        </label>

                                        {{-- Option Virement/Mobile Money --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="mode_paiement" value="virement" class="peer sr-only">
                                            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-white transition-all group">
                                                <i class="fas fa-university text-gray-400 group-hover:text-indigo-500 peer-checked:text-indigo-500 mb-2"></i>
                                                <span class="text-[10px] font-black uppercase text-gray-500">Virement</span>
                                            </div>
                                        </label>

                                        {{-- Option Chèque --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="mode_paiement" value="cheque" class="peer sr-only">
                                            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-white transition-all group">
                                                <i class="fas fa-money-check text-gray-400 group-hover:text-indigo-500 peer-checked:text-indigo-500 mb-2"></i>
                                                <span class="text-[10px] font-black uppercase text-gray-500">Chèque</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- INPUT MOIS STYLISÉ --}}
                                <div class="relative group">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Période (Mois/Année) *</label>
                                    <div class="relative">
                                        <input type="month" name="mois_annee" value="{{ date('Y-m') }}"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 appearance-none focus:ring-2 focus:ring-indigo-500 outline-none"
                                            required>
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                            <i class="fas fa-calendar-alt text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 italic">
                                {{-- MONTANT --}}
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Montant Versé *</label>
                                    <div class="relative">
                                        <input type="number" name="montant_paye" id="montant_paye"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-black text-indigo-600 text-xl focus:ring-2 focus:ring-indigo-500 outline-none"
                                            placeholder="0" required>
                                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-30 tracking-tighter">FCFA</span>
                                    </div>
                                </div>

                                {{-- DATE DE PAIEMENT STYLISÉE --}}
                                <div class="relative group">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Date de Paiement</label>
                                    <div class="relative">
                                        <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}"
                                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 appearance-none focus:ring-2 focus:ring-indigo-500 outline-none">
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                            <i class="fas fa-calendar-day text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- NOTES --}}
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Note ou Commentaire</label>
                                <textarea name="notes" rows="2"
                                    class="w-full bg-gray-50 border-none rounded-2xl p-5 font-medium text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
                                    placeholder="Ex: Paiement par chèque N°..."></textarea>
                            </div>
                        </form>
                    </div>

                    {{-- Sidebar Validation (Droite) --}}
                    <div class="w-full md:w-[300px] bg-gray-900 p-10 flex flex-col justify-center relative overflow-hidden text-center md:text-left">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/20 blur-[60px] rounded-full"></div>
                        <div class="relative z-10 space-y-6">
                            <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                                <i class="fas fa-shield-alt text-indigo-400 text-3xl mb-4 block"></i>
                                <p class="text-[10px] font-bold text-white uppercase tracking-widest leading-relaxed">
                                    Une quittance PDF sera générée automatiquement après validation.
                                </p>
                            </div>
                            <button type="submit" form="form-paiement" class="w-full bg-indigo-600 hover:bg-emerald-500 py-6 rounded-[2rem] font-black text-xs text-white uppercase tracking-[0.3em] shadow-2xl transition-all group">
                                Encaisser <i class="fas fa-check-circle ml-2 group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fermeture du modal avec Echap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.getElementById('modal-paiement').classList.add('hidden');
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contratSelector = document.getElementById('contrat_selector');
        const loyerInitial = document.getElementById('loyer_initial');
        const montantPaye = document.getElementById('montant_paye');

        if (contratSelector) {
            contratSelector.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const prix = selectedOption.getAttribute('data-prix');

                if (prix) {
                    // Affiche le prix formaté pour la lecture
                    loyerInitial.value = new Intl.NumberFormat('fr-FR').format(prix);
                    // Remplit le vrai champ numérique pour le formulaire
                    montantPaye.value = prix;

                    // Animation visuelle
                    loyerInitial.classList.add('ring-2', 'ring-indigo-500');
                    setTimeout(() => loyerInitial.classList.remove('ring-2', 'ring-indigo-500'), 500);
                } else {
                    loyerInitial.value = '---';
                    montantPaye.value = '';
                }
            });
        }
    });
</script>
@endsection