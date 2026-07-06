<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    // Un utilisateur peut être un client
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    // Un utilisateur peut être un agent
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }
}
