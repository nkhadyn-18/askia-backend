<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'client_id',
        'contrat_id',
        'montant',
        'date',
        'moyen',
        'reference_paytech',
        'statut',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    // Initie le paiement via PayTech (Wave ou Orange Money)
    public function effectuer(): array
    {
        // Appel à l'API PayTech avec le montant et le moyen choisi
        return [
            'statut' => 'en_attente',
            'moyen' => $this->moyen,
        ];
    }

    // Confirme le paiement une fois la notification PayTech reçue
    public function confirmer(string $reference): void
    {
        $this->update([
            'statut' => 'confirme',
            'reference_paytech' => $reference,
        ]);
    }
}
