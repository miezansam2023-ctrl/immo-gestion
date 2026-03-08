<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Bien;
use App\Models\Locataire;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
    /**
     * Enregistre le contrat
     */
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
            // Ajoute les autres champs si nécessaire...
            'animaux_autorises' => 'sometimes|boolean',
            'renouvellement_automatique' => 'sometimes|boolean',
            'date_signature' => 'sometimes|date',
            'clauses_particulieres' => 'nullable|string',
            'date_etat_lieux_entree' => 'nullable|date',
            'frais_agence' => 'nullable|numeric',
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

        return redirect()->route('contrats.index')->with('success', "Le bail {$contrat->numero} a été généré avec succès !");
    }

    /**
     * Affiche les détails d'un contrat
     */
    public function show(Contrat $contrat)
    {
        $contrat->load(['bien', 'locataire']);
        return view('contrats.show', compact('contrat'));
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
