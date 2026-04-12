@extends('layouts.app')
@section('title', $user->nom . ' ' . $user->prenoms)
@section('content')
<div class="min-h-screen bg-[#F0F2F7]">
<div class="max-w-7xl mx-auto px-4 py-10 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-end justify-between">
        <div>
            <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-1">
                🛡️ Admin</p>
            <h1 class="text-4xl font-black text-[#1E293B] uppercase tracking-tighter">
                {{ $user->nom }} {{ $user->prenoms }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.utilisateurs') }}"
               class="px-5 py-3 bg-white border border-gray-200 text-[#1E293B] rounded-2xl
                      text-[10px] font-black uppercase tracking-widest hover:border-indigo-400
                      hover:text-indigo-600 transition-all">
                ← Retour
            </a>

            {{-- Actions disponibles seulement si ce n'est pas le premier admin --}}
            @if($user->id !== 1)
                {{-- Changer le rôle --}}
                <form method="POST" action="{{ route('admin.utilisateurs.changeRole', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-5 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-2xl
                               text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fas {{ $user->role === 'gestionnaire' ? 'fa-user-shield' : 'fa-users' }} mr-2"></i>
                        {{ $user->role === 'gestionnaire' ? 'Promouvoir Admin' : 'Rétrograder Gestionnaire' }}
                    </button>
                </form>
                {{-- Toggle actif --}}
                <form method="POST" action="{{ route('admin.utilisateurs.toggle', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-5 py-3 {{ $user->actif ? 'bg-orange-500 hover:bg-orange-600' : 'bg-emerald-500 hover:bg-emerald-600' }}
                               text-white rounded-2xl text-[10px] font-black uppercase tracking-widest
                               transition-all">
                        <i class="fas {{ $user->actif ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                        {{ $user->actif ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
                {{-- Supprimer --}}
                <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user) }}"
                      onsubmit="event.preventDefault(); confirmDelete('Supprimer définitivement ce compte ?').then(confirmed => { if(confirmed) this.submit(); })">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-5 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl
                               text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                </form>
            @else
                {{-- Indicateur pour le premier admin --}}
                <div class="px-5 py-3 bg-gray-100 text-gray-500 rounded-2xl text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-shield-alt mr-2"></i> Admin Principal - Protégé
                </div>
            @endif
        </div>
    </div>

    {{-- INFOS UTILISATEUR --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 rounded-3xl bg-indigo-50 flex items-center justify-center
                        text-indigo-600 font-black text-2xl">
                {{ strtoupper(substr($user->prenoms,0,1).substr($user->nom,0,1)) }}
            </div>
            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                        Informations personnelles</p>
                    <div class="space-y-1">
                        <p class="font-black text-[#1E293B] text-lg">{{ $user->nom }} {{ $user->prenoms }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="text-sm text-gray-600">{{ $user->telephone }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                        Statut du compte</p>
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black uppercase
                            {{ $user->actif ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                            <span class="w-2 h-2 rounded-full mr-2
                                {{ $user->actif ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $user->actif ? 'Actif' : 'Inactif' }}
                        </span>
                        <p class="text-xs text-gray-500 mt-2">
                            Inscrit le {{ $user->created_at->format('d/m/Y à H:i') }}
                        </p>

                        @if(!$user->actif && $user->deactivated_at)
                            <p class="text-xs text-red-500 mt-1">
                                Désactivé le {{ $user->deactivated_at->format('d/m/Y à H:i') }}
                                @if($user->deactivatedBy)
                                    par {{ $user->deactivatedBy->prenoms }} {{ $user->deactivatedBy->nom }} 
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                        Rôle</p>
                    <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-600
                                 rounded-xl text-xs font-black uppercase">
                        <i class="fas fa-user-shield mr-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                        Dernière activité</p>
                    <div class="space-y-1">
                        @if($user->last_login)
                            <p class="text-sm font-bold text-[#1E293B]">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                {{ $user->last_login->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $user->last_login->diffForHumans() }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400">
                                <i class="fas fa-clock mr-2"></i>
                                Jamais connecté
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIQUES --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center
                        text-indigo-600 mb-4">
                <i class="fas fa-building"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Biens gérés</p>
            <p class="text-4xl font-black text-[#1E293B]">
                {{ $stats['total_biens'] }}</p>
            <p class="text-[10px] text-emerald-500 font-bold mt-2">
                {{ $stats['biens_occupes'] }} occupés
            </p>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center
                        text-blue-600 mb-4">
                <i class="fas fa-file-contract"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Contrats</p>
            <p class="text-4xl font-black text-[#1E293B]">
                {{ $stats['total_contrats'] }}</p>
            <p class="text-[10px] text-emerald-500 font-bold mt-2">
                {{ $stats['contrats_actifs'] }} actifs
            </p>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center
                        text-purple-600 mb-4">
                <i class="fas fa-users"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Locataires</p>
            <p class="text-4xl font-black text-[#1E293B]">
                {{ $stats['total_locataires'] }}</p>
        </div>
        <div class="bg-[#1E293B] rounded-[2rem] p-6 shadow-xl">
            <div class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center
                        text-emerald-400 mb-4">
                <i class="fas fa-wallet"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Revenus générés</p>
            <p class="text-2xl font-black text-white">
                {{ number_format($stats['revenus_total'], 0, ',', ' ') }}
                <span class="text-sm text-gray-400">FCFA</span>
            </p>
        </div>
    </div>

    {{-- BIENS GÉRÉS --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100">
            <h3 class="text-xl font-black text-[#1E293B] uppercase tracking-tight">
                Biens gérés ({{ $user->biens->count() }})
            </h3>
        </div>
        @if($user->biens->count() > 0)
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($user->biens as $bien)
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="font-black text-[#1E293B] text-lg">{{ $bien->reference }}</h4>
                            <p class="text-sm text-gray-600">{{ $bien->type }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-xs font-bold uppercase
                            {{ $bien->statut === 'libre' ? 'bg-emerald-100 text-emerald-600' :
                               ($bien->statut === 'occupe' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600') }}">
                            {{ $bien->statut }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-600">Localisation : {{ $bien->commune }}, {{ $bien->quartier }}</p>
                        <p class="text-gray-600">Loyer : {{ number_format($bien->prix_loyer, 0, ',', ' ') }} FCFA/mois</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="p-20 text-center">
            <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-400 font-black uppercase text-sm">Aucun bien géré</p>
        </div>
        @endif
    </div>

    {{-- CONTRATS ACTIFS --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100">
            <h3 class="text-xl font-black text-[#1E293B] uppercase tracking-tight">
                Contrats actifs ({{ $user->contrats->where('statut', 'actif')->count() }})
            </h3>
        </div>
        @if($user->contrats->where('statut', 'actif')->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Locataire</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Bien</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Période</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Loyer</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($user->contrats->where('statut', 'actif') as $contrat)
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center
                                            text-indigo-600 font-black text-xs">
                                    {{ strtoupper(substr($contrat->locataire->prenoms,0,1).substr($contrat->locataire->nom,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-[#1E293B] text-sm">
                                        {{ $contrat->locataire->nom }} {{ $contrat->locataire->prenoms }}</p>
                                    <p class="text-xs text-gray-500">{{ $contrat->locataire->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <p class="font-bold text-[#1E293B]">{{ $contrat->bien->reference }}</p>
                            <p class="text-xs text-gray-500">{{ $contrat->bien->adresse }}</p>
                        </td>
                        <td class="p-6">
                            <p class="text-sm font-bold text-[#1E293B]">
                                {{ $contrat->date_debut->format('d/m/Y') }} - {{ $contrat->date_fin->format('d/m/Y') }}
                            </p>
                        </td>
                        <td class="p-6">
                            <p class="font-bold text-[#1E293B]">
                                {{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }} FCFA
                            </p>
                        </td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-xl text-xs font-black uppercase
                                {{ $contrat->statut === 'actif' ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $contrat->statut }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-20 text-center">
            <i class="fas fa-file-contract text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-400 font-black uppercase text-sm">Aucun contrat actif</p>
        </div>
        @endif
    </div>

    {{-- LOCATAIRES --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100">
            <h3 class="text-xl font-black text-[#1E293B] uppercase tracking-tight">
                Locataires ({{ $user->locataires->count() }})
            </h3>
        </div>
        @if($user->locataires->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Locataire</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Contact</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Contrats actifs</th>
                        <th class="p-6 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            Inscrit le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($user->locataires as $locataire)
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center
                                            text-purple-600 font-black text-xs">
                                    {{ strtoupper(substr($locataire->prenoms,0,1).substr($locataire->nom,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-[#1E293B] text-sm">
                                        {{ $locataire->nom }} {{ $locataire->prenoms }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <p class="text-sm text-gray-600">{{ $locataire->email }}</p>
                            <p class="text-sm text-gray-600">{{ $locataire->telephone }}</p>
                        </td>
                        <td class="p-6">
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg">
                                {{ $locataire->contrats()->where('statut', 'actif')->count() }} actif(s)
                            </span>
                        </td>
                        <td class="p-6">
                            <p class="text-sm font-bold text-[#1E293B]">
                                {{ $locataire->created_at->format('d/m/Y') }}
                            </p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-20 text-center">
            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-400 font-black uppercase text-sm">Aucun locataire</p>
        </div>
        @endif
    </div>

</div></div>
@endsection
