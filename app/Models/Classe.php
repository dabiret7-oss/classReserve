<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = ['nom', 'filiere', 'niveau'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}