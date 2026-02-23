<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OlheiroShortlistItem extends Model
{
    protected $table = 'olheiro_shortlist_itens';

    protected $fillable = [
        'shortlist_id',
        'atleta_id',
        'status',
        'nota',
    ];

    public function shortlist()
    {
        return $this->belongsTo(OlheiroShortlist::class, 'shortlist_id');
    }

    public function atleta()
    {
        return $this->belongsTo(Atleta::class);
    }
}

