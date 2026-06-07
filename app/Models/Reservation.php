<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'salle_id',
        'matiere_id',
        'classe_id',
        'date_debut',
        'heure_fin',
        'motif',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function isValidee(): bool
    {
        return $this->statut === 'validee';
    }

    public function cahierDeTexte(){
        return $this->hasOne(CahierDeTexte::class);
    }
}