<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Locataire;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{

    /**
     * Affiche le formulaire pour encaisser un loyer
     */
    public function create(Request $request)
    {
        // On récupère les contrats actifs pour le menu déroulant
        // On charge aussi le bien et le locataire pour l'affichage
        $contrats = \App\Models\Contrat::with(['locataire', 'bien'])
            ->where('statut', 'actif')
            ->get();

        // Si on arrive depuis un bouton "Encaisser" spécifique
        $selectedContratId = $request->query('contrat_id');

        return view('paiements.create', compact('contrats', 'selectedContratId'));
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $paiements = \App\Models\Paiement::with(['locataire', 'bien', 'contrat'])
            ->when($search, function ($query) use ($search) {
                $query->where('numero', 'like', "%{$search}%")
                    ->orWhereHas('locataire', function ($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenoms', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Garde la recherche active lors du changement de page

        $contratsActifs = \App\Models\Contrat::with(['locataire', 'bien'])->get();

        return view('paiements.index', compact('paiements', 'contratsActifs'));
    }

    public function store(Request $request)
    {
        // 1. Correction de la validation : on utilise 'mois_annee'
        $validated = $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'montant_paye' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|in:especes,virement,cheque',
            'mois_annee' => 'required', // <--- Changé ici (était mois_annee_raw)
            'notes' => 'nullable|string',
        ]);

        $contrat = \App\Models\Contrat::findOrFail($request->contrat_id);

        // 2. Formatage du mois (on utilise $request->mois_annee)
        $date = \Carbon\Carbon::parse($request->mois_annee);
        $moisAnnee = ucfirst($date->translatedFormat('F Y')); // Ex: "Février 2026"

        // 3. Création du paiement
        $paiement = \App\Models\Paiement::create([
            'contrat_id' => $contrat->id,
            'locataire_id' => $contrat->locataire_id,
            'bien_id' => $contrat->bien_id,
            'gestionnaire_id' => auth()->id(),
            'numero' => 'PAY-' . strtoupper(uniqid()), // Assure-toi que ce champ existe ou est géré
            'type' => 'loyer',
            'montant_du' => $contrat->loyer_mensuel,
            'montant_paye' => $request->montant_paye,
            'date_echeance' => $date->startOfMonth(),
            'date_paiement' => $request->date_paiement,
            'mode_paiement' => $request->mode_paiement,
            'mois_annee' => $moisAnnee,
            'statut' => ($request->montant_paye >= $contrat->loyer_mensuel) ? 'paye' : 'partiel',
            'notes' => $request->notes,
        ]);

        return redirect()->route('paiements.index')->with('success', 'Encaissement enregistré avec succès !');
    }

    public function generateQuittance(Paiement $paiement)
    {
        // On charge les relations nécessaires
        $paiement->load(['locataire', 'bien', 'contrat']);

        $data = [
            'paiement' => $paiement,
            'date' => now()->format('d/m/Y'),
        ];

        // On charge la vue qu'on va créer à l'étape 3
        $pdf = Pdf::loadView('paiements.quittance_pdf', $data);

        // Téléchargement du fichier avec un nom propre
        return $pdf->download('Quittance_' . $paiement->numero . '_' . $paiement->mois_annee . '.pdf');
    }

    public function show(Paiement $paiement)
    {
        // On charge les relations pour éviter les requêtes SQL inutiles dans la vue
        $paiement->load(['locataire', 'bien', 'contrat', 'gestionnaire']);

        return view('paiements.show', compact('paiement'));
    }
}
