<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = ['nom', 'code'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}