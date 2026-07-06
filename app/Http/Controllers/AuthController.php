<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Inscription d'un nouveau client
    public function register(Request $request)
    {
        $donnees = $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:6',
            'telephone' => 'required|string',
            'adresse' => 'required|string',
        ]);

        $user = User::create([
            'nom' => $donnees['nom'],
            'email' => $donnees['email'],
            'mot_de_passe' => Hash::make($donnees['mot_de_passe']),
            'role' => 'client',
        ]);

        $client = Client::create([
            'user_id' => $user->id,
            'telephone' => $donnees['telephone'],
            'adresse' => $donnees['adresse'],
        ]);

        $token = $user->createToken('askia')->plainTextToken;

        return response()->json([
            'user' => $user,
            'client' => $client,
            'token' => $token,
        ], 201);
    }

    // Connexion d'un utilisateur (client, agent ou administrateur)
    public function login(Request $request)
    {
        $donnees = $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        $user = User::where('email', $donnees['email'])->first();

        if (! $user || ! Hash::check($donnees['mot_de_passe'], $user->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 401);
        }

        $token = $user->createToken('askia')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie.']);
    }
}
