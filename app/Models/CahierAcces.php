<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CahierAcces extends Model
{
    protected $table = 'cahier_acces';

    protected $fillable = ['cahier_id', 'user_id', 'statut'];

    public function cahier()
    {
        return $this->belongsTo(Cahier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}