@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="min-h-screen bg-[#F0F2F7]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- ═══════════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] mb-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                Tableau de bord · {{ now()->locale('fr')->translatedFormat('d F Y') }}
            </p>
            <h1 class="text-4xl font-black text-[#1E293B] uppercase tracking-tighter leading-none">
                Vue d'ensemble
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('paiements.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-[#1E293B] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg">
                <i class="fas fa-cash-register"></i> Encaisser
            </a>
            <a href="{{ route('contrats.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-[#1E293B] rounded-2xl text-[10px] font-black uppercase tracking-widest hover:border-indigo-400 hover:text-indigo-600 transition-all shadow-sm">
                <i class="fas fa-plus"></i> Nouveau bail
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         KPI CARDS
    ═══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Biens --}}
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fas fa-building text-sm"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Biens</p>
                <p class="text-4xl font-black text-[#1E293B] leading-none">{{ $stats['total_biens'] }}</p>
                <p class="text-[10px] text-gray-400 font-bold mt-3 uppercase">Unités enregistrées</p>
            </div>
        </div>

        {{-- Recettes --}}
        <div class="bg-[#1E293B] rounded-[2rem] p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-xl"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 bg-white/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-5">
                    <i class="fas fa-wallet text-sm"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Recettes</p>
                <p class="text-2xl font-black text-white leading-none">
                    {{ number_format($stats['revenus_mois'], 0, ',', ' ') }}
                    <span class="text-sm font-bold text-gray-400">FCFA</span>
                </p>
                <p class="text-[10px] text-emerald-400 font-black mt-3 uppercase flex items-center gap-1">
                    <i class="fas fa-arrow-up text-[8px]"></i> Flux de trésorerie
                </p>
            </div>
        </div>

        {{-- Taux d'occupation --}}
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                @php $taux = $stats['total_biens'] > 0 ? round(($stats['biens_occupes']/$stats['total_biens'])*100) : 0; @endphp
                <div class="flex items-center justify-between mb-5">
                    <div class="w-11 h-11 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="fas fa-key text-sm"></i>
                    </div>
                    <span class="text-2xl font-black text-blue-600">{{ $taux }}%</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Occupation</p>
                <p class="text-4xl font-black text-[#1E293B] leading-none">
                    {{ $stats['biens_occupes'] }}
                    <span class="text-xl text-gray-300">/ {{ $stats['total_biens'] }}</span>
                </p>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4 overflow-hidden">
                    <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-700"
                         style="width: {{ $taux }}%"></div>
                </div>
            </div>
        </div>

        {{-- Incidents --}}
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-11 h-11 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-all">
                        <i class="fas fa-tools text-sm"></i>
                    </div>
                    @if($stats['incidents_actifs'] > 0)
                        <span class="flex h-2.5 w-2.5 rounded-full bg-red-500 animate-pulse"></span>
                    @endif
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Incidents Actifs</p>
                <p class="text-4xl font-black text-[#1E293B] leading-none">{{ $stats['incidents_actifs'] }}</p>
                <p class="text-[10px] {{ $stats['incidents_actifs'] > 0 ? 'text-red-500' : 'text-emerald-500' }} font-black mt-3 uppercase">
                    {{ $stats['incidents_actifs'] > 0 ? 'Attention requise' : 'Tout est ok' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- TABLEAU RECETTES RÉCENTES --}}
        <div class="lg:col-span-8 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-[#1E293B] uppercase tracking-tight">Recettes Récentes</h3>
                    <p class="text-[10px] text-gray-400 font-bold mt-0.5">Derniers flux financiers encaissés</p>
                </div>
                <a href="{{ route('paiements.index') }}"
                   class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                    Tout voir <i class="fas fa-arrow-right text-[8px]"></i>
                </a>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($derniersPaiements as $paiement)
                <div class="px-8 py-5 flex items-center justify-between hover:bg-gray-50/50 transition-colors group">
                    <div class="flex items-center gap-4">
                        {{-- Avatar initiales --}}
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-xs flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            {{ strtoupper(substr($paiement->locataire->prenoms, 0, 1)) }}{{ strtoupper(substr($paiement->locataire->nom, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-[#1E293B] uppercase tracking-tight">
                                {{ $paiement->locataire->prenoms }} {{ $paiement->locataire->nom }}
                            </p>
                            <p class="text-[10px] text-gray-400 font-bold">
                                <i class="fas fa-home mr-1 text-indigo-300"></i>{{ $paiement->bien->titre }}
                                · {{ $paiement->mois_annee }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right flex items-center gap-4">
                        <div>
                            <p class="font-black text-[#1E293B] text-sm">
                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }}
                                <span class="text-[10px] text-gray-400 font-bold">FCFA</span>
                            </p>
                            <p class="text-[10px] text-gray-400 font-bold">
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                            </p>
                        </div>
                        @php
                            $badgeMap = [
                                'paye'    => ['label'=>'Payé',    'class'=>'bg-emerald-100 text-emerald-700'],
                                'partiel' => ['label'=>'Partiel', 'class'=>'bg-orange-100 text-orange-700'],
                                'retard'  => ['label'=>'Retard',  'class'=>'bg-red-100 text-red-700'],
                            ];
                            $badge = $badgeMap[$paiement->statut] ?? ['label'=>'En attente','class'=>'bg-gray-100 text-gray-500'];
                        @endphp
                        <span class="px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <i class="fas fa-receipt text-4xl text-gray-200 mb-3 block"></i>
                    <p class="text-sm text-gray-400 italic font-medium">Aucun flux récent à afficher.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- COLONNE DROITE --}}
        <div class="lg:col-span-4 space-y-5">

            {{-- ACTIONS RAPIDES --}}
            <div class="bg-[#1E293B] rounded-[2.5rem] p-7 text-white relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-4 -top-4 w-24 h-24 bg-white/3 rounded-full pointer-events-none"></div>
                <div class="relative z-10">
                    <h3 class="font-black text-lg uppercase tracking-tighter mb-5">Actions Rapides</h3>
                    <div class="space-y-2.5">
                        <a href="{{ route('biens.index') }}"
                           class="flex items-center justify-between p-4 bg-white/5 hover:bg-indigo-600 border border-white/10 hover:border-indigo-500 rounded-2xl transition-all group/btn">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-indigo-300 group-hover/btn:text-white">
                                    <i class="fas fa-building text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Mes Biens</span>
                            </div>
                            <i class="fas fa-arrow-right text-[9px] opacity-50 group-hover/btn:opacity-100 group-hover/btn:translate-x-0.5 transition-all"></i>
                        </a>
                        <a href="{{ route('locataires.index') }}"
                           class="flex items-center justify-between p-4 bg-white/5 hover:bg-indigo-600 border border-white/10 hover:border-indigo-500 rounded-2xl transition-all group/btn">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-indigo-300 group-hover/btn:text-white">
                                    <i class="fas fa-users text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Locataires</span>
                            </div>
                            <i class="fas fa-arrow-right text-[9px] opacity-50 group-hover/btn:opacity-100 group-hover/btn:translate-x-0.5 transition-all"></i>
                        </a>
                        <a href="{{ route('contrats.index') }}"
                           class="flex items-center justify-between p-4 bg-white/5 hover:bg-indigo-600 border border-white/10 hover:border-indigo-500 rounded-2xl transition-all group/btn">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-indigo-300 group-hover/btn:text-white">
                                    <i class="fas fa-file-contract text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Contrats</span>
                            </div>
                            <i class="fas fa-arrow-right text-[9px] opacity-50 group-hover/btn:opacity-100 group-hover/btn:translate-x-0.5 transition-all"></i>
                        </a>
                        <a href="{{ route('paiements.index') }}"
                           class="flex items-center justify-between p-4 bg-emerald-500/20 hover:bg-emerald-500 border border-emerald-500/30 hover:border-emerald-400 rounded-2xl transition-all group/btn">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 group-hover/btn:text-white">
                                    <i class="fas fa-hand-holding-usd text-xs"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300 group-hover/btn:text-white">Encaisser Loyer</span>
                            </div>
                            <i class="fas fa-arrow-right text-[9px] text-emerald-400 group-hover/btn:text-white group-hover/btn:translate-x-0.5 transition-all"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- RÉPARTITION BIENS --}}
            <div class="bg-white rounded-[2.5rem] p-7 border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Répartition du parc</p>
                @php
                    $occupes    = $stats['biens_occupes'];
                    $total      = $stats['total_biens'];
                    $disponibles = $total - $occupes;
                @endphp
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Occupés</span>
                            <span class="text-[10px] font-black text-emerald-600">{{ $occupes }} / {{ $total }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-700"
                                 style="width: {{ $total > 0 ? ($occupes/$total)*100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Disponibles</span>
                            <span class="text-[10px] font-black text-indigo-600">{{ $disponibles }} / {{ $total }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-indigo-400 h-2 rounded-full transition-all duration-700"
                                 style="width: {{ $total > 0 ? ($disponibles/$total)*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Stats mini --}}
                <div class="grid grid-cols-2 gap-3 mt-5">
                    <div class="bg-emerald-50 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-black text-emerald-600">{{ $occupes }}</p>
                        <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-0.5">Occupés</p>
                    </div>
                    <div class="bg-indigo-50 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-black text-indigo-600">{{ $disponibles }}</p>
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mt-0.5">Libres</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
</div>
@endsection