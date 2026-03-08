@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#F8FAFC] min-h-screen" x-data="{ 
    contratId: '', 
    loyer: 0,
    updateLoyer(e) {
        const option = e.target.selectedOptions[0];
        this.loyer = option.dataset.loyer || 0;
    }
}">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-[#1E293B] uppercase tracking-tight">Encaisser un Loyer</h1>
                <p class="text-gray-500 text-sm font-medium">Enregistrement d'un nouveau flux financier</p>
            </div>
        </div>

        <form action="{{ route('paiements.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Sélection du Contrat --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Contrat de Bail *</label>
                            <select name="contrat_id" 
                                    @change="updateLoyer"
                                    class="w-full bg-gray-50 border-none rounded-2xl py-4 font-bold px-4 shadow-inner focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">Sélectionner un contrat actif...</option>
                                @foreach($contrats as $contrat)
                                    <option value="{{ $contrat->id }}" data-loyer="{{ $contrat->loyer_mensuel }}">
                                        {{ $contrat->locataire->nom }} {{ $contrat->locataire->prenoms }} - {{ $contrat->bien->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Période --}}
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Mois concerné *</label>
                                <input type="month" name="mois_annee_raw" required
                                       class="w-full bg-gray-50 border-none rounded-2xl py-4 font-bold px-4 shadow-inner">
                            </div>
                            {{-- Date de paiement --}}
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Date d'encaissement *</label>
                                <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}"
                                       class="w-full bg-gray-50 border-none rounded-2xl py-4 font-bold px-4 shadow-inner">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Mode de règlement *</label>
                            <select name="mode_paiement" class="w-full bg-gray-50 border-none rounded-2xl py-4 font-bold px-4 shadow-inner">
                                <option value="especes">Espèces</option>
                                <option value="virement">Virement Bancaire</option>
                                <option value="cheque">Chèque</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-[#1E293B] p-8 rounded-3xl shadow-2xl text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Montant du Loyer</p>
                        <div class="flex items-baseline gap-2 mb-8">
                            <span class="text-4xl font-black" x-text="new Intl.NumberFormat('fr-FR').format(loyer)">0</span>
                            <span class="text-gray-400 font-bold">FCFA</span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-indigo-300 uppercase mb-2">Montant perçu *</label>
                                <input type="number" name="montant_paye" :max="loyer"
                                       class="w-full bg-white/10 border-white/20 rounded-xl py-3 px-4 font-black text-2xl focus:bg-white/20 transition-all outline-none" 
                                       placeholder="0">
                            </div>

                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-500/30 transition-all uppercase tracking-widest text-sm">
                                Valider l'encaissement
                            </button>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl"></div>
                </div>
                
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Notes / Référence</label>
                    <textarea name="notes" rows="3" class="w-full bg-gray-50 border-none rounded-xl p-3 text-sm font-medium" placeholder="Ex: Chèque N°12345..."></textarea>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection