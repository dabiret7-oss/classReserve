<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cahier extends Model
{
    protected $fillable = ['classe_id', 'annee_academique', 'statut'];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function seances()
    {
        return $this->hasMany(CahierSeance::class);
    }

    public function acces()
    {
        return $this->hasMany(CahierAcces::class);
    }

    // Vérifier si un prof a accès
    public function accesValide($userId): bool
    {
        return $this->acces()
            ->where('user_id', $userId)
            ->where('statut', 'valide')
            ->exists();
    }

    // Vérifier si un prof a déjà demandé l'accès
    public function demandeEnCours($userId): bool
    {
        return $this->acces()
            ->where('user_id', $userId)
            ->exists();
    }
}