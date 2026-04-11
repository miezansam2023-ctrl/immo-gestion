<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocataireController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // On part de la relation pour la sécurité (uniquement SES locataires)
        $query = auth()->user()->locataires();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenoms', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // On charge les contrats pour éviter le problème N+1 (performance)
        $locataires = $query->with('contrats.bien')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('locataires.index', compact('locataires'));
    }

    public function create()
    {
        return view('locataires.create');
    }

    public function store(Request $request)
    {
        // 1. Validation stricte
        $validated = $request->validate([
            'civilite' => 'required|string|max:10',
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'situation_matrimoniale' => 'nullable|string',
            'telephone' => 'required|string',
            'telephone_secondaire' => 'nullable|string',
            'email' => 'nullable|email',
            'type_piece' => 'required|string',
            'numero_piece' => 'required|string|unique:locataires,numero_piece',
            'date_delivrance_piece' => 'nullable|date',
            'date_expiration_piece' => 'nullable|date',
            'lieu_delivrance_piece' => 'nullable|string',
            'profession' => 'nullable|string',
            'employeur' => 'nullable|string',
            'adresse_employeur' => 'nullable|string',
            'telephone_employeur' => 'nullable|string',
            'revenus_mensuels' => 'nullable|numeric',
            'personne_urgence_nom' => 'nullable|string',
            'personne_urgence_telephone' => 'nullable|string',
            'personne_urgence_lien' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'notes' => 'nullable|string',
        ]);

        // 2. Nettoyage des chaînes vides (évite les erreurs SQL de troncature)
        // Transforme les "" en null
        $data = array_map(function ($value) {
            return $value === "" ? null : $value;
        }, $validated);

        // 3. Gestion de la photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('locataires/photos', 'public');
        }

        // 4. Gestion des documents (JSON)
        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('locataires/documents', 'public');
            }
            // On enregistre le tableau de chemins en JSON (assure-toi que la colonne est de type JSON ou text)
            $data['documents'] = $docPaths;
        }
        // 5. Paramètres forcés
        $data['gestionnaire_id'] = auth()->id();
        $data['actif'] = $request->has('actif');



        // Sécurité pour la civilité (on enlève le point si présent)
        $data['civilite'] = str_replace('.', '', $data['civilite']);

        // 6. Création
        $locataire = Locataire::create($data);

        // 7. Redirection explicite vers le Dashboard
        return redirect()->route('locataires.index')->with(
            'success',
            "Dossier ouvert avec succès pour {$locataire->civilite} {$locataire->nom}."
        );
    }

    public function show(Locataire $locataire)
    {
        if ($locataire->gestionnaire_id !== auth()->id()) {
            abort(403);
        }
        return view('locataires.show', compact('locataire'));
    }

    public function edit(Locataire $locataire)
    {
        // Vérification de sécurité (si tu gères les accès par utilisateur)
        if ($locataire->gestionnaire_id !== auth()->id()) {
            abort(403);
        }

        return view('locataires.edit', compact('locataire'));
    }

    public function update(Request $request, Locataire $locataire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'telephone' => 'required|string',
            'email' => 'nullable|email',
            'type_piece' => 'required|string',
            'numero_piece' => 'required|string|unique:locataires,numero_piece,' . $locataire->id,
            'date_delivrance_piece' => 'nullable|date',
            'date_expiration_piece' => 'nullable|date',
            'lieu_delivrance_piece' => 'nullable|string',
            'profession' => 'nullable|string',
            'employeur' => 'nullable|string',
            'adresse_employeur' => 'nullable|string',
            'telephone_employeur' => 'nullable|string',
            'revenus_mensuels' => 'nullable|numeric',
            'personne_urgence_nom' => 'nullable|string',
            'personne_urgence_telephone' => 'nullable|string',
            'personne_urgence_lien' => 'nullable|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'situation_matrimoniale' => 'nullable|string',
        ]);

        // On récupère toutes les données validées
        $data = $validated;

        // 1. Récupérer les documents actuels
        $currentDocs = is_array($locataire->documents) ? $locataire->documents : (json_decode($locataire->documents, true) ?? []);

        // 2. Supprimer les fichiers cochés pour suppression
        if ($request->has('remove_documents')) {
            foreach ($request->remove_documents as $pathToRemove) {
                // Supprimer physiquement le fichier
                Storage::disk('public')->delete($pathToRemove);
                // Retirer du tableau
                $currentDocs = array_filter($currentDocs, fn($path) => $path !== $pathToRemove);
            }
        }

        // 3. Ajouter les nouveaux fichiers
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $currentDocs[] = $file->store('locataires/documents', 'public');
            }
        }

        // 4. Mettre à jour la donnée finale
        $data['documents'] = array_values($currentDocs); // array_values pour réindexer proprement

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // 1. Supprimer l'ancienne photo physiquement
            if ($locataire->photo && Storage::disk('public')->exists($locataire->photo)) {
                Storage::disk('public')->delete($locataire->photo);
            }

            // 2. Stocker la nouvelle photo et mettre à jour le chemin dans $data
            $data['photo'] = $request->file('photo')->store('locataires/photos', 'public');
        }

        // Gestion des documents (similaire à la méthode store)
        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('locataires/documents', 'public');
            }
            $data['copie_piece'] = $docPaths;
            $data['documents'] = $docPaths;
        }

        // IMPORTANT : On utilise $data ici, pas $validated
        $locataire->update($data);

        if ($request->has('remove_documents')) {
            foreach ($request->remove_documents as $path) {
                // Supprime le fichier du disque 'public'
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        return redirect()->route('locataires.show', $locataire->id)
            ->with('success', 'Fiche locataire mise à jour !');
    }
}
