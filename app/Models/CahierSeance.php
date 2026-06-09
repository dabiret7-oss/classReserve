<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CahierSeance extends Model
{
    protected $fillable = [
        'cahier_id',
        'user_id',
        'matiere_id',
        'date_seance',
        'heure_debut',
        'heure_fin',
        'titre_module',
        'contenu',
    ];

    public function cahier()
    {
        return $this->belongsTo(Cahier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}