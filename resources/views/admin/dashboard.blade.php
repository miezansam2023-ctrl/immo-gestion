@extends('layouts.app')
@section('title', 'Administration')
@section('content')
<div class="min-h-screen bg-[#F0F2F7]">
<div class="max-w-7xl mx-auto px-4 py-10 space-y-8">
 
    {{-- HEADER --}}
    <div class="flex items-end justify-between">
        <div>
            <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-1">
                🛡️ ESPACE ADMINISTRATEUR
            </p>
            <h1 class="text-4xl font-black text-[#1E293B] uppercase tracking-tighter">
                Dashboard Admin
            </h1>
        </div>
        <a href="{{ route('admin.utilisateurs') }}"
           class="px-6 py-3 bg-[#1E293B] text-white rounded-2xl text-[10px] font-black
                  uppercase tracking-widest hover:bg-indigo-600 transition-all">
            <i class="fas fa-users mr-2"></i> Gérer les comptes utilisateurs
        </a>
    </div>
 
    {{-- KPI CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center
                        text-indigo-600 mb-4">
                <i class="fas fa-users"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Comptes utilisateurs</p>
            <p class="text-4xl font-black text-[#1E293B]">
                {{ $stats['total_gestionnaires'] }}</p>
            <p class="text-[10px] text-emerald-500 font-bold mt-2">
                {{ $stats['gestionnaires_actifs'] }} actifs ·
                {{ $stats['gestionnaires_inactifs'] }} inactifs
            </p>
        </div>
        <div class="bg-[#1E293B] rounded-[2rem] p-6 shadow-xl">
            <div class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center
                        text-emerald-400 mb-4">
                <i class="fas fa-wallet"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Recettes plateforme</p>
            <p class="text-2xl font-black text-white">
                {{ number_format($stats['total_paiements'], 0, ',', ' ') }}
                <span class="text-sm text-gray-400">FCFA</span>
            </p>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center
                        text-blue-600 mb-4">
                <i class="fas fa-building"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Biens enregistrés</p>
            <p class="text-4xl font-black text-[#1E293B]">{{ $stats['total_biens'] }}</p>
        </div>
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 rounded-2xl flex items-center justify-center
                        text-emerald-600 mb-4">
                <i class="fas fa-file-contract"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Contrats actifs</p>
            <p class="text-4xl font-black text-[#1E293B]">{{ $stats['contrats_actifs'] }}</p>
        </div>
    </div>
 
    {{-- DERNIERS GESTIONNAIRES --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-black text-[#1E293B] uppercase tracking-tight">
                Derniers comptes créés</h3>
            <a href="{{ route('admin.utilisateurs') }}"
               class="text-[10px] font-black text-indigo-600 uppercase tracking-widest
                      hover:text-indigo-800">Tout voir →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($derniersUtilisateurs as $g)
            <div class="px-8 py-5 flex items-center justify-between hover:bg-gray-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center
                                text-indigo-600 font-black text-xs">
                        {{ strtoupper(substr($g->prenoms,0,1).substr($g->nom,0,1)) }}
                    </div>
                    <div>
                        <p class="font-black text-[#1E293B] text-sm uppercase">
                            {{ $g->nom }} {{ $g->prenoms }}</p>
                        <p class="text-[10px] text-gray-400">{{ $g->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-xl text-[9px] font-black uppercase
                        {{ $g->actif ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                        {{ $g->actif ? 'Actif' : 'Inactif' }}
                    </span>
                    <a href="{{ route('admin.utilisateurs.show', $g) }}"
                       class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-600
                              hover:text-white transition-all">
                        <i class="fas fa-eye text-xs"></i>
                    </a>
                </div>
            </div>
            @empty
            <p class="px-8 py-10 text-center text-gray-400 italic">Aucun utilisateur.</p>
            @endforelse
        </div>
    </div>
 
</div></div>
@endsection

