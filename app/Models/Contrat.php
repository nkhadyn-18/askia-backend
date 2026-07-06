<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    protected $fillable = [
        'client_id',
        'type',
        'date_debut',
        'date_fin',
        'montant',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sinistres()
    {
        return $this->hasMany(Sinistre::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Renouveler le contrat pour un an supplémentaire
    public function renouveler(): void
    {
        $this->update([
            'date_debut' => $this->date_fin,
            'date_fin' => now()->parse($this->date_fin)->addYear(),
        ]);
    }
}
