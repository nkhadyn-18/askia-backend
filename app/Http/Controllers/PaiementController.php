<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    // Liste des paiements du client connecté
    public function index(Request $request)
    {
        $client = $request->user()->client;

        return response()->json($client->paiements()->latest()->get());
    }

    // Initie un paiement via Wave ou Orange Money (PayTech)
    public function effectuer(Request $request)
    {
        $donnees = $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'moyen' => 'required|in:wave,orange_money',
        ]);

        $client = $request->user()->client;
        $contrat = $client->contrats()->findOrFail($donnees['contrat_id']);

        $paiement = $client->payer([
            'contrat_id' => $contrat->id,
            'montant' => $contrat->montant,
            'date' => now(),
            'moyen' => $donnees['moyen'],
            'statut' => 'en_attente',
        ]);

        // Simulation de la confirmation automatique de PayTech
        // Dans un vrai système, cette confirmation viendrait d'un webhook PayTech
        $paiement->confirmer('SIMULATION-' . uniqid());

        return response()->json([
            'paiement' => $paiement->fresh(),
            'redirection' => [
                'statut' => 'confirme',
                'moyen' => $donnees['moyen'],
            ],
        ]);
    }

    // Vérifie le statut d'un paiement
    public function show($id)
    {
        $paiement = Paiement::findOrFail($id);

        return response()->json($paiement);
    }
}
