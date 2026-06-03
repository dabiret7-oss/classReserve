<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom',
        'niveau',
        'statut',
    ];

    public function isActive(): bool
    {
        return $this->statut === 'active';
    }

    // Une salle peut avoir plusieurs réservations
public function reservations()
{
    return $this->hasMany(Reservation::class);
}
}