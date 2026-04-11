<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bien;
use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Locataire;
use Illuminate\Http\Request;
 
class AdminController extends Controller
{
    // ─── Dashboard Admin ────────────────────────────
    public function index()
    {
        $stats = [
            'total_gestionnaires' => User::where('role', 'gestionnaire')->count(),
            'gestionnaires_actifs' => User::where('role', 'gestionnaire')
                                         ->where('actif', true)->count(),
            'gestionnaires_inactifs' => User::where('role', 'gestionnaire')
                                           ->where('actif', false)->count(),
            'total_biens'      => Bien::count(),
            'total_contrats'   => Contrat::count(),
            'total_locataires' => Locataire::count(),
            'total_paiements'  => Paiement::where('statut', 'paye')->sum('montant_paye'),
            'contrats_actifs'  => Contrat::where('statut', 'actif')->count(),
        ];
 
        // Les 5 derniers gestionnaires inscrits
        $derniersGestionnaires = User::where('role', 'gestionnaire')
            ->latest()->take(5)->get();
 
        return view('admin.dashboard', compact('stats', 'derniersGestionnaires'));
    }
 
    // ─── Liste des gestionnaires ─────────────────────
    public function gestionnaires(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('id', '!=', 1) // Exclure l'admin principal
            ->with(['deactivatedBy'])
            ->withCount(['biens', 'contrats', 'locataires', 'paiements']);

        // Si l'utilisateur connecté n'est pas l'admin principal, exclure son propre compte
        if (auth()->id() !== 1) {
            $query->where('id', '!=', auth()->id());
        }

        $gestionnaires = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.gestionnaires', compact('gestionnaires'));
    }
 
    // ─── Détail d'un gestionnaire ────────────────────
    public function showGestionnaire(User $user)
    {
        $user->load(['biens', 'contrats', 'locataires']);
        $stats = [
            'total_biens'      => $user->biens()->count(),
            'biens_occupes'    => $user->biens()->where('statut', 'occupe')->count(),
            'total_contrats'   => $user->contrats()->count(),
            'contrats_actifs'  => $user->contrats()->where('statut', 'actif')->count(),
            'total_locataires' => $user->locataires()->count(),
            'revenus_total'    => $user->paiements()->where('statut', 'paye')->sum('montant_paye'),

        ];
        return view('admin.gestionnaire-show', compact('user', 'stats'));
    }
 
    // ─── Activer / Désactiver ────────────────────────
    public function toggleActif(User $user)
    {
        // Sécurité : empêcher de modifier le premier admin
        if ($user->id === 1) {
            return back()->with('error', 'Impossible de modifier le compte admin principal.');
        }

        if ($user->actif) {
            // Désactivation
            $user->update([
                'actif' => false,
                'deactivated_at' => now(),
                'deactivated_by' => auth()->id(),
            ]);
            $status = 'désactivé';
        } else {
            // Réactivation
            $user->update([
                'actif' => true,
                'deactivated_at' => null,
                'deactivated_by' => null,
            ]);
            $status = 'activé';
        }

        return back()->with('success',
            "Le compte de {$user->nom} {$user->prenoms} a été {$status}.");
    }

    // ─── Changer le rôle ─────────────────────────────
    public function changeRole(User $user)
    {
        // Sécurité : empêcher de modifier le premier admin
        if ($user->id === 1) {
            return back()->with('error', 'Impossible de modifier le rôle de l\'admin principal.');
        }

        // Sécurité : s'assurer qu'il reste au moins un admin actif
        if ($user->role === 'admin') {
            $adminsActifs = User::where('role', 'admin')->where('actif', true)->count();
            if ($adminsActifs <= 1) {
                return back()->with('error', 'Impossible de rétrograder le dernier admin actif.');
            }
        }

        // Changer le rôle
        $nouveauRole = $user->role === 'gestionnaire' ? 'admin' : 'gestionnaire';
        $user->update(['role' => $nouveauRole]);

        $action = $nouveauRole === 'admin' ? 'promu admin' : 'rétrogradé gestionnaire';
        return back()->with('success',
            "Le compte de {$user->nom} {$user->prenoms} a été {$action}.");
    }

    // ─── Supprimer un gestionnaire ───────────────────
    public function destroyGestionnaire(User $user)
    {
        // Sécurité : empêcher de supprimer le premier admin
        if ($user->id === 1) {
            return back()->with('error', 'Impossible de supprimer l\'admin principal.');
        }

        // Sécurité : ne pas supprimer le dernier admin actif
        if ($user->role === 'admin') {
            $adminsActifs = User::where('role', 'admin')->where('actif', true)->count();
            if ($adminsActifs <= 1) {
                return back()->with('error', 'Impossible de supprimer le dernier admin actif.');
            }
        }

        $user->delete();

        return back()->with('success', "Le compte de {$user->nom} {$user->prenoms} a été supprimé définitivement.");
    }
 
    // ─── Stats globales ──────────────────────────────
    public function stats()
    {
        $statsParGestionnaire = User::where('role', 'gestionnaire')
            ->withCount(['biens', 'contrats', 'locataires'])
            ->withSum(['paiements' => function($q) {
                $q->where('statut', 'paye');
            }], 'montant_paye')
            ->orderByDesc('paiements_sum_montant_paye')
            ->get();
 
        return view('admin.stats', compact('statsParGestionnaire'));
    }
}
