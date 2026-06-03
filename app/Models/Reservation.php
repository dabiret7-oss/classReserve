<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'salle_id',
        'date_debut',
        'heure_fin',
        'motif',
        'statut',
    ];

    // Une réservation appartient à un professeur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une réservation appartient à une salle
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    // Méthodes utilitaires
    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function isValidee(): bool
    {
        return $this->statut === 'validee';
    }
}