<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Olheiro extends Authenticatable
{
    use Notifiable;

    protected $table = 'olheiros';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'entidade',
        'cidade',
        'login',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function favoritos()
    {
        return $this->hasMany(OlheiroFavorito::class);
    }

    public function shortlists()
    {
        return $this->hasMany(OlheiroShortlist::class);
    }
}

