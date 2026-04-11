<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Bien;
use App\Models\Locataire;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratController extends Controller
{
    /**
     * Affiche la liste ET le formulaire (Vue Unique)
     */
    // Dans ContratController.php

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = $user->contrats()->with(['bien', 'locataire']);
        if ($request->filled('search')) {
            $query->recherche($request->search);
        }
        $contrats = $query->latest()->paginate(10);

        $biens = $user->biens()->where('statut', 'disponible')->get();

        // MODIFICATION ICI : On enlève le ->where('actif', true) pour tester
        $locataires = $user->locataires()->get();

        return view('contrats.index', compact('contrats', 'biens', 'locataires'));
    }
    // Enregistre le contrat
    
    public function store(Request $request)
    {   
        

        $validated = $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'locataire_id' => 'required|exists:locataires,id',
            'date_debut' => 'required|date',
            'duree_mois' => 'required|integer|min:1',
            'loyer_mensuel' => 'required|numeric',
            'caution' => 'required|numeric',
            'mode_paiement' => 'required|string',
            'jour_paiement' => 'required|integer|between:1,31',
            'animaux_autorises' => 'sometimes|boolean',
            'renouvellement_automatique' => 'sometimes|boolean',
            'date_signature' => 'sometimes|date',
            'clauses_particulieres' => 'nullable|string',
            'date_etat_lieux_entree' => 'nullable|date',
            'frais_agence' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'etat_lieux_entree' => 'nullable|string',
        ]);

        // 1. On parse la date
        $dateDebut = \Carbon\Carbon::parse($request->date_debut);

        // 2. On calcule la date de fin en forçant le type ENTIER (int)
        $validated['date_fin'] = $dateDebut->copy()->addMonths((int)$request->duree_mois);

        // 3. Données automatiques
        $validated['gestionnaire_id'] = auth()->id();
        $validated['statut'] = 'actif';
        $validated['date_signature'] = $request->date_signature ?? now();

        // 4. Checkboxes
        $validated['animaux_autorises'] = $request->has('animaux_autorises');
        $validated['renouvellement_automatique'] = $request->has('renouvellement_automatique');

        // 5. Création
        $contrat = \App\Models\Contrat::create($validated);

        // 6. Mise à jour du bien
        $contrat->bien->update(['statut' => 'occupe']);

        // 7. Créer le paiement pour le premier mois (considéré comme payé)
        $datePremierMois = \Carbon\Carbon::parse($request->date_debut);
        $moisAnneePremier = ucfirst($datePremierMois->locale('fr')->translatedFormat('F Y'));

        \App\Models\Paiement::create([
            'contrat_id'      => $contrat->id,
            'locataire_id'    => $contrat->locataire_id,
            'bien_id'         => $contrat->bien_id,
            'gestionnaire_id' => auth()->id(),
            'numero'          => (new \App\Models\Paiement)->genererNumero(),
            'type'            => 'loyer',
            'type_selection'  => 'simple',
            'montant_du'      => $contrat->loyer_mensuel,
            'montant_paye'    => $contrat->loyer_mensuel,
            'reste_a_payer'   => 0,
            'periode_debut'   => $datePremierMois->copy()->startOfMonth()->toDateString(),
            'periode_fin'     => $datePremierMois->copy()->endOfMonth()->toDateString(),
            'date_echeance'   => $datePremierMois->copy()->startOfMonth()->toDateString(),
            'date_paiement'   => $request->date_signature ?? $request->date_debut,
            'mode_paiement'   => $request->mode_paiement,
            'mois_annee'      => $moisAnneePremier,
            'statut'          => 'paye',
            'reference_paiement' => 'Paiement initial - Premier mois',
            'notes'           => 'Paiement automatique lors de la validation du contrat',
        ]);

        return redirect()->route('contrats.index')->with('success', "Le bail {$contrat->numero} a été généré avec succès ! Le premier mois est marqué comme payé.");
    }

    /**
     * Affiche les détails d'un contrat
     */
    public function show(Contrat $contrat)
    {
        $contrat->load(['bien', 'locataire']);

        $paiements = $contrat->paiements()
            ->orderByDesc('date_paiement')
            ->paginate(5);

        return view('contrats.show', compact('contrat', 'paiements'));
    }

    public function generatePDF($id)
    {
        $contrat = Contrat::with(['bien', 'locataire'])->findOrFail($id);

        // On prépare les données pour la vue PDF
        $data = [
            'contrat' => $contrat,
            'date' => date('d/m/Y'),
        ];

        // Chargement de la vue spécifique pour le PDF
        $pdf = Pdf::loadView('contrats.pdf_template', $data);

        // Téléchargement du fichier
        return $pdf->download('Contrat_Bail_' . $contrat->numero . '.pdf');
    }

    public function edit(Contrat $contrat)
    {
        // On récupère les biens et locataires au cas où (même si on bloque le changement ici pour la sécurité)
        $biens = Bien::all();
        $locataires = Locataire::all();
        return view('contrats.edit', compact('contrat', 'biens', 'locataires'));
    }

    public function update(Request $request, Contrat $contrat)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'duree_mois' => 'required|integer',
            'loyer_mensuel' => 'required|numeric',
            'caution' => 'required|numeric',
            'statut' => 'required|string',
            'mode_paiement' => 'required|string',
            'jour_paiement' => 'required|integer|between:1,31', // Ajouté pour cohérence
            'animaux_autorises' => 'sometimes|boolean',
            'renouvellement_automatique' => 'sometimes|boolean',
            'date_etat_des_lieux' => 'nullable|date',
            'frais_agence' => 'nullable|numeric',
            'etat_des_lieux_entree' => 'nullable|string',
            'notes' => 'nullable|string',
            'date_etat_lieux_entree' => 'nullable|date',
        ]);

        // Recalcul de la date de fin
        $dateDebut = \Carbon\Carbon::parse($request->date_debut);
        $validated['date_fin'] = $dateDebut->copy()->addMonths((int)$request->duree_mois);

        // Gestion propre des checkboxes (si décoché, n'existe pas dans $request)
        $validated['renouvellement_automatique'] = $request->has('renouvellement_automatique');
        $validated['animaux_autorises'] = $request->has('animaux_autorises');

        // Si le statut passe à "résilié", mettre à jour le bien et ajouter la date + heure de résiliation
        if ($request->statut === 'resilie' && $contrat->statut !== 'resilie') {
            $validated['date_resiliation'] = now();
            // Remettre le bien en disponible
            if ($contrat->bien) {
                $contrat->bien->update(['statut' => 'disponible']);
            }
        }

        // Si le statut repasse à "actif", remettre le bien en occupé
        if ($request->statut === 'actif' && $contrat->statut === 'resilie') {
            if ($contrat->bien) {
                $contrat->bien->update(['statut' => 'occupe']);
            }
        }

        $contrat->update($validated);

        return redirect()->route('contrats.index')->with('success', 'Le contrat a été mis à jour avec succès.');
    }
    public function destroy(Contrat $contrat)
    {
        // 1. Récupérer le bien associé avant de supprimer le contrat
        $bien = $contrat->bien;

        // 2. Supprimer le contrat
        $contrat->delete();

        // 3. Remettre le bien en statut 'disponible'
        if ($bien) {
            $bien->update(['statut' => 'disponible']);
        }

        return redirect()->route('contrats.index')
            ->with('success', 'Le bail a été annulé et le bien est à nouveau disponible.');
    }
}