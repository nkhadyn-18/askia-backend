<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContratController extends Controller
{
    // Liste des contrats du client connecté
    public function index(Request $request)
    {
        $client = $request->user()->client;

        return response()->json($client->contrats);
    }

    // Souscription à un nouveau contrat
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'type' => 'required|in:automobile,habitation,sante',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'montant' => 'required|numeric|min:0',
        ]);

        $client = $request->user()->client;
        $contrat = $client->souscrire($donnees);

        return response()->json($contrat, 201);
    }

    // Renouvellement d'un contrat existant
    public function renouveler(Request $request, $id)
    {
        $client = $request->user()->client;
        $contrat = $client->contrats()->findOrFail($id);
        $contrat->renouveler();

        return response()->json($contrat);
    }
}
