<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{
    // ─── Index ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = $request->input('search');

        // On récupère TOUS les paiements (pour grouper les multi-mois)
        $tousLesPaiements = \App\Models\Paiement::with(['locataire', 'bien', 'contrat'])
            ->when($search, function ($query) use ($search) {
                $formattedSearch = "%{$search}%";
                $query->where('numero', 'like', $formattedSearch)
                    ->orWhere('mois_annee', 'like', $formattedSearch)
                    ->orWhereHas('locataire', function ($q) use ($formattedSearch) {
                        $q->where('nom', 'like', $formattedSearch)
                            ->orWhere('prenoms', 'like', $formattedSearch)
                            ->orWhereRaw("LOWER(CONCAT(nom, ' ', prenoms)) LIKE ?", [strtolower($formattedSearch)]);
                    })
                    ->orWhereHas('bien', function ($q) use ($formattedSearch) {
                        $q->where('titre', 'like', $formattedSearch)
                            ->orWhere('commune', 'like', $formattedSearch)
                            ->orWhere('quartier', 'like', $formattedSearch);
                    });
            })
            ->latest()
            ->get();

        // Grouper : les multi-mois d'un même groupe = même mois_concernes[]
        // On prend le premier de chaque groupe comme représentant
        $paiementsAffiches = $tousLesPaiements->groupBy(function ($p) {
            if ($p->type_selection === 'multiple' && !empty($p->mois_concernes)) {
                // Groupe par contrat + liste de mois (identique pour tous les mois du groupe)
                return $p->contrat_id . '_' . implode(',', $p->mois_concernes);
            }
            return 'single_' . $p->id; // Paiement simple = sa propre ligne
        })->map(function ($groupe) {
            $premier = $groupe->first();
            if ($premier->type_selection === 'multiple') {
                // Montant total = somme de tous les mois du groupe
                $premier->montant_total_groupe = $groupe->sum('montant_paye');
                $premier->tous_les_mois = $groupe->pluck('mois_annee')->toArray();
            }
            return $premier;
        })->values();

        // Pagination manuelle
        $page = $request->get('page', 1);
        $perPage = 10;
        $paiements = new \Illuminate\Pagination\LengthAwarePaginator(
            $paiementsAffiches->forPage($page, $perPage),
            $paiementsAffiches->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $contratsActifs = Contrat::with(['locataire', 'bien'])
            ->where('statut', 'actif')->get();

        $paiementsExistants = \App\Models\Paiement::whereIn('contrat_id', $contratsActifs->pluck('id'))
            ->whereNotIn('statut', ['annule'])
            ->get(['contrat_id', 'mois_annee', 'statut', 'reste_a_payer']);

        $totalEncaisse     = \App\Models\Paiement::where('statut', 'paye')->sum('montant_paye');
        $totalReliquat     = \App\Models\Paiement::sum('reste_a_payer');
        $totalTransactions = \App\Models\Paiement::count();
        $contrat = \App\Models\Paiement::with(['locataire', 'bien', 'contrat']);

        return view('paiements.index', compact(
            'paiements',
            'contratsActifs',
            'paiementsExistants',
            'totalEncaisse',
            'totalReliquat',
            'totalTransactions',
            'contrat'
        ));
    }

    // ─── Store ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'contrat_id'     => 'required|exists:contrats,id',
            'montant_paye'   => 'required|numeric|min:0.01',
            'date_paiement'  => 'required|date',
            'mode_paiement'  => 'required|in:especes,virement,cheque,mobile_money',
            'type_selection' => 'required|in:simple,multiple',
            // Requis si simple
            'mois_annee'     => 'required_if:type_selection,simple',
            // Requis si multiple
            'mois_multiples' => 'required_if:type_selection,multiple|array|min:1',
            'reference_paiement' => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $contrat    = Contrat::findOrFail($request->contrat_id);
        $montantDu  = (float) $contrat->loyer_mensuel;
        $typeSelection = $request->type_selection;

        // ═══════════════════════════════════════════════════
        // CAS 1 : PAIEMENT SIMPLE (1 seul mois)
        // ═══════════════════════════════════════════════════
        if ($typeSelection === 'simple') {

            $date      = \Carbon\Carbon::parse($request->mois_annee);
            // Forcer le format français avec majuscule initiale
            $moisAnnee = ucfirst($date->locale('fr')->translatedFormat('F Y'));
            $montantPaye = (float) $request->montant_paye;
            $resteAPayer = max(0, $montantDu - $montantPaye);

            $paiementExistant = Paiement::where('contrat_id', $request->contrat_id)
                ->where('mois_annee', $moisAnnee)
                ->whereNotIn('statut', ['annule'])
                ->first();

            if ($paiementExistant) {
                //  Déjà payé en entier → bloquer
                if ($paiementExistant->statut === 'paye') {
                    return back()->withErrors([
                        'mois_annee' => "Le loyer de {$moisAnnee} a déjà été payé en totalité."
                    ])->withInput();
                }
                //  Partiel → compléter
                if ($paiementExistant->statut === 'partiel') {
                    $nouveauMontant = $paiementExistant->montant_paye + $montantPaye;
                    $nouveauReste   = max(0, $paiementExistant->montant_du - $nouveauMontant);
                    $nouveauStatut  = $nouveauReste <= 0 ? 'paye' : 'partiel';
                    
                    // Générer automatiquement une note de complément si aucune note n'existe
                    $dateFirstPaiement = $paiementExistant->date_paiement->format('d/m/Y');
                    $dateComplement = \Carbon\Carbon::parse($request->date_paiement)->format('d/m/Y');
                    $montantFirstPaiement = number_format($paiementExistant->montant_paye, 0, ',', ' ') . ' FCFA';
                    
                    $notesFinales = $paiementExistant->notes;
                    if (empty($notesFinales) && $nouveauStatut === 'paye') {
                        // Note auto: date du premier paiement + indication complément
                        $notesFinales = "Complément de paiement du {$dateFirstPaiement}. "
                            . "Premier paiement partiel de {$montantFirstPaiement} effectué le {$dateFirstPaiement}, solde réglé le {$dateComplement}.";
                    } elseif (!empty($notesFinales) && $nouveauStatut === 'paye' && !empty($request->notes)) {
                        // Ajouter les notes de l'utilisateur si présentes
                        $notesFinales .= "\n[Complément du {$dateComplement}] " . $request->notes;
                    }
                    
                    $paiementExistant->update([
                        'montant_paye'  => $nouveauMontant,
                        'reste_a_payer' => $nouveauReste,
                        'statut'        => $nouveauStatut,
                        'date_paiement' => $request->date_paiement,
                        'notes'         => trim($notesFinales),
                    ]);
                    return redirect()->route('paiements.index')
                        ->with('success', "Complément enregistré pour {$moisAnnee} !");
                }
            }

            //  Mois libre ou anticipé → créer
            Paiement::create([
                'contrat_id'      => $contrat->id,
                'locataire_id'    => $contrat->locataire_id,
                'bien_id'         => $contrat->bien_id,
                'gestionnaire_id' => auth()->id(),
                'numero'          => (new Paiement)->genererNumero(),
                'type'            => $request->type ?? 'loyer',
                'type_selection'  => 'simple',
                'montant_du'      => $montantDu,
                'montant_paye'    => $montantPaye,
                'reste_a_payer'   => $resteAPayer,
                'periode_debut'   => $date->copy()->startOfMonth()->toDateString(),
                'periode_fin'     => $date->copy()->endOfMonth()->toDateString(),
                'date_echeance'   => $date->copy()->startOfMonth()->toDateString(),
                'date_paiement'   => $request->date_paiement,
                'mode_paiement'   => $request->mode_paiement,
                'mois_annee'      => $moisAnnee,
                'statut'          => $montantPaye >= $montantDu ? 'paye' : 'partiel',
                'reference_paiement' => $request->reference_paiement,
                'notes'           => $request->notes,
            ]);

            return redirect()->route('paiements.index')
                ->with('success', "Encaissement enregistré pour {$moisAnnee} !");
        }

        // ═══════════════════════════════════════════════════
        // CAS 2 : PAIEMENT MULTIPLE (plusieurs mois)
        // ═══════════════════════════════════════════════════
        if ($typeSelection === 'multiple') {

            $moisSelectionnes = $request->mois_multiples; // ['2026-03','2026-05',...]
            $moisIgnores  = [];
            $moisCreees   = [];
            $moisCompletes = [];

            foreach ($moisSelectionnes as $moisRaw) {
                $date      = \Carbon\Carbon::parse($moisRaw);
                $moisAnnee = ucfirst($date->locale('fr')->translatedFormat('F Y'));

                $existant = Paiement::where('contrat_id', $contrat->id)
                    ->where('mois_annee', $moisAnnee)
                    ->whereNotIn('statut', ['annule'])
                    ->first();

                if ($existant && $existant->statut === 'paye') {
                    // Ignorer ce mois — déjà payé
                    $moisIgnores[] = $moisAnnee;
                    continue;
                }

                if ($existant && $existant->statut === 'partiel') {
                    // Compléter ce mois
                    $dateFirstPaiement = $existant->date_paiement->format('d/m/Y');
                    $dateComplement = \Carbon\Carbon::parse($request->date_paiement)->format('d/m/Y');
                    $montantFirstPaiement = number_format($existant->montant_paye, 0, ',', ' ') . ' FCFA';
                    
                    // Générer automatiquement une note de complément si aucune note n'existe
                    $notesFinales = $existant->notes;
                    if (empty($notesFinales)) {
                        $notesFinales = "Complément de paiement du {$dateFirstPaiement}. "
                            . "Premier paiement partiel de {$montantFirstPaiement} effectué le {$dateFirstPaiement}, solde réglé le {$dateComplement}.";
                    } elseif (!empty($request->notes)) {
                        $notesFinales .= "\n[Complément du {$dateComplement}] " . $request->notes;
                    }
                    
                    $existant->update([
                        'montant_paye'  => $montantDu,
                        'reste_a_payer' => 0,
                        'statut'        => 'paye',
                        'date_paiement' => $request->date_paiement,
                        'notes'         => trim($notesFinales),
                    ]);
                    $moisCompletes[] = $moisAnnee;
                    continue;
                }

                // Créer un nouveau paiement pour ce mois
                Paiement::create([
                    'contrat_id'      => $contrat->id,
                    'locataire_id'    => $contrat->locataire_id,
                    'bien_id'         => $contrat->bien_id,
                    'gestionnaire_id' => auth()->id(),
                    'numero'          => (new Paiement)->genererNumero(),
                    'type'            => 'loyer',
                    'type_selection'  => 'multiple',
                    'mois_concernes'  => [$moisRaw],
                    'montant_du'      => $montantDu,
                    'montant_paye'    => $montantDu,
                    'reste_a_payer'   => 0,
                    'periode_debut'   => $date->copy()->startOfMonth()->toDateString(),
                    'periode_fin'     => $date->copy()->endOfMonth()->toDateString(),
                    'date_echeance'   => $date->copy()->startOfMonth()->toDateString(),
                    'date_paiement'   => $request->date_paiement,
                    'mode_paiement'   => $request->mode_paiement,
                    'mois_annee'      => $moisAnnee,
                    'statut'          => 'paye',
                    'notes'           => $request->notes,
                ]);
                $moisCreees[] = $moisAnnee;
            }

            // Construction du message de retour
            $message = '';
            if (!empty($moisCreees)) {
                $message .= count($moisCreees) . ' paiement(s) créé(s) : '
                    . implode(', ', $moisCreees) . '. ';
            }
            if (!empty($moisCompletes)) {
                $message .= count($moisCompletes) . ' solde(s) complété(s) : '
                    . implode(', ', $moisCompletes) . '. ';
            }
            if (!empty($moisIgnores)) {
                $message .= 'Ignoré(s) car déjà réglé(s) : '
                    . implode(', ', $moisIgnores) . '.';
            }

            if (empty($moisCreees) && empty($moisCompletes)) {
                return back()->withErrors([
                    'mois_multiples' => 'Tous les mois sélectionnés sont déjà réglés : '
                        . implode(', ', $moisIgnores)
                ])->withInput();
            }

            return redirect()->route('paiements.index')
                ->with('success', trim($message));
        }
    }

    // ─── Show ───────────────────────────────────────────────
    public function show(Paiement $paiement)
    {
        $paiement->load(['locataire', 'bien', 'contrat', 'gestionnaire']);
        return view('paiements.show', compact('paiement'));
    }

    // ─── Quittance ──────────────────────────────────────────

    // / ─── Quittance ───────────────────────────────────────────────
    public function generateQuittance(Paiement $paiement)
    {
        $paiement->load(['locataire', 'bien', 'contrat']);

        // Sauvegarde quittance en base
        if (!$paiement->quittance_generee) {
            $paiement->update([
                'quittance_generee'         => true,
                'numero_quittance'          => $paiement->genererNumeroQuittance(),
                'date_generation_quittance' => now()->toDateString(),
            ]);
        }

        $pdf = Pdf::loadView('paiements.quittance_pdf', [
            'paiement' => $paiement,
            'date'     => now()->format('d/m/Y'),
        ]);

        return $pdf->download(
            'Quittance_' . $paiement->numero . '_' . $paiement->mois_annee . '.pdf'
        );
    }
}
