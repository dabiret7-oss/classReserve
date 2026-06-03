<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // Liste toutes les réservations
    public function index()
    {
        $enAttente = Reservation::where('statut', 'en_attente')
            ->with(['user', 'salle'])
            ->latest()
            ->get();

        $traitees = Reservation::whereIn('statut', ['validee', 'rejetee'])
            ->with(['user', 'salle'])
            ->latest()
            ->paginate(10);

        return view('admin.reservations.index', compact('enAttente', 'traitees'));
    }

    // Valider une réservation
    public function valider(Reservation $reservation)
    {
        $reservation->update(['statut' => 'validee']);
        return back()->with('success', "La réservation de {$reservation->user->nom} a été validée.");
    }

    // Rejeter une réservation
    public function rejeter(Reservation $reservation)
    {
        $reservation->update(['statut' => 'rejetee']);
        return back()->with('success', "La réservation de {$reservation->user->nom} a été rejetée.");
    }
}