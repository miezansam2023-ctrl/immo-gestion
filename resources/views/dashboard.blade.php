@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if(session('success'))
    <div x-data="{ show: true }"
        x-show="show"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="relative mb-6 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm tracking-wide">

        <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1">
            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <div class="ml-4">
            <h3 class="text-xs font-black text-emerald-900 uppercase tracking-widest">Opération réussie</h3>
            <p class="text-[11px] font-bold text-emerald-700 mt-0.5">
                {{ session('success') }}
            </p>
        </div>

        <button @click="show = false" class="ml-auto group p-2">
            <svg class="h-5 w-5 text-emerald-300 group-hover:text-emerald-600 transition-colors"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:scale-[1.02] transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <span class="text-[10px] font-black bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full uppercase tracking-tighter italic">Patrimoine</span>
            </div>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Total Biens</p>
            <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total_biens'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold mt-2 italic">Unités enregistrées</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:scale-[1.02] transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-4 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 px-2 py-1 rounded-full uppercase tracking-tighter italic">Finance</span>
            </div>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Recettes (Mois)</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($stats['revenus_mois'], 0, ',', ' ') }} <span class="text-sm">CFA</span></h3>
            <p class="text-[10px] text-emerald-500 font-black mt-2 uppercase tracking-tight"><i class="fas fa-arrow-up mr-1"></i>Flux de trésorerie</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:scale-[1.02] transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-4 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-lg font-black text-blue-600">{{ $stats['total_biens'] > 0 ? round(($stats['biens_occupes']/$stats['total_biens'])*100) : 0 }}%</span>
                </div>
            </div>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Taux d'occupation</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $stats['biens_occupes'] }} <span class="text-gray-300 text-lg">/ {{ $stats['total_biens'] }}</span></h3>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4 overflow-hidden">
                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $stats['total_biens'] > 0 ? ($stats['biens_occupes']/$stats['total_biens'])*100 : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:scale-[1.02] transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-4 bg-red-50 rounded-2xl text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <span class="animate-pulse flex h-2 w-2 rounded-full bg-red-500"></span>
            </div>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Incidents Actifs</p>
            <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $stats['incidents_actifs'] }}</h3>
            <p class="text-[10px] text-red-500 font-bold mt-2 uppercase">Attention requise</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <div class="lg:col-span-8 bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-gray-900 uppercase tracking-tight text-lg">Recettes Récentes</h3>
                    <p class="text-xs text-gray-400 font-bold">Derniers flux financiers encaissés</p>
                </div>
                <a href="{{ route('paiements.index') }}" class="px-5 py-2 bg-gray-50 text-gray-900 rounded-xl text-[10px] font-black uppercase hover:bg-indigo-600 hover:text-white transition-all">Tout voir</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4 text-left">Locataire / Bien</th>
                            <th class="px-8 py-4 text-left">Montant</th>
                            <th class="px-8 py-4 text-left">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($derniersPaiements as $paiement)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 text-sm uppercase">{{ $paiement->locataire->prenoms }} {{ $paiement->locataire->nom }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $paiement->bien->titre }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-black text-gray-900">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} <small class="text-gray-400">CFA</small></span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full uppercase tracking-tighter">Confirmé</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-12 text-center text-gray-400 italic text-sm font-medium">Aucun flux récent à afficher.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gray-900 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-black text-xl uppercase tracking-tighter mb-6">Actions Rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('biens.index') }}" class="group flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-indigo-600 transition-all">
                            <span class="text-xs font-black uppercase tracking-widest">Nouveau Bien</span>
                            <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('contrats.index') }}" class="group flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-indigo-600 transition-all">
                            <span class="text-xs font-black uppercase tracking-widest">Nouveau Contrat</span>
                            <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('paiements.index') }}" class="group flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-emerald-600 transition-all">
                            <span class="text-xs font-black uppercase tracking-widest">Encaisser Loyer</span>
                            <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="bg-indigo-50 p-8 rounded-[2.5rem] border border-indigo-100">
                <h4 class="font-black text-indigo-900 uppercase text-xs tracking-widest mb-2">Besoin d'aide ?</h4>
                <p class="text-xs text-indigo-700/70 font-bold mb-6">Consultez la documentation technique ou contactez le support.</p>
                <button class="w-full py-4 bg-white text-indigo-600 rounded-2xl text-[10px] font-black uppercase shadow-sm hover:shadow-md transition-all">
                    Ouvrir le guide
                </button>
            </div>
        </div>
    </div>
</div>
@endsection