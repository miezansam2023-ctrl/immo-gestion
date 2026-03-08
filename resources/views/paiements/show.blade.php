@extends('layouts.app')
@section('title',$paiement->numero)
@section('content')
<div class="p-6 bg-[#F8FAFC] min-h-screen">
    <div class="max-w-4xl mx-auto">
        
        {{-- Barre d'actions --}}
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('paiements.index') }}" class="text-gray-500 hover:text-gray-700 font-bold flex items-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                RETOUR
            </a>
            <div class="flex gap-3">
                <a href="{{ route('paiements.quittance', $paiement) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    IMPRIMER LA QUITTANCE
                </a>
            </div>
        </div>

        {{-- Carte Détails --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-[#1E293B] p-8 text-white flex justify-between items-center">
                <div>
                    <p class="text-indigo-300 text-[10px] font-black uppercase tracking-widest mb-1">Référence Transaction</p>
                    <h2 class="text-2xl font-black">{{ $paiement->numero }}</h2>
                </div>
                <div class="text-right">
                    <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase bg-white/10 border border-white/20">
                        Statut : {{ str_replace('_', ' ', $paiement->statut) }}
                    </span>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12">
                {{-- Infos Locataire & Bien --}}
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Locataire</p>
                        <p class="font-bold text-lg text-gray-800">{{ $paiement->locataire->nom }} {{ $paiement->locataire->prenoms }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Bien Immobilier</p>
                        <p class="font-bold text-gray-800">{{ $paiement->bien->titre }}</p>
                        <p class="text-sm text-gray-500">{{ $paiement->bien->adresse }}</p>
                    </div>
                </div>

                {{-- Infos Financières --}}
                <div class="bg-gray-50 rounded-2xl p-6 space-y-4">
                    <div class="flex justify-between border-b border-gray-200 pb-3">
                        <span class="text-gray-500 font-medium">Période</span>
                        <span class="font-bold text-gray-800">{{ $paiement->mois_annee }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-3">
                        <span class="text-gray-500 font-medium">Montant Dû</span>
                        <span class="font-bold text-gray-800">{{ number_format($paiement->montant_du, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-3">
                        <span class="text-gray-500 font-medium text-indigo-600">Montant Payé</span>
                        <span class="font-black text-indigo-600 text-lg">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($paiement->reste_a_payer > 0)
                    <div class="flex justify-between text-red-500 pt-2">
                        <span class="font-bold uppercase text-[10px]">Reste à Payer</span>
                        <span class="font-black">{{ number_format($paiement->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Footer détails --}}
            <div class="p-8 border-t border-gray-50 bg-gray-50/30 grid grid-cols-3 gap-4">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Mode de règlement</p>
                    <p class="font-bold text-gray-700 capitalize">{{ $paiement->mode_paiement }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Date du paiement</p>
                    <p class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Encaissé par</p>
                    <p class="font-bold text-gray-700">{{ $paiement->gestionnaire->name ?? 'Système' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection