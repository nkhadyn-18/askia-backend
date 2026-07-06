<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'telephone',
        'adresse',
    ];

    // Le client hérite des informations de base de l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un client possède un ou plusieurs contrats
    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    // Un client peut déclarer zéro ou plusieurs sinistres
    public function sinistres()
    {
        return $this->hasMany(Sinistre::class);
    }

    // Un client peut effectuer zéro ou plusieurs paiements
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Souscrire à un nouveau contrat
    public function souscrire(array $donneesContrat): Contrat
    {
        return $this->contrats()->create($donneesContrat);
    }

    // Déclarer un sinistre lié à un contrat
    public function declarerSinistre(array $donneesSinistre): Sinistre
    {
        return $this->sinistres()->create($donneesSinistre);
    }

    // Payer une prime d'assurance
    public function payer(array $donneesPaiement): Paiement
    {
        return $this->paiements()->create($donneesPaiement);
    }
}
