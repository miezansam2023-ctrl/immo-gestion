<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BienController extends Controller
{
    /**
     * Affiche la liste des biens du gestionnaire connecté.
     */
    public function index(Request $request)
    {
        // On récupère uniquement les biens liés au gestionnaire (via la relation dans le model User)
        $biens = auth()->user()->biens()->latest()->paginate(10);

        // On récupère le terme de recherche
        $search = $request->input('search');
        $statut = $request->input('statut');

        // Requête de base : uniquement les biens de l'utilisateur connecté
        $query = auth()->user()->biens();

        // Application du filtre de recherche textuelle
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('ville', 'like', "%{$search}%")
                    ->orWhere('commune', 'like', "%{$search}%")
                    ->orWhere('quartier', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('nom_proprietaire', 'like', "%{$search}%");
            });
        }

        // Application du filtre par statut
        if ($statut && $statut !== 'tous') {
            $query->where('statut', $statut);
        }

        // Récupération des données paginées (on garde le paginate pour la performance)
        $biens = $query->latest()->paginate(10)->withQueryString();

        
        return view('biens.index', compact('biens'));
    }

    /**
     * Enregistre un nouveau bien avec gestion des fichiers.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:villa,appartement,studio,magasin,bureau,terrain',
            'superficie' => 'nullable|numeric',
            'nombre_pieces' => 'nullable|integer',
            'nombre_chambres' => 'nullable|integer',
            'nombre_salles_bain' => 'nullable|integer',
            'etage' => 'nullable|integer',
            'meuble' => 'boolean',
            'equipements' => 'nullable|array',
            'description' => 'nullable|string',
            'adresse' => 'required|string',
            'commune' => 'required|string',
            'quartier' => 'required|string',
            'ville' => 'nullable|string',
            'prix_loyer' => 'required|numeric|min:0',
            'prix_caution' => 'nullable|numeric|min:0',
            'charges' => 'required|numeric|min:0',
            'nom_proprietaire' => 'required|string',
            'telephone_proprietaire' => 'nullable|string',
            'email_proprietaire' => 'nullable|email',
            'statut' => 'required|in:disponible,occupe,maintenance',
            'date_acquisition' => 'nullable|date',
            'notes' => 'nullable|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'documents.*' => 'mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        // On commence avec les données validées
        $data = $validated;

        // 1. Gestion des photos
        if ($request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $paths[] = $photo->store('biens/photos', 'public');
            }
            $data['photos'] = $paths; // On ajoute les chemins au tableau $data
        } else {
            $data['photos'] = []; // Tableau vide si pas de photos
        }

        // 2. Gestion des documents
        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('biens/documents', 'public');
            }
            $data['documents'] = $docPaths;
        } else {
            $data['documents'] = [];
        }

        // 3. Données additionnelles
        $data['gestionnaire_id'] = auth()->id();
        $data['meuble'] = $request->has('meuble');

        // 4. Création
        $bien = Bien::create($data);

        return redirect()->route('biens.index')->with('success', "Le bien {$bien->reference} a été créé avec succès !");
    }

    public function edit($id)
    {
        $bien = Bien::findOrFail($id);
        return view('biens.edit', compact('bien'));
    }

    public function destroy($id)
    {
        $bien = Bien::findOrFail($id);
        $bien->delete();

        return redirect()->route('biens.index')
            ->with('success', 'Le bien a été retiré du patrimoine avec succès !');
    }

    public function update(Request $request, $id)
    {
        $bien = Bien::findOrFail($id);

        // 1. Validation
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'prix_loyer' => 'required|numeric',
            'type' => 'required',
            'statut' => 'required',
            'adresse' => 'required|string',
            'commune' => 'required|string',
            'quartier' => 'required|string',
            'ville' => 'nullable|string',
            'superficie' => 'nullable|numeric',
            'nombre_pieces' => 'nullable|integer',
            'nombre_chambres' => 'nullable|integer',
            'nombre_salles_bain' => 'nullable|integer',
            'prix_caution' => 'nullable|numeric',
            'description' => 'nullable|string',
            'nom_proprietaire' => 'nullable|string',
            'telephone_proprietaire' => 'nullable|string',
            'equipements' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Initialisation des photos avec celles que l'utilisateur a gardées (old_photos)
        // Si l'utilisateur a tout supprimé, on part d'un tableau vide
        $photosFinales = $request->input('old_photos', []);

        // 3. Gestion des suppressions physiques sur le disque
        if ($request->has('removed_photos')) {
            foreach ($request->removed_photos as $pathToDelete) {
                // On supprime le fichier seulement s'il existe pour éviter les erreurs
                if (\Storage::disk('public')->exists($pathToDelete)) {
                    \Storage::disk('public')->delete($pathToDelete);
                }
            }
        }

        // 4. Ajout des nouvelles photos téléchargées
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                // On vérifie qu'on ne dépasse pas la limite de 5
                if (count($photosFinales) < 5) {
                    $path = $photo->store('biens/photos', 'public');
                    $photosFinales[] = $path;
                }
            }
        }

        // 5. Préparation des données pour la mise à jour
        $data = $validated;
        $data['photos'] = $photosFinales; // On met à jour le tableau complet des photos

        // 6. Mise à jour en base de données
        $bien->update($data);

        return redirect()->route('biens.index')
            ->with('success', 'Le patrimoine "' . $bien->titre . '" a été mis à jour avec succès !');
    }

    public function show(Bien $bien)
    {
        // On charge le contrat actif et le locataire associé pour l'affichage
        $bien->load(['contrats' => function ($query) {
            $query->where('statut', 'actif')->with('locataire');
        }]);

        return view('biens.show', compact('bien'));
    }


    // 
}
