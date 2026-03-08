<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{

    public function index()
    {
        $incidents = auth()->user()->incidents()
            ->with('bien')
            ->orderBy('priorite', 'desc') // Les plus urgents en premier
            ->latest()
            ->paginate(10);

        return view('incidents.index', compact('incidents'));
    }
    
    public function updateStatus(Request $request, Incident $incident)
    {
        $request->validate(['statut' => 'required']);

        $incident->update(['statut' => $request->statut]);
        $incident->ajouterHistorique("Statut modifié en : " . $request->statut);

        return back()->with('success', 'Statut de l\'incident mis à jour.');
    }
}
