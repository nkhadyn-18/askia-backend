<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinistreController extends Controller
{
    // Liste des sinistres du client connecté
    public function index(Request $request)
    {
        $client = $request->user()->client;

        return response()->json($client->sinistres);
    }

    // Déclaration d'un nouveau sinistre, avec document optionnel
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'contrat_id' => 'required|exists:contrats,id',
            'description' => 'required|string',
            'date' => 'required|date',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $client = $request->user()->client;

        $donneesSinistre = [
            'contrat_id' => $donnees['contrat_id'],
            'description' => $donnees['description'],
            'date' => $donnees['date'],
            'statut' => 'en_attente',
        ];

        $sinistre = $client->declarerSinistre($donneesSinistre);

        if ($request->hasFile('document')) {
            $chemin = $request->file('document')->store('sinistres', 'public');
            $sinistre->envoyerDocument($chemin);
        }

        return response()->json($sinistre->fresh(), 201);
    }

    // Mise à jour du statut par un agent
    public function update(Request $request, $id)
    {
        $donnees = $request->validate([
            'statut' => 'required|in:en_attente,en_cours,traite,refuse',
        ]);

        $agent = $request->user()->agent;
        $sinistre = \App\Models\Sinistre::findOrFail($id);
        $agent->traiterSinistre($sinistre, $donnees['statut']);

        return response()->json($sinistre);
    }
}
