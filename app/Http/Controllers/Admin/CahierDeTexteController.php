<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CahierDeTexte;

class CahierDeTexteController extends Controller
{
    public function index()
    {
        // Admin voit tout, rangé par classe puis matière
        $cahiers = CahierDeTexte::with(['reservation.salle', 'user', 'matiere', 'classe'])
            ->latest()
            ->get()
            ->groupBy(fn($c) => $c->classe?->nom ?? 'Sans classe');

        return view('admin.cahiers.index', compact('cahiers'));
    }
}