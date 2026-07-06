<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sinistre;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // Liste de tous les clients de la plateforme
    public function clients()
    {
        return response()->json(Client::with('user')->get());
    }

    // Liste des sinistres à traiter (en attente ou en cours)
    public function sinistresATraiter()
    {
        $sinistres = Sinistre::whereIn('statut', ['en_attente', 'en_cours'])
            ->with(['client.user', 'contrat'])
            ->get();

        return response()->json($sinistres);
    }
}
