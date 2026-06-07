<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CahierDeTexte extends Model
{
    protected $table = 'cahiers_de_texte';

    protected $fillable = [
        'reservation_id',
        'user_id',
        'matiere_id',
        'classe_id',
        'titre_module',
        'contenu',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}