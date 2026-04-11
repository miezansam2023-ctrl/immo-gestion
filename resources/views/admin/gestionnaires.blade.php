@extends('layouts.app')
@section('title', 'Gestion des comptes')
@section('content')
<div class="min-h-screen bg-[#F0F2F7]">
<div class="max-w-7xl mx-auto px-4 py-10 space-y-8">

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-1">🛡️ Admin</p>
                <h1 class="text-3xl md:text-4xl font-black text-[#1E293B] uppercase tracking-tight">Gestion des comptes</h1>
                <p class="text-sm text-gray-500 mt-2">Visualisez et gérez tous les comptes (admins et gestionnaires). Sélectionnez un utilisateur pour actions rapides.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap px-5 py-3 bg-[#1E293B] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Retour au dashboard
            </a>
        </div>

        {{-- RECHERCHE --}}
        <form method="GET" class="relative max-w-xl">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, email..." class="w-full bg-white border border-gray-200 py-4 pl-12 pr-28 rounded-full shadow-sm font-black text-sm uppercase tracking-wider outline-none focus:ring-2 focus:ring-indigo-500 transition" />
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-search"></i></span>
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-900 text-white px-5 py-2 rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition">Rechercher</button>
        </form>

        {{-- TABLEAU --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-separate" style="border-spacing: 0;">
                        <thead class="bg-gray-50/80 border-b border-gray-200 sticky top-0">
                            <tr>
                                <th class="p-4 md:p-5 text-[10px] md:text-xs font-black uppercase text-gray-400 tracking-widest">Utilisateur</th>
                                <th class="p-4 md:p-5 text-[10px] md:text-xs font-black uppercase text-gray-400 tracking-widest">Rôle</th>
                                <th class="p-4 md:p-5 text-[10px] md:text-xs font-black uppercase text-gray-400 tracking-widest">Données</th>
                                <th class="p-4 md:p-5 text-[10px] md:text-xs font-black uppercase text-gray-400 tracking-widest">Statut</th>
                                <th class="p-4 md:p-5 text-[10px] md:text-xs font-black uppercase text-gray-400 tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($gestionnaires as $g)
                            <tr class="group hover:bg-indigo-50/20 transition-all">
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center
                                        justify-center text-indigo-600 font-black text-xs">
                                            {{ strtoupper(substr($g->prenoms, 0, 1) . substr($g->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-[#1E293B] text-sm uppercase">
                                                {{ $g->nom }} {{ $g->prenoms }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $g->email }}</p>
                                            <p class="text-[9px] text-gray-400">{{ $g->telephone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase
                                        {{ $g->role === 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                        <i class="fas {{ $g->role === 'admin' ? 'fa-user-shield' : 'fa-users' }} mr-1"></i>
                                        {{ ucfirst($g->role) }}
                                    </span>
                                </td>
                                <td class="p-6">
                                    <div class="flex gap-3">
                                        <span
                                            class="px-2 py-1 bg-indigo-50 text-indigo-600 text-[9px]
                                         font-black rounded-lg uppercase">
                                            {{ $g->biens_count }} biens
                                        </span>
                                        <span
                                            class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[9px]
                                         font-black rounded-lg uppercase">
                                            {{ $g->contrats_count }} contrats
                                        </span>
                                        <span
                                            class="px-2 py-1 bg-blue-50 text-blue-600 text-[9px]
                                         font-black rounded-lg uppercase">
                                            {{ $g->locataires_count }} locataires
                                        </span>
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-1 font-bold">
                                        Inscrit le {{ $g->created_at->format('d/m/Y') }}
                                    </p>
                                    @if($g->last_login)
                                        <p class="text-[9px] text-gray-500 mt-1">
                                            Dernière connexion : {{ $g->last_login->format('d/m/Y H:i') }}
                                        </p>
                                    @else
                                        <p class="text-[9px] text-gray-500 mt-1">Jamais connecté</p>
                                    @endif
                                </td>
                                <td class="p-6">
                                    <span
                                        class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase
                            {{ $g->actif ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                        {{ $g->actif ? '● Actif' : '● Inactif' }}
                                    </span>

                                    @if(!$g->actif && $g->deactivated_at)
                                        <p class="text-[9px] text-gray-500 mt-1">
                                            Désactivé le {{ $g->deactivated_at->format('d/m/Y H:i') }}
                                            @if($g->deactivatedBy)
                                                par {{ $g->deactivatedBy->nom }} {{ $g->deactivatedBy->prenoms }}
                                            @endif
                                        </p>
                                    @endif
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Voir --}}
                                        <a href="{{ route('admin.gestionnaires.show', $g) }}"
                                            class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-600
                                      hover:text-white transition-all">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        {{-- Actions disponibles seulement si ce n'est pas le premier admin --}}
                                        @if($g->id !== 1)
                                            {{-- Toggle actif --}}
                                            <form method="POST" action="{{ route('admin.gestionnaires.toggle', $g) }}" onsubmit="event.preventDefault(); confirmToggle('{{ $g->actif ? 'desactiver' : 'activer' }}', '{{ addslashes($g->nom . ' ' . $g->prenoms) }}', this);">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 bg-gray-50 text-gray-400 rounded-xl transition-all
                                        {{ $g->actif ? 'hover:bg-orange-500 hover:text-white' : 'hover:bg-emerald-500 hover:text-white' }}">
                                                    <i
                                                        class="fas {{ $g->actif ? 'fa-ban' : 'fa-check' }}
                                               text-xs"></i>
                                                </button>
                                            </form>
                                            {{-- Changer le rôle --}}
                                            <form method="POST" action="{{ route('admin.gestionnaires.changeRole', $g) }}"
                                                onsubmit="event.preventDefault(); const msg = '{{ $g->role === 'gestionnaire' ? 'Promouvoir ce gestionnaire en admin ?' : 'Rétrograder cet admin en gestionnaire ?' }}'; confirmDelete(msg).then(confirmed => { if(confirmed) this.submit(); })">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 bg-gray-50 text-gray-300 rounded-xl hover:bg-blue-600
                                                hover:text-white transition-all">
                                                    <i class="fas {{ $g->role === 'gestionnaire' ? 'fa-user-shield' : 'fa-user' }} text-xs"></i>
                                                </button>
                                            </form>
                                            {{-- Supprimer --}}
                                            <form method="POST" action="{{ route('admin.gestionnaires.destroy', $g) }}"
                                                onsubmit="event.preventDefault(); confirmDelete('Supprimer définitivement ce compte ?').then(confirmed => { if(confirmed) this.submit(); })">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-gray-50 text-gray-300 rounded-xl hover:bg-red-600
                                           hover:text-white transition-all">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Indicateur pour le premier admin --}}
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs font-bold uppercase">
                                                <i class="fas fa-shield-alt mr-1"></i> Protégé
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-20 text-center text-gray-300 font-black uppercase">
                                    Aucun compte trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="p-6 bg-gray-50/50">{{ $gestionnaires->links() }}</div>
            </div>

        </div>
    </div>
@endsection
