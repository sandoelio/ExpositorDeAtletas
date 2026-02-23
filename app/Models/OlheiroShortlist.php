<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OlheiroShortlist extends Model
{
    protected $table = 'olheiro_shortlists';

    protected $fillable = [
        'olheiro_id',
        'nome',
        'descricao',
    ];

    public function olheiro()
    {
        return $this->belongsTo(Olheiro::class);
    }

    public function itens()
    {
        return $this->hasMany(OlheiroShortlistItem::class, 'shortlist_id');
    }
}

