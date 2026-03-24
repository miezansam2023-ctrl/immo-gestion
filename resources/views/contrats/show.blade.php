@extends('layouts.app')
@section('title', '' . $contrat->numero)
@section('content')
    <div class="p-4 md:p-8 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- Header avec Statut --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('contrats.index') }}"
                            class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:text-indigo-600 shadow-sm transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-black text-[#1E293B] uppercase tracking-tight">Contrat
                            #{{ $contrat->reference ?? 'BAIL-2026' }}</h1>
                    </div>
                    <p class="text-gray-400 font-bold text-sm ml-12 italic">Créé le
                        {{ $contrat->created_at->format('d/m/Y') }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <span
                        class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm 
                    {{ $contrat->statut == 'actif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        ● Contrat {{ $contrat->statut }}
                    </span>
                    <!-- <button class="bg-[#1E293B] text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-900/10">
                                    Générer PDF
                                </button> -->
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- COLONNE GAUCHE : LES ACTEURS --}}
                <div class="space-y-8">
                    {{-- LE LOCATAIRE --}}
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Locataire</h3>
                        <div class="flex items-center gap-5 mb-8">
                            {{-- Photo du Locataire --}}
                            <div
                                class="h-20 w-20 rounded-2xl bg-indigo-50 overflow-hidden shadow-inner border border-gray-100">
                                @if ($contrat->locataire->photo)
                                    @php
                                        // On nettoie le chemin au cas où "public/" est resté
                                        $cheminPhoto = str_replace('public/', '', $contrat->locataire->photo);
                                    @endphp
                                    <img src="{{ asset('storage/' . $cheminPhoto) }}" class="w-full h-full object-cover"
                                        alt="{{ $contrat->locataire->nom }}"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    {{-- Fallback si l'image est introuvable sur le serveur --}}
                                    <div
                                        class="hidden w-full h-full items-center justify-center text-indigo-600 font-black text-2xl bg-indigo-50">
                                        {{ substr($contrat->locataire->nom, 0, 1) }}
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-indigo-600 font-black text-2xl">
                                        {{ substr($contrat->locataire->nom, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xl font-black text-[#1E293B] uppercase italic">{{ $contrat->locataire->nom }}
                                </p>
                                <p class="text-xs font-bold text-gray-400">{{ $contrat->locataire->telephone }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <span class="text-xs font-bold text-gray-400 uppercase">Email</span>
                                <span
                                    class="text-xs font-black text-[#1E293B]">{{ $contrat->locataire->email ?? 'Non renseigné' }}</span>
                            </div>

                            <div class="flex justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <span class="text-xs font-bold text-gray-400 uppercase">Profession</span>
                                <span
                                    class="text-xs font-black text-[#1E293B]">{{ $contrat->locataire->profession ?? 'Non renseigné' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- LE BIEN CONCERNÉ --}}
                    <div
                        class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm group cursor-pointer hover:border-indigo-200 transition-all">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Bien Immobilier
                        </h3>
                        <div class="aspect-video rounded-3xl overflow-hidden mb-6">
                            @php $photoBien = is_array($contrat->bien->photos) ? $contrat->bien->photos[0] : null; @endphp
                            <img src="{{ asset('storage/' . $photoBien) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <p class="text-lg font-black text-[#1E293B] uppercase italic">{{ $contrat->bien->titre }}</p>
                        <p class="text-xs font-bold text-gray-400 mb-6"> {{ $contrat->bien->quartier }},
                            {{ $contrat->bien->adresse }}</p>
                        <a href="{{ route('biens.show', $contrat->bien) }}"
                            class="inline-flex items-center text-xs font-black text-indigo-600 uppercase tracking-widest">
                            Fiche du bien
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- COLONNE DROITE : CONDITIONS & PAIEMENTS --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- INFOS FINANCIÈRES --}}
                    <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-10">Conditions du Bail
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="relative">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Loyer Mensuel</p>
                                <p class="text-3xl font-black text-indigo-600 italic">
                                    {{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }} <span
                                        class="text-xs uppercase">CFA</span></p>
                                <div class="absolute -left-4 top-0 w-1 h-12 bg-indigo-500 rounded-full"></div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Caution Versée</p>
                                <p class="text-3xl font-black text-[#1E293B] italic">
                                    {{ number_format($contrat->caution, 0, ',', ' ') }} <span
                                        class="text-xs uppercase font-bold">CFA</span></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Durée</p>
                                {{-- On affiche la durée venant du contrat, ou 0 si elle est vide --}}
                                <p class="text-3xl font-black text-[#1E293B] italic">
                                    {{ $contrat->duree_mois ?? '0' }}
                                    <span class="text-xs uppercase font-bold text-gray-400">Mois</span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-12 p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Période d'occupation</p>
                                    <p class="font-black text-[#1E293B]">Du
                                        {{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }} au
                                        {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}</p>
                                </div>

                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">

                                    @php
                                        $dateDebut = \Carbon\Carbon::parse($contrat->date_debut);
                                        $dateFin = \Carbon\Carbon::parse($contrat->date_fin);
                                        $totalJours = $dateDebut->diffInDays($dateFin);
                                        $joursEcoules = $dateDebut->diffInDays(now());
                                        $pourcentage =
                                            $totalJours > 0 ? min(100, max(0, ($joursEcoules / $totalJours) * 100)) : 0;
                                    @endphp
                                    pourcentage d'occupation : {{ number_format($pourcentage, 0) }}%
                                </span>

                            </div>

                            {{-- Barre de progression --}}
                            @php
                                $dateDebut = \Carbon\Carbon::parse($contrat->date_debut);
                                $dateFin = \Carbon\Carbon::parse($contrat->date_fin);
                                $totalJours = $dateDebut->diffInDays($dateFin);
                                $joursEcoules = $dateDebut->diffInDays(now());
                                $pourcentage =
                                    $totalJours > 0 ? min(100, max(0, ($joursEcoules / $totalJours) * 100)) : 0;
                            @endphp

                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 rounded-full shadow-lg"
                                    style="width: {{ $pourcentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- HISTORIQUE DES LOYERS --}}
                    <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-10">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Historique des
                                Paiements</h3>

                        </div>

                        <div class="overflow-hidden">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                        <th class="pb-6">Mois / Période</th>
                                        <th class="pb-6">Date</th>
                                        <th class="pb-6">Montant</th>
                                        <th class="pb-6 text-right">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($contrat->paiements->sortByDesc('date_paiement') as $paiement)
                                        <tr class="group hover:bg-gray-50 transition-colors">
                                            <td class="py-6 font-black text-[#1E293B] uppercase italic">
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
                                                    
                                                    [$moisEn, $annee] = explode(' ', $paiement->mois_annee);
                                                    $moisNom = $moisFr[$moisEn] ?? $moisEn;
                                                @endphp
                                                {{ $moisNom }} {{ $annee }}
                                            </td>
                                            <td class="py-6 text-sm text-gray-500 font-bold">
                                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-6 font-black text-[#1E293B]">
                                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} CFA
                                                @if ($paiement->reste_a_payer > 0)
                                                    <div class="text-[9px] text-red-500 font-black uppercase mt-1">
                                                        Reliquat:
                                                        {{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-6 text-right">
                                                @php
                                                    $statusMap = [
                                                        'paye' => [
                                                            'label' => 'Payé',
                                                            'color' => 'bg-green-100 text-green-600',
                                                        ],
                                                        'partiel' => [
                                                            'label' => 'Partiel',
                                                            'color' => 'bg-orange-100 text-orange-600',
                                                        ],
                                                        'en_attente' => [
                                                            'label' => 'Attente',
                                                            'color' => 'bg-yellow-100 text-yellow-600',
                                                        ],
                                                        'retard' => [
                                                            'label' => 'Retard',
                                                            'color' => 'bg-red-100 text-red-600',
                                                        ],
                                                    ];
                                                    $s = $statusMap[$paiement->statut] ?? [
                                                        'label' => $paiement->statut,
                                                        'color' => 'bg-gray-100 text-gray-500',
                                                    ];
                                                @endphp
                                                <span
                                                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $s['color'] }}">
                                                    {{ $s['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center text-gray-400 font-bold text-sm">
                                                <div class="flex flex-col items-center gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-10 w-10 text-gray-200" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Aucun paiement enregistré pour ce contrat.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
