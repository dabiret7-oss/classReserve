<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\CahierDeTexte;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CahierDeTexteController extends Controller
{
    // Liste des cahiers du professeur rangés par classe et matière
    public function index()
    {
        $cahiers = CahierDeTexte::where('user_id', Auth::id())
            ->with(['reservation.salle', 'matiere', 'classe'])
            ->latest()
            ->get()
            ->groupBy(fn($c) => $c->classe?->nom ?? 'Sans classe');

        return view('professeur.cahiers.index', compact('cahiers'));
    }

    // Formulaire de création lié à une réservation validée
    public function create(Reservation $reservation)
    {
        // Vérifier que c'est sa réservation et qu'elle est validée
        if ($reservation->user_id !== Auth::id() || !$reservation->isValidee()) {
            abort(403);
        }

        // Vérifier qu'il n'y a pas déjà un cahier pour cette réservation
        if ($reservation->cahierDeTexte) {
            return redirect()->route('professeur.cahiers.index')
                ->with('success', 'Un cahier de texte existe déjà pour ce cours.');
        }

        return view('professeur.cahiers.create', compact('reservation'));
    }

    // Enregistrer le cahier
    public function store(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->isValidee()) {
            abort(403);
        }

        $request->validate([
            'titre_module' => 'required|string|max:255',
            'contenu'      => 'required|string',
        ], [
            'titre_module.required' => 'Le titre du module est obligatoire.',
            'contenu.required'      => 'Le contenu est obligatoire.',
        ]);

        CahierDeTexte::create([
            'reservation_id' => $reservation->id,
            'user_id'        => Auth::id(),
            'matiere_id'     => $reservation->matiere_id,
            'classe_id'      => $reservation->classe_id,
            'titre_module'   => $request->titre_module,
            'contenu'        => $request->contenu,
        ]);

        return redirect()->route('professeur.cahiers.index')
            ->with('success', 'Cahier de texte enregistré avec succès.');
    }
}