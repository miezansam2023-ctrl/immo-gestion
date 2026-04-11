@extends('layouts.app')
@section('title', $paiement->numero)
@section('content')
    <div class="p-6 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-4xl mx-auto">

            {{-- Barre d'actions --}}
            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('paiements.index') }}"
                    class="text-gray-500 hover:text-gray-700 font-bold flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    RETOUR
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('paiements.quittance', $paiement) }}"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        IMPRIMER LA QUITTANCE
                    </a>
                </div>
            </div>

            {{-- Carte principale --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Header --}}
                <div
                    class="bg-[#1E293B] p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="text-indigo-300 text-[10px] font-black uppercase tracking-widest mb-1">Référence
                            Transaction</p>
                        <h2 class="text-2xl font-black">{{ $paiement->numero }}</h2>
                        @if ($paiement->type_selection === 'multiple')
                            <span
                                class="mt-2 inline-block bg-indigo-500/30 text-indigo-200 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                                <i class="fas fa-layer-group mr-1"></i> Paiement Multi-Mois
                            </span>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @php
                            $statusMap = [
                                'paye' => [
                                    'label' => 'Payé',
                                    'color' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                ],
                                'partiel' => [
                                    'label' => 'Partiel',
                                    'color' => 'bg-orange-500/20 text-orange-300 border-orange-500/30',
                                ],
                                'en_attente' => [
                                    'label' => 'En attente',
                                    'color' => 'bg-gray-500/20 text-gray-300 border-gray-500/30',
                                ],
                                'retard' => [
                                    'label' => 'En retard',
                                    'color' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                ],
                            ];
                            $s = $statusMap[$paiement->statut] ?? [
                                'label' => $paiement->statut,
                                'color' => 'bg-white/10 text-white border-white/20',
                            ];
                        @endphp
                        <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase border {{ $s['color'] }}">
                            {{ $s['label'] }}
                        </span>
                        @if ($paiement->est_en_retard)
                            <span
                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-red-500/20 text-red-300 border border-red-500/30">
                                <i class="fas fa-clock mr-1"></i> Paiement tardif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- Infos Locataire & Bien --}}
                    <div class="space-y-5">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Locataire</p>
                            <p class="font-bold text-lg text-gray-800 uppercase">{{ $paiement->locataire->nom }}
                                {{ $paiement->locataire->prenoms }}</p>
                            <p class="text-sm text-gray-500">{{ $paiement->locataire->telephone }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Bien Immobilier
                            </p>
                            <p class="font-bold text-gray-800">{{ $paiement->bien->titre }}</p>
                            {{-- <p class="text-sm text-gray-500">{{ $paiement->bien->adresse }}</p> --}}
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Contrat</p>
                            <a href="{{ route('contrats.show', $paiement->contrat) }}"
                                class="font-bold text-indigo-600 hover:underline">
                                {{ $paiement->contrat->numero }}
                            </a>
                            <p class="text-[10px] text-gray-400 mt-1">
                                Jour de paiement défini : <strong>le {{ $paiement->contrat->jour_paiement ?? 5 }} du
                                    mois</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Infos financières --}}
                    <div class="bg-gray-50 rounded-2xl p-6 space-y-4">

                        {{-- Période --}}
                        <div class="border-b border-gray-200 pb-3">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Période concernée
                            </p>
                            @if ($paiement->type_selection === 'multiple' && !empty($paiement->mois_concernes))
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($paiement->mois_concernes as $moisRaw)
                                        @php
                                            $moisFr = [
                                                'January' => 'Janvier',
                                                'February' => 'Février',
                                                'March' => 'Mars',
                                                'April' => 'Avril',
                                                'May' => 'Mai',
                                                'June' => 'Juin',
                                                'July' => 'Juillet',
                                                'August' => 'Août',
                                                'September' => 'Septembre',
                                                'October' => 'Octobre',
                                                'November' => 'Novembre',
                                                'December' => 'Décembre',
                                            ];
                                            $d = \Carbon\Carbon::parse($moisRaw . '-01');
                                            $moisNom =
                                                ($moisFr[$d->format('F')] ?? $d->format('F')) . ' ' . $d->format('Y');
                                        @endphp
                                        <span
                                            class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase rounded-lg">
                                            {{ $moisNom }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="font-bold text-gray-800">{{ $paiement->mois_annee }}</span>
                            @endif
                        </div>

                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="text-gray-500 font-medium">Montant Dû</span>
                            <span class="font-bold text-gray-800">
                                {{ number_format($paiement->montant_du, 0, ',', ' ') }} FCFA
                            </span>
                        </div>

                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="text-indigo-600 font-medium">Montant Payé</span>
                            <span class="font-black text-indigo-600 text-lg">
                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                            </span>
                        </div>

                        @if ($paiement->reste_a_payer > 0)
                            <div class="flex justify-between text-red-500 pt-2">
                                <span class="font-bold uppercase text-[10px]">Reste à Payer</span>
                                <span class="font-black">{{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                                    FCFA</span>
                            </div>
                        @endif

                        {{-- Retard info --}}
                        @if ($paiement->est_en_retard)
                            @php
                                $jourPaiement = $paiement->contrat->jour_paiement ?? 5;
                                $dateLimite = \Carbon\Carbon::parse($paiement->periode_debut)->setDay(
                                    min($jourPaiement, \Carbon\Carbon::parse($paiement->periode_debut)->daysInMonth),
                                );
                                $joursRetard = $dateLimite->diffInDays($paiement->date_paiement);
                            @endphp
                            <div class="p-3 bg-red-50 rounded-xl border border-red-100">
                                <p class="text-[10px] font-black text-red-600 uppercase">
                                    <i class="fas fa-clock mr-1"></i>
                                    Payé avec {{ $joursRetard }} jour(s) de retard
                                    (échéance : le {{ $dateLimite->format('d/m/Y') }})
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-8 border-t border-gray-50 bg-gray-50/30 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Mode de règlement</p>
                        <p class="font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $paiement->mode_paiement) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Date d'encaissement
                        </p>
                        <p class="font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">enregistré
                            par</p>
                        <p class="font-bold text-gray-700">{{ $paiement->gestionnaire->nom ?? 'Système' }}
                            {{ $paiement->gestionnaire->prenoms ?? 'Système' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">N°
                            quittance : </p>
                        <p class="font-bold text-gray-700">{{ $paiement->numero_quittance }}</p>
                    </div>
                    @if ($paiement->reference_paiement)
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Référence</p>
                            <p class="font-bold text-gray-700 font-mono text-sm">{{ $paiement->reference_paiement }}</p>
                        </div>
                    @endif
                    @if ($paiement->notes)
                        <div class="col-span-2 md:col-span-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Notes</p>
                            <p class="text-sm text-gray-600 italic">{{ $paiement->notes }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
