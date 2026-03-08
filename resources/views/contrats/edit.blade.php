@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12 space-y-8">

    {{-- Entête avec bouton retour --}}
    <div class="flex items-center justify-between px-4">
        <a href="{{ route('contrats.index') }}" class="flex items-center text-gray-400 hover:text-indigo-600 transition font-black text-[10px] uppercase tracking-[0.2em] group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Retour au registre
        </a>
        <div class="flex items-center space-x-4">
            <span class="px-5 py-2 rounded-2xl bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-100">
                Mode Édition
            </span>
            <span class="px-5 py-2 rounded-2xl bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest shadow-lg">
                Dossier : {{ $contrat->numero }}
            </span>
        </div>
    </div>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-[2rem] shadow-sm flex justify-between animate-in fade-in slide-in-from-top-4">
        <div>
            <h3 class="text-xs font-black text-red-800 uppercase italic mb-2">Erreurs de validation :</h3>
            <ul class="text-[10px] text-red-700 list-disc list-inside font-bold space-y-1">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
    </div>
    @endif

    <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100 relative overflow-hidden">
        {{-- Décoration subtile --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-50/50 blur-[80px] rounded-full -mr-32 -mt-32"></div>

        <div class="relative z-10 mb-10 border-b pb-8 flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Mise à jour du Bail</h1>
                <p class="text-amber-500 font-bold italic text-[10px] tracking-[0.2em] uppercase mt-1">Modification des clauses contractuelles et financières</p>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Dernière modification</p>
                <p class="text-xs font-mono font-bold text-gray-600">{{ $contrat->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <form action="{{ route('contrats.update', $contrat->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative z-10">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-10">
                {{-- SECTION 1 : LES PARTIES (VERROUILLÉES) --}}
                <div class="space-y-6">
                    <h3 class="text-[11px] font-black text-indigo-500 uppercase tracking-[0.2em] flex items-center">
                        <span class="bg-indigo-500 w-2 h-2 rounded-full mr-2"></span> Identification du contrat
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Bien Immobilier (Non modifiable)</label>
                            <div class="w-full bg-gray-50 border-2 border-transparent rounded-2xl py-4 px-5 font-bold text-gray-400 flex items-center">
                                <i class="fas fa-lock mr-3 text-gray-300"></i> {{ $contrat->bien->reference }} - {{ $contrat->bien->titre }}
                            </div>
                            <input type="hidden" name="bien_id" value="{{ $contrat->bien_id }}">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Locataire (Non modifiable)</label>
                            <div class="w-full bg-gray-50 border-2 border-transparent rounded-2xl py-4 px-5 font-bold text-gray-400 flex items-center">
                                <i class="fas fa-user-lock mr-3 text-gray-300"></i> {{ $contrat->locataire->nom }} {{ $contrat->locataire->prenoms }}
                            </div>
                            <input type="hidden" name="locataire_id" value="{{ $contrat->locataire_id }}">
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 : TEMPORALITÉ --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Date Début *</label>
                        <input type="date" name="date_debut" value="{{ old('date_debut', $contrat->date_debut->format('Y-m-d')) }}" class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white rounded-2xl py-4 px-5 font-bold text-gray-700 transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Durée (Mois) *</label>
                        <input type="number" name="duree_mois" value="{{ old('duree_mois', $contrat->duree_mois) }}" class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white rounded-2xl py-4 px-5 font-bold text-gray-700 transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Mode de règlement *</label>
                        <select name="mode_paiement" class="w-full bg-white border-none rounded-xl py-3 font-bold px-4 shadow-sm">
                            <option value="Espèces" {{ old('mode_paiement', $contrat->mode_paiement) == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                            <option value="virement" {{ old('mode_paiement', $contrat->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                            <option value="Chèque" {{ old('mode_paiement', $contrat->mode_paiement) == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                        </select>
                    </div>
                </div>

                {{-- SECTION 3 : ÉTAT & STATUT --}}
                <div class="bg-indigo-50/30 p-8 rounded-[2rem] space-y-6 border border-indigo-100/50">
                    <div class="flex justify-between items-center">
                        <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest flex items-center">
                            <i class="fas fa-clipboard-check mr-2 text-indigo-500"></i> Suivi du dossier
                        </h3>
                        {{-- STATUT DU CONTRAT --}}
                        <div class="flex space-x-2">
                            <label class="relative flex items-center cursor-pointer">
                                <select name="statut" class="appearance-none bg-white border-none rounded-xl py-2 px-8 font-black text-[10px] uppercase tracking-widest text-indigo-600 shadow-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="actif" {{ $contrat->statut == 'actif' ? 'selected' : '' }}>ACTIF</option>
                                    <option value="suspendu" {{ $contrat->statut == 'suspendu' ? 'selected' : '' }}>SUSPENDU</option>
                                    <option value="resilie" {{ $contrat->statut == 'resilie' ? 'selected' : '' }}>RÉSILIÉ</option>
                                </select>
                                <div class="absolute right-3 pointer-events-none text-[8px] text-indigo-400">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Date d'état des lieux</label>
                            <input type="date" name="date_etat_lieux_entree"
                                value="{{ old('date_etat_lieux_entree', $contrat->date_etat_lieux_entree ? \Carbon\Carbon::parse($contrat->date_etat_lieux_entree)->format('Y-m-d') : '') }}"
                                class="w-full bg-white border-none rounded-xl py-3 font-bold px-4 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Jour de paiement (1-31)</label>
                            <input type="number" name="jour_paiement" value="{{ old('jour_paiement', $contrat->jour_paiement) }}" class="w-full bg-white border-none rounded-xl py-3 px-4 font-bold shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Observations État des lieux</label>
                        <textarea name="etat_des_lieux_entree" rows="2" class="w-full bg-white border-none rounded-xl p-4 font-medium text-sm shadow-sm" placeholder="Observations sur l'état général du bien lors de l'édition...">{{ old('etat_des_lieux_entree', $contrat->etat_des_lieux_entree) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Notes administratives (Interne)</label>
                    <textarea name="notes" rows="3" class="w-full bg-gray-50 border-none rounded-[1.5rem] p-6 font-medium text-sm" placeholder="Ajoutez ici les modifications apportées ou notes spécifiques...">{{ old('notes', $contrat->notes) }}</textarea>
                </div>
            </div>

            {{-- COLONNE DROITE : FINANCES --}}
            <div class="bg-gray-900 rounded-[3rem] p-8 text-white flex flex-col shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 blur-[60px] rounded-full"></div>

                <div class="relative z-10 space-y-8">
                    <div class="border-b border-white/10 pb-6">
                        <label class="block text-[10px] font-black uppercase opacity-40 mb-3 tracking-[0.2em]">Loyer Mensuel HT</label>
                        <div class="flex items-baseline space-x-2">
                            <input type="number" name="loyer_mensuel" value="{{ old('loyer_mensuel', $contrat->loyer_mensuel) }}" class="w-full bg-transparent border-none p-0 text-4xl font-black text-amber-400 focus:ring-0" required>
                            <span class="text-sm font-bold opacity-30">FCFA</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 italic">
                        <div>
                            <label class="block text-[9px] font-black uppercase opacity-40 mb-1">Dépôt de Garantie (Caution)</label>
                            <input type="number" name="caution" value="{{ old('caution', $contrat->caution) }}" class="w-full bg-white/5 border-none rounded-xl py-3 px-4 text-xl font-bold" required>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase opacity-40 mb-1">Frais d'agence / Dossier</label>
                            <input type="number" step="0.01" name="frais_agence"
                                value="{{ old('frais_agence', $contrat->frais_agence) }}"
                                class="w-full bg-white/5 border-none rounded-xl py-3 px-4 text-xl font-bold">
                        </div>
                    </div>

                    <div class="pt-6 space-y-4">
                        <label class="flex items-center space-x-3 cursor-pointer group p-3 rounded-2xl hover:bg-white/5 transition">
                            <input type="checkbox" name="renouvellement_automatique" value="1" {{ $contrat->renouvellement_automatique ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-white/10 text-amber-500 focus:ring-0">
                            <span class="text-[11px] font-bold uppercase tracking-tight">Tacite Reconduction</span>
                        </label>
                        <!-- <label class="flex items-center space-x-3 cursor-pointer group p-3 rounded-2xl hover:bg-white/5 transition">
                            <input type="checkbox" name="animaux_autorises" value="1" {{ $contrat->animaux_autorises ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-white/10 text-amber-500 focus:ring-0">
                            <span class="text-[11px] font-bold uppercase tracking-tight">Animaux Autorisés</span>
                        </label> -->
                    </div>
                </div>

                <div class="mt-auto pt-12">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-xl transition-all active:scale-95 text-gray-900 group">
                        Appliquer les changements <i class="fas fa-check-circle ml-2 group-hover:rotate-12 transition-transform"></i>
                    </button>
                    <p class="text-center text-[8px] font-bold text-gray-500 uppercase mt-4 tracking-widest italic">L'historique des modifications sera archivé</p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection