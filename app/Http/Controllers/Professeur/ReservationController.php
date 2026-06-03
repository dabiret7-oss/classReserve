<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // Liste des réservations du professeur connecté
    public function index(){
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['salle'])
            ->latest()
            ->paginate(10);

        return view('professeur.reservations.index', compact('reservations'));
    }

    // Formulaire de création
    public function create(){
        $salles = Salle::where('statut', 'active')->orderBy('niveau')->get();
        return view('professeur.reservations.create', compact('salles'));
    }

   

    public function store(Request $request){
        $request->validate([
            'salle_id'   => 'required|exists:salles,id',
            'date_debut' => 'required|date|after:now',
            'heure_fin'  => 'required',
            'motif'      => 'required|string|max:255',
        ], [
            'salle_id.required'   => 'Veuillez choisir une salle.',
            'salle_id.exists'     => 'La salle choisie est invalide.',
            'date_debut.required' => 'La date et heure de début sont obligatoires.',
            'date_debut.after'    => 'La date doit être dans le futur.',
            'heure_fin.required'  => 'L\'heure de fin est obligatoire.',
            'motif.required'      => 'Le motif est obligatoire.',
        ]);

        // Vérifier si la salle est déjà réservée sur cette plage horaire
        $dateDebut = $request->date_debut;
        $heureFin  = date('Y-m-d', strtotime($dateDebut)) . ' ' . $request->heure_fin;

        $conflit = Reservation::where('salle_id', $request->salle_id)
        ->where('statut', '!=', 'rejetee')
        ->where(function ($query) use ($dateDebut, $heureFin) {
            $query->whereBetween('date_debut', [$dateDebut, $heureFin])
                  ->orWhereRaw("ADDTIME(DATE(date_debut), heure_fin) > ? AND date_debut < ?",
                      [$dateDebut, $heureFin]);
        })
        ->exists();

        if ($conflit) {
            return back()
            ->withInput()
            ->withErrors(['date_debut' => 'Cette salle est déjà réservée sur cette plage horaire.']);
        }

        Reservation::create([
            'user_id'    => Auth::id(),
            'salle_id'   => $request->salle_id,
            'date_debut' => $request->date_debut,
            'heure_fin'  => $request->heure_fin,
            'motif'      => $request->motif,
            'statut'     => 'en_attente',
        ]);

        return redirect()->route('professeur.reservations.index')
                     ->with('success', 'Votre demande de réservation a été envoyée.');
    }

}