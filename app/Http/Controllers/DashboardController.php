<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // On prépare la variable $stats que la vue réclame
        $stats = [
            'total_biens' => $user->biens()->count(),
            'biens_occupes' => $user->biens()->where('statut', 'occupe')->count(),
            'revenus_mois' => $user->paiements()->where('statut', 'paye')->sum('montant_paye'),
            'total_locataires' => $user->locataires()->where('actif', true)->count(),
            'incidents_actifs' => $user->incidents()->whereNotIn('statut', ['resolu', 'annule'])->count(),
        ];

        // On récupère aussi les derniers paiements
        $derniersPaiements = $user->paiements()->with(['locataire', 'bien'])->latest()->take(5)->get();

        // On envoie le tout à la vue
        return view('dashboard', compact('stats', 'derniersPaiements'));
    }
}