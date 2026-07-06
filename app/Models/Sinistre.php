<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sinistre extends Model
{
    protected $fillable = [
        'client_id',
        'agent_id',
        'contrat_id',
        'description',
        'date',
        'statut',
        'document',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function mettreAJourStatut(string $statut): void
    {
        $this->update(['statut' => $statut]);
    }

    public function envoyerDocument(string $cheminFichier): void
    {
        $this->update(['document' => $cheminFichier]);
    }
}
