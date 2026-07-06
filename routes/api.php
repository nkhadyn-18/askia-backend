<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\SinistreController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Routes publiques (pas besoin d'être connecté)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées (utilisateur connecté requis)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Réservé aux clients
    Route::middleware('role:client')->group(function () {
        Route::get('/contrats', [ContratController::class, 'index']);
        Route::post('/contrats', [ContratController::class, 'store']);
        Route::put('/contrats/{id}/renouveler', [ContratController::class, 'renouveler']);

        Route::get('/sinistres', [SinistreController::class, 'index']);
        Route::post('/sinistres', [SinistreController::class, 'store']);

        Route::get('/paiements', [PaiementController::class, 'index']);
        Route::post('/paiements/effectuer', [PaiementController::class, 'effectuer']);
        Route::get('/paiements/{id}', [PaiementController::class, 'show']);
    });

    // Réservé aux agents
    Route::middleware('role:agent')->group(function () {
        Route::get('/agent/clients', [AgentController::class, 'clients']);
        Route::get('/agent/sinistres', [AgentController::class, 'sinistresATraiter']);
        Route::put('/sinistres/{id}', [SinistreController::class, 'update']);
    });

    // Réservé aux administrateurs
    Route::middleware('role:administrateur')->group(function () {
        Route::get('/admin/utilisateurs', [AdminController::class, 'utilisateurs']);
        Route::post('/admin/agents', [AdminController::class, 'creerAgent']);
        Route::get('/admin/statistiques', [AdminController::class, 'statistiques']);
        Route::put('/admin/utilisateurs/{id}', [AdminController::class, 'modifierUtilisateur']);
        Route::delete('/admin/utilisateurs/{id}', [AdminController::class, 'supprimerUtilisateur']);
    });
});
