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
            <form action="{{ route('paiements.index') }}" method="GET" class="relative w-full max-w-xl group">
                <div class="relative flex items-center">
                    <span class="absolute left-5 text-slate-400 group-focus-within:text-black-600 transition-colors">
                        <i class="fas fa-search-dollar text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par numéro, mois, locataire, bien..."
                        class="w-full bg-white border border-gray-100 py-5 pl-14 pr-28 rounded-[2rem] shadow-sm focus:ring-4 focus:ring-dark-500/10 focus:border-indigo-500 font-black text-[11px] uppercase tracking-wider text-slate-700 placeholder:text-slate-300 transition-all outline-none italic">
                    <div class="absolute right-2">
                        <button type="submit"
                            class="bg-gray-900 text-white px-6 py-3 rounded-[1.5rem] font-black text-[9px] uppercase tracking-widest hover:bg-dark-600 transition-all active:scale-95">
                            Rechercher
                        </button>
                    </div>
                </div>
                @if (request('search'))
                    @if ($paiements->isEmpty())
                        <a href="{{ route('paiements.index') }}"
                            class="absolute -bottom-6 left-6 text-[9px] font-black text-red-500 uppercase tracking-tighter hover:text-red-700 transition-colors">
                            <i class="fas fa-times-circle mr-1"></i> Aucun élément trouvé pour "{{ request('search') }}".
                            Réinitialiser.
                        </a>
                    @else
                        <a href="{{ route('paiements.index') }}"
                            class="absolute -bottom-6 left-6 text-[9px] font-black text-emerald-500 uppercase tracking-tighter hover:text-emerald-700 transition-colors">
                            <i class="fas fa-check-circle mr-1"></i> {{ $paiements->count() }} résultat(s). Cliquez pour
                            effacer.
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
                    {{ number_format($totalEncaisse, 0, ',', ' ') }} <span class="text-sm">FCFA</span>
                </h3>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-exclamation-circle text-8xl text-orange-500"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Reste à Recouvrer</p>
                <h3 class="text-3xl font-black text-orange-500 tracking-tighter">
                    {{ number_format($totalReliquat, 0, ',', ' ') }} <span class="text-sm">FCFA</span>
                </h3>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-exchange-alt text-8xl text-indigo-600"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Volume Transactions</p>
                <h3 class="text-3xl font-black text-indigo-600 tracking-tighter">
                    {{ $totalTransactions }} <span class="text-sm">REÇUS</span>
                </h3>
            </div>
        </div>

        {{-- 3. TABLEAU --}}
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Référence</th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Locataire & Bien
                            </th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Période</th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Montant</th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Statut</th>
                            <th class="p-8 text-[10px] font-black uppercase text-gray-400 text-right tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($paiements as $paiement)
                            <tr class="group hover:bg-indigo-50/30 transition-all">

                                {{-- RÉFÉRENCE --}}
                                <td class="p-8">
                                    <span
                                        class="font-mono font-black text-gray-400 text-xs bg-gray-100 px-3 py-1.5 rounded-lg group-hover:bg-white transition-colors">
                                        {{ $paiement->numero }}
                                    </span>
                                    @if ($paiement->type_selection === 'multiple')
                                        <span class="block mt-1 text-[9px] font-black text-indigo-400 uppercase">
                                            <i class="fas fa-layer-group mr-1"></i> Multi-mois
                                        </span>
                                    @endif
                                </td>

                                {{-- LOCATAIRE & BIEN --}}
                                <td class="p-8">
                                    <div class="space-y-1">
                                        <span class="block font-black text-gray-800 uppercase text-xs tracking-tight">
                                            <i class="fas fa-user-circle mr-2 text-indigo-400 text-xs"></i>
                                            {{ $paiement->locataire->nom }} {{ $paiement->locataire->prenoms }}
                                        </span>
                                        <span class="block text-[9px] text-indigo-400 font-black uppercase tracking-widest">
                                            <i class="fas fa-home mr-1"></i> {{ $paiement->bien->titre }}
                                        </span>
                                        <span class="block text-[9px] text-indigo-400 font-black uppercase tracking-widest">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $paiement->bien->commune }},
                                            {{ $paiement->bien->quartier }}
                                        </span>
                                    </div>
                                </td>

                                {{-- PÉRIODE --}}
                                <td class="p-8">
                                    @if ($paiement->type_selection === 'multiple' && !empty($paiement->tous_les_mois))
                                        {{-- Multi-mois : liste tous les mois --}}
                                        <div class="space-y-1">
                                            @foreach ($paiement->tous_les_mois as $mois)
                                                <span
                                                    class="block text-[9px] font-black text-gray-600 uppercase bg-indigo-50 px-2 py-0.5 rounded-lg w-fit">
                                                    {{ formatMoisFr($mois) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span
                                            class="text-xs font-black text-gray-600 uppercase">{{ formatMoisFr($paiement->mois_annee) }}</span>
                                    @endif
                                </td>

                                {{-- MONTANT --}}
                                <td class="p-8">
                                    @php
                                        $montantAffiche =
                                            $paiement->type_selection === 'multiple'
                                                ? $paiement->montant_total_groupe ?? $paiement->montant_paye
                                                : $paiement->montant_paye;
                                    @endphp
                                    <div class="font-black text-gray-900 text-sm italic">
                                        {{ number_format($montantAffiche, 0, ',', ' ') }}
                                        <span class="text-[9px] opacity-50 tracking-tighter">FCFA</span>
                                    </div>
                                    @if ($paiement->type_selection === 'multiple')
                                        <div class="text-[9px] text-indigo-500 font-black uppercase mt-1">
                                            {{ count($paiement->tous_les_mois ?? []) }} mois
                                        </div>
                                    @elseif($paiement->reste_a_payer > 0)
                                        <div class="text-[9px] text-red-500 font-black uppercase mt-1">
                                            Reliquat: {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                                        </div>
                                    @endif
                                </td>

                                {{-- STATUT --}}
                                <td class="p-8">
                                    @php
                                        $statusMap = [
                                            'paye' => ['label' => 'Payé', 'color' => 'bg-emerald-100 text-emerald-600'],
                                            'partiel' => [
                                                'label' => 'Partiel',
                                                'color' => 'bg-orange-100 text-orange-600',
                                            ],
                                            'en_attente' => [
                                                'label' => 'Attente',
                                                'color' => 'bg-gray-100 text-gray-500',
                                            ],
                                            'retard' => ['label' => 'Retard', 'color' => 'bg-red-100 text-red-600'],
                                        ];
                                        $current = $statusMap[$paiement->statut] ?? [
                                            'label' => $paiement->statut,
                                            'color' => 'bg-gray-100 text-gray-500',
                                        ];
                                    @endphp
                                    <span
                                        class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $current['color'] }}">
                                        {{ $current['label'] }}
                                    </span>

                                    {{-- Badge retard --}}
                                    @if ($paiement->est_en_retard)
                                        <span
                                            class="block mt-1 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase bg-red-100 text-red-600 w-fit">
                                            <i class="fas fa-clock mr-1"></i> Tardif
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td class="p-8">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('paiements.show', $paiement) }}"
                                            class="p-3 bg-gray-50 text-gray-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('paiements.quittance', $paiement) }}"
                                            class="p-3 bg-gray-50 text-gray-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="p-32 text-center text-gray-300 font-black uppercase tracking-[0.4em] text-xs">
                                    <i class="fas fa-receipt mb-4 text-6xl block opacity-10"></i>
                                    Aucune transaction trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-8 bg-gray-50/50">{{ $paiements->links() }}</div>
        </div>

        @php
            function formatMoisFr($mois) {
                try {
                    if (preg_match('/^\d{4}-\d{2}$/', trim($mois))) {
                        return ucfirst(\Carbon\Carbon::createFromFormat('Y-m', $mois)
                            ->locale('fr')
                            ->translatedFormat('F Y'));
                    }

                    // Si le format est déjà un mois texte (ex: "Mai 2026"), on renvoie tel quel
                    if (preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+\s+\d{4}$/', trim($mois))) {
                        return ucfirst(trim($mois));
                    }

                    return ucfirst(\Carbon\Carbon::parse(trim($mois))
                        ->locale('fr')
                        ->translatedFormat('F Y'));
                } catch (Exception $e) {
                    return $mois;
                }
            }
        @endphp

        {{-- 4. MODAL D'ENCAISSEMENT --}}
        <div id="modal-paiement" class="fixed inset-0 z-50 hidden overflow-y-auto italic">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')">
            </div>

            <div class="relative min-h-screen flex items-start justify-center p-4 py-8">
                <div class="relative bg-white w-full max-w-5xl rounded-[3rem] shadow-2xl overflow-hidden">
                    <div class="flex flex-col md:flex-row">

                        {{-- FORMULAIRE GAUCHE --}}
                        <div class="flex-1 overflow-y-auto max-h-[90vh]">

                            {{-- Header sticky --}}
                            <div
                                class="sticky top-0 z-10 bg-white px-10 py-6 border-b border-gray-100 flex justify-between items-center">
                                <div>
                                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Encaissement
                                    </h2>
                                    <p class="text-indigo-500 text-[9px] font-black uppercase tracking-widest">Remplir tous
                                        les champs obligatoires *</p>
                                </div>
                                <button onclick="document.getElementById('modal-paiement').classList.add('hidden')"
                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fas fa-times-circle text-2xl"></i>
                                </button>
                            </div>

                            <div class="p-10 space-y-6">

                                {{-- Erreurs --}}
                                @if ($errors->has('mois_annee') || $errors->has('mois_multiples'))
                                    <div id="error-message"
                                        class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-2xl flex items-start gap-3 relative">
                                        <i class="fas fa-ban text-red-500 mt-0.5"></i>
                                        <div class="flex-1">
                                            <p class="text-xs font-black text-red-700 uppercase tracking-widest">Paiement
                                                bloqué</p>
                                            <p class="text-[11px] text-red-600 mt-1 font-medium">
                                                {{ $errors->first('mois_annee') }}
                                                {{ $errors->first('mois_multiples') }}
                                            </p>
                                        </div>
                                        <button onclick="this.closest('#error-message').style.display='none'"
                                            class="flex-shrink-0 text-red-400 hover:text-red-600 transition-colors focus:outline-none">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </div>
                                @endif

                                <form action="{{ route('paiements.store') }}" method="POST" id="form-paiement"
                                    class="space-y-6">
                                    @csrf

                                    {{-- CONTRAT + LOYER --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="relative">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Locataire
                                                / Bien *</label>
                                            <div class="relative">
                                                <select name="contrat_id" id="contrat_selector"
                                                    class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 appearance-none focus:ring-2 focus:ring-indigo-500 outline-none"
                                                    required>
                                                    <option value="" disabled selected>Sélectionner le bail...
                                                    </option>
                                                    @foreach ($contratsActifs ?? [] as $contrat)
                                                        <option value="{{ $contrat->id }}"
                                                            data-prix="{{ $contrat->loyer_mensuel }}"
                                                            data-locataire="{{ $contrat->locataire->nom }} {{ $contrat->locataire->prenoms }}"
                                                            data-bien="{{ $contrat->bien->titre }}"
                                                            data-debut="{{ $contrat->date_debut->format('Y-m') }}"
                                                            data-fin="{{ $contrat->date_fin->format('Y-m') }}">
                                                            {{ $contrat->locataire->nom }}
                                                            {{ $contrat->locataire->prenoms }} —
                                                            {{ $contrat->bien->titre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-indigo-500 uppercase mb-2 tracking-widest ml-1">Loyer
                                                Mensuel (Réf.)</label>
                                            <div class="relative">
                                                <input type="text" id="loyer_initial"
                                                    class="w-full bg-indigo-50/50 border-none rounded-2xl py-4 px-5 font-black text-indigo-400 text-xl"
                                                    placeholder="---" readonly>
                                                <span
                                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-30 text-indigo-500">FCFA</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- TYPE DE PAIEMENT (LOYER/CAUTION/AUTRE) --}}
                                    {{-- <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Nature
                                            du paiement *</label>
                                        <div class="grid grid-cols-3 gap-3">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="type" value="loyer" class="peer sr-only"
                                                    checked>
                                                <div
                                                    class="flex flex-col items-center p-3 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                                    <i
                                                        class="fas fa-home text-gray-400 peer-checked:text-indigo-500 mb-1 text-sm"></i>
                                                    <span
                                                        class="text-[9px] font-black uppercase text-gray-500">Loyer</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="type" value="caution"
                                                    class="peer sr-only">
                                                <div
                                                    class="flex flex-col items-center p-3 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all">
                                                    <i class="fas fa-shield-alt text-gray-400 mb-1 text-sm"></i>
                                                    <span
                                                        class="text-[9px] font-black uppercase text-gray-500">Caution</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="type" value="autre"
                                                    class="peer sr-only">
                                                <div
                                                    class="flex flex-col items-center p-3 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-gray-500 peer-checked:bg-gray-100 transition-all">
                                                    <i class="fas fa-ellipsis-h text-gray-400 mb-1 text-sm"></i>
                                                    <span
                                                        class="text-[9px] font-black uppercase text-gray-500">Autre</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div> --}}

                                    {{-- TOGGLE SIMPLE / MULTIPLE --}}
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest ml-1">Sélection
                                            des mois *</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="type_selection" value="simple"
                                                    class="peer sr-only" checked onchange="toggleTypeSelection('simple')">
                                                <div
                                                    class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                                    <i
                                                        class="fas fa-calendar-day text-gray-400 peer-checked:text-indigo-500 text-lg"></i>
                                                    <div>
                                                        <p class="text-xs font-black uppercase text-gray-700">1 Mois</p>
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="type_selection" value="multiple"
                                                    class="peer sr-only" onchange="toggleTypeSelection('multiple')">
                                                <div
                                                    class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                                    <i
                                                        class="fas fa-calendar-alt text-gray-400 peer-checked:text-indigo-500 text-lg"></i>
                                                    <div>
                                                        <p class="text-xs font-black uppercase text-gray-700">Plusieurs
                                                            Mois</p>
                                                        <p class="text-[9px] text-gray-400">Avance ou paiement anticipé</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- SECTION SIMPLE --}}
                                    <div id="section-simple">
                                        <div class="flex items-center justify-between mb-4">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                Mois concerné *
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="changerAnneeSimple(-1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-indigo-100 text-gray-500 hover:text-indigo-600 transition-all flex items-center justify-center">
                                                    <i class="fas fa-chevron-left text-xs"></i>
                                                </button>
                                                <span id="annee-simple-affichee"
                                                    class="text-lg font-black text-gray-800 min-w-[60px] text-center">
                                                    {{ date('Y') }}
                                                </span>
                                                <button type="button" onclick="changerAnneeSimple(1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-indigo-100 text-gray-500 hover:text-indigo-600 transition-all flex items-center justify-center">
                                                    <i class="fas fa-chevron-right text-xs"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Grille 12 mois --}}
                                        <div id="grille-mois-simple" class="grid grid-cols-3 md:grid-cols-4 gap-3"></div>

                                        {{-- Champ caché pour soumettre --}}
                                        <input type="hidden" name="mois_annee" id="input_mois_simple"
                                            value="{{ date('Y-m') }}">

                                        <div id="statut-mois-simple" class="mt-3"></div>
                                    </div>

                                    {{-- SECTION MULTIPLE --}}
                                    <div id="section-multiple" class="hidden">

                                        {{-- Sélecteur d'année --}}
                                        <div class="flex items-center justify-between mb-4">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                                Sélectionner les mois *
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="changerAnnee(-1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-indigo-100 text-gray-500 hover:text-indigo-600 transition-all flex items-center justify-center">
                                                    <i class="fas fa-chevron-left text-xs"></i>
                                                </button>
                                                <span id="annee-affichee"
                                                    class="text-lg font-black text-gray-800 min-w-[60px] text-center">
                                                    {{ date('Y') }}
                                                </span>
                                                <button type="button" onclick="changerAnnee(1)"
                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-indigo-100 text-gray-500 hover:text-indigo-600 transition-all flex items-center justify-center">
                                                    <i class="fas fa-chevron-right text-xs"></i>
                                                </button>
                                                <span id="recap-nb-mois"
                                                    class="text-[10px] font-black text-indigo-600 ml-2">
                                                    0 mois sélectionné(s)
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Grille des 12 mois --}}
                                        <div id="grille-mois" class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                            {{-- Généré par JS --}}
                                        </div>

                                        {{-- Résumé des mois sélectionnés (multi-années) --}}
                                        <div id="recap-selection"
                                            class="hidden mt-4 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                            <p
                                                class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mb-2">
                                                <i class="fas fa-check-circle mr-1"></i> Mois sélectionnés
                                            </p>
                                            <div id="liste-mois-selectionnes" class="flex flex-wrap gap-2"></div>
                                        </div>

                                        {{-- Alerte mois déjà payés ignorés --}}
                                        <div id="alerte-mois-ignores"
                                            class="hidden mt-3 p-3 bg-orange-50 border-l-4 border-orange-400 rounded-r-xl">
                                            <p class="text-[11px] font-bold text-orange-700">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                <span id="texte-mois-ignores"></span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- MODE + MONTANT + DATE --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Mode
                                                de Règlement *</label>
                                            <div class="grid grid-cols-4 gap-3">
                                                @foreach ([['especes', 'fa-money-bill-wave', 'Espèces'], ['cheque', 'fa-money-check', 'Chèque'], ['mobile_money', 'fa-mobile-alt', 'Mobile Money']] as [$val, $icon, $label])
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" name="mode_paiement"
                                                            value="{{ $val }}" class="peer sr-only"
                                                            {{ $val === 'especes' ? 'checked' : '' }} required>
                                                        <div
                                                            class="flex flex-col items-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-white transition-all">
                                                            <i class="fas {{ $icon }} text-gray-400 mb-2"></i>
                                                            <span
                                                                class="text-[10px] font-black uppercase text-gray-500">{{ $label }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Montant
                                                Versé *</label>
                                            <div class="relative">
                                                <input type="number" name="montant_paye" id="montant_paye"
                                                    class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-black text-indigo-600 text-xl focus:ring-2 focus:ring-indigo-500 outline-none"
                                                    placeholder="0" required>
                                                <span
                                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black opacity-30">FCFA</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Date
                                                d'encaissement</label>
                                            <div class="relative">
                                                <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}"
                                                    class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                                            </div>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Référence
                                                / N° de transaction</label>
                                            <input type="text" name="reference_paiement"
                                                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none"
                                                placeholder="Ex: Chèque N°12345, Réf Orange Money...">
                                        </div>
                                    </div>

                                    {{-- NOTES --}}
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest ml-1">Note
                                            ou Commentaire</label>
                                        <textarea name="notes" rows="2"
                                            class="w-full bg-gray-50 border-none rounded-2xl p-5 font-medium text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
                                            placeholder="Ex: Paiement anticipé pour les mois à venir..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- SIDEBAR DROITE --}}
                        <div
                            class="w-full md:w-[280px] bg-gray-900 p-10 flex flex-col justify-center relative overflow-hidden text-center md:text-left">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/20 blur-[60px] rounded-full"></div>
                            <div class="relative z-10 space-y-6">
                                <div class="p-6 bg-white/5 rounded-3xl border border-white/10 space-y-3">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Récapitulatif
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-gray-400">Locataire</span>
                                        <span id="recap-locataire" class="text-[10px] font-black text-white">—</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-gray-400">Bien</span>
                                        <span id="recap-bien"
                                            class="text-[10px] font-black text-white truncate max-w-[100px]">—</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-gray-400">Mois</span>
                                        <span id="recap-mois" class="text-[10px] font-black text-indigo-400">—</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-white/10 pt-3">
                                        <span class="text-[10px] text-gray-400">Total</span>
                                        <span id="recap-total" class="text-lg font-black text-indigo-400">— <span
                                                class="text-xs">FCFA</span></span>
                                    </div>
                                </div>
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                    <i class="fas fa-shield-alt text-indigo-400 text-2xl mb-3 block"></i>
                                    <p class="text-[10px] font-bold text-white uppercase tracking-widest leading-relaxed">
                                        Une quittance PDF par mois sera générée après validation.
                                    </p>
                                </div>
                                <button type="submit" form="form-paiement"
                                    class="w-full bg-indigo-600 hover:bg-emerald-500 py-6 rounded-[2rem] font-black text-xs text-white uppercase tracking-[0.3em] shadow-2xl transition-all group">
                                    Encaisser <i
                                        class="fas fa-check-circle ml-2 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const paiementsExistants = @json($paiementsExistants);

        const MOIS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        // ─── Variables globales ──────────────────────────────────────
        let loyerMensuel = 0;
        let contratDebut = null;
        let contratFin = null;
        let contratIdActif = null;
        let anneeActiveSimple = new Date().getFullYear();
        let anneeActive = new Date().getFullYear();
        let moisSimpleSelectionne = null;
        const moisSelectionnes = new Set();

        // ─── Utilitaires ─────────────────────────────────────────────
        function fmt(n) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(n || 0));
        }

        function moisNomDepuisValeur(valeur) {
            const d = new Date(valeur + '-01');
            return MOIS_FR[d.getMonth()] + ' ' + d.getFullYear();
        }

        function getStatutMois(contratId, moisNom) {
            return paiementsExistants.find(
                p => p.contrat_id == contratId && p.mois_annee === moisNom
            ) ?? null;
        }

        function isHorsContrat(annee, moisNum) {
            if (!contratDebut || !contratFin) return false;
            const [ad, md] = contratDebut.split('-').map(Number);
            const [af, mf] = contratFin.split('-').map(Number);
            return (
                annee < ad || (annee === ad && moisNum < md) ||
                annee > af || (annee === af && moisNum > mf)
            );
        }

        function classesMois(horsContrat, statut, isSelected, valeur, isDebutMois) {
            const reste = statut?.reste_a_payer ?? 0;

            if (horsContrat) return {
                border: 'border-gray-100',
                bg: 'bg-gray-50 opacity-40',
                text: 'text-gray-300',
                sub: 'text-gray-200',
                label: 'Hors contrat',
                disabled: true
            };
            if (isDebutMois) return {
                border: 'border-gray-300',
                bg: 'bg-gray-100',
                text: 'text-gray-500',
                sub: 'text-gray-400',
                label: '1er mois (réglé)',
                disabled: true
            };
            if (statut?.statut === 'paye') return {
                border: 'border-emerald-200',
                bg: 'bg-emerald-50 opacity-80',
                text: 'text-emerald-700',
                sub: 'text-emerald-500',
                label: '✓ Payé',
                disabled: true
            };
            if (statut?.statut === 'partiel') return {
                border: 'border-orange-300',
                bg: 'bg-orange-50',
                text: 'text-orange-600',
                sub: 'text-orange-500',
                label: reste > 0 ? 'Reste: ' + fmt(reste) : '· Partiel',
                disabled: false
            };
            if (isSelected) return {
                border: 'border-indigo-500',
                bg: 'bg-indigo-50',
                text: 'text-indigo-700',
                sub: 'text-indigo-500',
                label: '· Sélectionné',
                disabled: false
            };
            return {
                border: 'border-transparent',
                bg: 'bg-gray-50 hover:bg-indigo-50/50 hover:border-indigo-200',
                text: 'text-gray-700',
                sub: 'text-gray-400',
                label: '',
                disabled: false
            };
        }

        function renderCartesMois(annee, grille, modeSimple) {
            if (!grille) return;
            grille.innerHTML = '';

            for (let m = 0; m < 12; m++) {
                const moisNum = m + 1;
                const valeur = annee + '-' + String(moisNum).padStart(2, '0');
                const moisNom = MOIS_FR[m] + ' ' + annee;
                const hors = isHorsContrat(annee, moisNum);
                const statut = getStatutMois(contratIdActif, moisNom);
                const isSelected = modeSimple ?
                    moisSimpleSelectionne === valeur :
                    moisSelectionnes.has(valeur);
                const isDebutMois = contratDebut === valeur;

                const c = classesMois(hors, statut, isSelected, valeur, isDebutMois);

                const onclick = c.disabled ?
                    '' :
                    modeSimple ?
                    `selectMoisSimple('${valeur}', '${moisNom}')` :
                    `toggleMois('${valeur}', '${moisNom}')`;

                const wrap = document.createElement('div');
                wrap.style.cursor = c.disabled ? 'not-allowed' : 'pointer';
                wrap.innerHTML = `
            <div class="p-3 rounded-2xl border-2 transition-all text-center select-none
                        ${c.border} ${c.bg}"
                 onclick="${onclick}">
                <p class="text-[10px] font-black uppercase ${c.text}">
                    ${MOIS_FR[m]}
                </p>
                <p class="text-[9px] font-bold ${c.sub} leading-tight">
                    ${annee}${c.label ? '<br>' + c.label : ''}
                </p>
                ${isSelected
                    ? '<div class="mt-1 w-2 h-2 bg-indigo-500 rounded-full mx-auto"></div>'
                    : ''}
            </div>`;
                grille.appendChild(wrap);
            }
        }

        // ─── Fermer avec Echap ────────────────────────────────────────
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape')
                document.getElementById('modal-paiement').classList.add('hidden');
        });

        // ─── Rouvrir si erreur Laravel ────────────────────────────────
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('modal-paiement').classList.remove('hidden');
            });
        @endif

        // ─── Sélection du contrat ─────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('contrat_selector');
            if (!selector) return;

            selector.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                loyerMensuel = parseFloat(opt.getAttribute('data-prix')) || 0;
                contratDebut = opt.getAttribute('data-debut');
                contratFin = opt.getAttribute('data-fin');
                contratIdActif = opt.value;

                document.getElementById('loyer_initial').value = fmt(loyerMensuel);
                document.getElementById('recap-locataire').textContent =
                    opt.getAttribute('data-locataire') || '—';
                document.getElementById('recap-bien').textContent =
                    opt.getAttribute('data-bien') || '—';
                document.getElementById('montant_paye').value = loyerMensuel;

                // Initialiser les deux grilles sur l'année de début du contrat
                if (contratDebut) {
                    anneeActiveSimple = parseInt(contratDebut.split('-')[0]);
                    anneeActive = parseInt(contratDebut.split('-')[0]);
                }
                document.getElementById('annee-simple-affichee').textContent = anneeActiveSimple;
                document.getElementById('annee-affichee').textContent = anneeActive;

                // Reset sélections
                moisSimpleSelectionne = null;
                moisSelectionnes.clear();
                document.getElementById('input_mois_simple').value = '';

                // Vider le statut simple
                document.getElementById('statut-mois-simple').innerHTML = '';

                // Regénérer les grilles
                genererGrilleSimple();
                genererGrilleMultiple();
                updateRecap();
            });
        });

        // ─── Validation du formulaire ──────────────────────────────────
        document.getElementById('form-paiement').addEventListener('submit', function(e) {
            const montant = parseFloat(document.getElementById('montant_paye').value) || 0;
            if (montant <= 0) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Montant invalide',
                        html: 'Le montant versé ne peut pas être nul ou négatif.',
                        icon: 'warning',
                        confirmButtonColor: '#f59e0b',
                        confirmButtonText: 'Corriger',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    });
                } else {
                    alert('Le montant versé ne peut pas être nul ou négatif.');
                }
                document.getElementById('montant_paye').focus();
                return false;
            }
        });

        // ─── MODE SIMPLE ──────────────────────────────────────────────

        function changerAnneeSimple(delta) {
            if (!contratDebut || !contratFin) return;
            const ad = parseInt(contratDebut.split('-')[0]);
            const af = parseInt(contratFin.split('-')[0]);
            anneeActiveSimple = Math.max(ad, Math.min(af, anneeActiveSimple + delta));
            document.getElementById('annee-simple-affichee').textContent = anneeActiveSimple;
            genererGrilleSimple();
        }

        function genererGrilleSimple() {
            renderCartesMois(
                anneeActiveSimple,
                document.getElementById('grille-mois-simple'),
                true
            );
        }

        function selectMoisSimple(valeur, moisNom) {
            if (valeur === contratDebut) {
                // le premier mois du contrat est déjà réglé
                return;
            }

            moisSimpleSelectionne = valeur;
            document.getElementById('input_mois_simple').value = valeur;
            genererGrilleSimple();
            verifierMoisSimple(valeur, moisNom);
            updateRecap();
        }

        function verifierMoisSimple(valeur, moisNomParam) {
            if (!contratIdActif || !valeur) return;
            const moisNom = moisNomParam ?? moisNomDepuisValeur(valeur);
            const existant = getStatutMois(contratIdActif, moisNom);
            const div = document.getElementById('statut-mois-simple');

            if (!existant) {
                div.innerHTML = `<span class="text-[11px] font-bold text-emerald-600">
            ✓ ${moisNom} — mois non payé</span>`;
            } else if (existant.statut === 'paye') {
                div.innerHTML = `<span class="text-[11px] font-bold text-red-600">
            ✗ ${moisNom} — déjà payé en totalité</span>`;
            } else if (existant.statut === 'partiel') {
                const reste = existant.reste_a_payer ?? 0;
                div.innerHTML = `<span class="text-[11px] font-bold text-orange-600">
            ⚠ ${moisNom} — paiement partiel · Reste : ${fmt(reste)} FCFA</span>`;
            }
        }

        // ─── MODE MULTIPLE ────────────────────────────────────────────

        function changerAnnee(delta) {
            if (!contratDebut || !contratFin) return;
            const ad = parseInt(contratDebut.split('-')[0]);
            const af = parseInt(contratFin.split('-')[0]);
            anneeActive = Math.max(ad, Math.min(af, anneeActive + delta));
            document.getElementById('annee-affichee').textContent = anneeActive;
            genererGrilleMultiple();
        }

        function genererGrilleMultiple() {
            renderCartesMois(
                anneeActive,
                document.getElementById('grille-mois'),
                false
            );
            syncInputsHidden();
            updateRecapSelection();
            updateAlerteIgnores();
        }

        function toggleMois(valeur, moisNom) {
            // Ne pas permettre la sélection d'un mois déjà payé en entier
            if (valeur === contratDebut) {
                // le mois de début de contrat est considéré comme réglé
                return;
            }

            const statut = getStatutMois(contratIdActif, moisNom);
            if (statut?.statut === 'paye') return;

            if (moisSelectionnes.has(valeur)) {
                moisSelectionnes.delete(valeur);
            } else {
                moisSelectionnes.add(valeur);
            }
            genererGrilleMultiple();
            updateRecap();
        }

        function syncInputsHidden() {
            document.querySelectorAll('input[name="mois_multiples[]"]')
                .forEach(el => el.remove());
            const form = document.getElementById('form-paiement');
            moisSelectionnes.forEach(val => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'mois_multiples[]';
                input.value = val;
                form.appendChild(input);
            });
        }

        function updateRecapSelection() {
            const container = document.getElementById('recap-selection');
            const liste = document.getElementById('liste-mois-selectionnes');
            const nb = moisSelectionnes.size;

            const recapNb = document.getElementById('recap-nb-mois');
            if (recapNb) recapNb.textContent = nb + ' mois sélectionné(s)';

            if (!container) return;
            if (nb === 0) {
                container.classList.add('hidden');
                return;
            }
            container.classList.remove('hidden');

            const sorted = [...moisSelectionnes].sort();
            liste.innerHTML = sorted.map(val => {
                const nom = moisNomDepuisValeur(val);
                return `<span class="px-3 py-1 bg-white border border-indigo-200 text-indigo-700
                             text-[10px] font-black uppercase rounded-lg flex items-center gap-1">
                    ${nom}
                    <span onclick="toggleMois('${val}', '${nom}')"
                          style="cursor:pointer"
                          class="text-indigo-400 hover:text-red-500 ml-1 transition-colors">✕</span>
                </span>`;
            }).join('');
        }

        function updateAlerteIgnores() {
            const moisIgnores = [...moisSelectionnes]
                .filter(val => {
                    const nom = moisNomDepuisValeur(val);
                    const ex = getStatutMois(contratIdActif, nom);
                    return ex?.statut === 'paye';
                })
                .map(val => moisNomDepuisValeur(val));

            const alerte = document.getElementById('alerte-mois-ignores');
            if (!alerte) return;

            if (moisIgnores.length > 0) {
                alerte.classList.remove('hidden');
                document.getElementById('texte-mois-ignores').textContent =
                    moisIgnores.join(', ') + ' sont déjà réglés et seront ignorés.';
            } else {
                alerte.classList.add('hidden');
            }
        }

        // ─── Toggle simple / multiple ─────────────────────────────────
        function toggleTypeSelection(type) {
            document.getElementById('section-simple')
                .classList.toggle('hidden', type !== 'simple');
            document.getElementById('section-multiple')
                .classList.toggle('hidden', type !== 'multiple');
            const inputMois = document.querySelector('input[name="mois_annee"]');
            if (inputMois) inputMois.required = (type === 'simple');
            updateRecap();
        }

        // ─── Récapitulatif sidebar ────────────────────────────────────
        function montantPourMois(valeur) {
            if (!contratIdActif || !valeur) return 0;
            const nom = moisNomDepuisValeur(valeur);
            const ex = getStatutMois(contratIdActif, nom);

            if (ex?.statut === 'paye') return 0;
            if (ex?.statut === 'partiel') return parseFloat(ex.reste_a_payer) || 0;
            return parseFloat(loyerMensuel || 0);
        }

        function calculRecapSelection() {
            const isMultiple = document.querySelector('input[name="type_selection"]:checked')?.value === 'multiple';
            let total = 0;
            let nb = 0;

            if (isMultiple) {
                moisSelectionnes.forEach(valeur => {
                    const montant = montantPourMois(valeur);
                    if (montant > 0) nb++;
                    total += montant;
                });
            } else {
                if (moisSimpleSelectionne) {
                    const montant = montantPourMois(moisSimpleSelectionne);
                    nb = montant > 0 ? 1 : 0;
                    total = montant;
                }
            }

            return { nb, total };
        }

        function updateRecap() {
            const { nb, total } = calculRecapSelection();
            const recapMois = document.getElementById('recap-mois');
            const recapTotal = document.getElementById('recap-total');

            if (recapMois) recapMois.textContent = nb + ' mois';
            if (recapTotal) recapTotal.innerHTML = fmt(total) + ' <span class="text-xs">FCFA</span>';

            const montantInput = document.getElementById('montant_paye');
            if (montantInput) {
                montantInput.value = total > 0 ? total : '';
            }

            if (document.querySelector('input[name="type_selection"]:checked')?.value === 'multiple') {
                syncInputsHidden();
            }
        }
    </script>
@endsection
