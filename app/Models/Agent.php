<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'user_id',
        'matricule',
        'departement',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un agent traite zéro ou plusieurs sinistres
    public function sinistres()
    {
        return $this->hasMany(Sinistre::class);
    }

    // Mettre à jour le statut d'un sinistre
    public function traiterSinistre(Sinistre $sinistre, string $statut): Sinistre
    {
        $sinistre->update(['statut' => $statut, 'agent_id' => $this->id]);
        return $sinistre;
    }

    public function contacterClient(Client $client): void
    {
        // Envoi d'une notification au client concerné
    }
}
