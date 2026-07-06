<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use App\Models\Contrat;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Liste de tous les utilisateurs
    public function utilisateurs()
    {
        return response()->json(User::all());
    }

    // Création d'un compte agent
    public function creerAgent(Request $request)
    {
        $donnees = $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
            'matricule' => 'required|string|unique:agents,matricule',
            'departement' => 'required|string',
        ]);

        $user = User::create([
            'nom' => $donnees['nom'],
            'email' => $donnees['email'],
            'mot_de_passe' => Hash::make($donnees['mot_de_passe']),
            'role' => 'agent',
        ]);

        $agent = Agent::create([
            'user_id' => $user->id,
            'matricule' => $donnees['matricule'],
            'departement' => $donnees['departement'],
        ]);

        return response()->json(['user' => $user, 'agent' => $agent], 201);
    }

    // Modification d'un utilisateur (nom, email)
    public function modifierUtilisateur(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $donnees = $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($donnees);

        return response()->json($user);
    }

    // Suppression d'un utilisateur
    public function supprimerUtilisateur($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }

    // Statistiques globales pour le tableau de bord administrateur
    public function statistiques()
    {
        return response()->json([
            'total_clients' => Client::count(),
            'total_agents' => Agent::count(),
            'total_contrats' => Contrat::count(),
            'contrats_par_type' => Contrat::selectRaw('type, count(*) as total')
                ->groupBy('type')
                ->get(),
        ]);
    }
}
