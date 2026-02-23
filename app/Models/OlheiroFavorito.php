<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OlheiroFavorito extends Model
{
    protected $table = 'olheiro_favoritos';

    protected $fillable = [
        'olheiro_id',
        'atleta_id',
    ];

    public function olheiro()
    {
        return $this->belongsTo(Olheiro::class);
    }

    public function atleta()
    {
        return $this->belongsTo(Atleta::class);
    }
}

