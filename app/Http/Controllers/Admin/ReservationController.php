<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use App\Notifications\SalleAttribuee;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $salles  = Salle::orderBy('niveau')->orderBy('nom')->get();
        $salleId = $request->input('salle_id');

        $enAttente = Reservation::where('statut', 'en_attente')
            ->when($salleId, fn($q) => $q->where('salle_id', $salleId))
            ->with(['user', 'salle'])
            ->latest()
            ->get();

        $traitees = Reservation::whereIn('statut', ['validee', 'rejetee'])
            ->when($salleId, fn($q) => $q->where('salle_id', $salleId))
            ->with(['user', 'salle'])
            ->latest()
            ->paginate(10);

        return view('admin.reservations.index',
            compact('enAttente', 'traitees', 'salles', 'salleId'));
    }

    // Valider avec possibilité de changer la salle (Cas 1)
    public function valider(Request $request, Reservation $reservation)
    {
        $request->validate([
            'salle_id' => 'required|exists:salles,id',
        ]);

        $reservation->update([
            'salle_id' => $request->salle_id,
            'statut'   => 'validee',
        ]);

        // Recharger la relation salle après modification
        $reservation->load('salle');

        // Envoyer la notification au professeur
        $reservation->user->notify(new SalleAttribuee($reservation));

        return back()->with('success',
            "Réservation validée — salle {$reservation->salle->nom} attribuée à {$reservation->user->nom}.");
    }

    // Rejeter une réservation
    public function rejeter(Reservation $reservation)
    {
        $reservation->update(['statut' => 'rejetee']);
        return back()->with('success',
            "La réservation de {$reservation->user->nom} a été rejetée.");
    }

    // Formulaire attribution directe (Cas 2)
    public function create()
    {
        $professeurs = User::where('role', 'professeur')
            ->where('statut', 'valide')
            ->orderBy('nom')
            ->get();
        $salles = Salle::where('statut', 'active')
            ->orderBy('niveau')
            ->get();

        return view('admin.reservations.create', compact('professeurs', 'salles'));
    }

    // Enregistrer attribution directe (Cas 2)
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'salle_id'   => 'required|exists:salles,id',
            'date_debut' => 'required|date|after:now',
            'heure_fin'  => 'required',
            'motif'      => 'required|string|max:255',
        ], [
            'user_id.required'    => 'Veuillez choisir un professeur.',
            'salle_id.required'   => 'Veuillez choisir une salle.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.after'    => 'La date doit être dans le futur.',
            'heure_fin.required'  => 'L\'heure de fin est obligatoire.',
            'motif.required'      => 'Le motif est obligatoire.',
        ]);

        $reservation = Reservation::create([
            'user_id'    => $request->user_id,
            'salle_id'   => $request->salle_id,
            'date_debut' => $request->date_debut,
            'heure_fin'  => $request->heure_fin,
            'motif'      => $request->motif,
            'statut'     => 'validee',
        ]);

        $reservation->load(['user', 'salle']);

        // Notifier le professeur
        $reservation->user->notify(new SalleAttribuee($reservation));

        return redirect()->route('admin.reservations.index')
            ->with('success',
                "Salle {$reservation->salle->nom} attribuée à {$reservation->user->nom} pour {$reservation->motif}.");
    }
}