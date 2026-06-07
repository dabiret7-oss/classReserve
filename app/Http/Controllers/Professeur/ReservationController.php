<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Reservation;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['salle', 'matiere', 'classe'])
            ->latest()
            ->paginate(10);

        return view('professeur.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $salles   = Salle::where('statut', 'active')->orderBy('niveau')->get();
        $matieres = Matiere::orderBy('nom')->get();
        $classes  = Classe::orderBy('niveau')->orderBy('nom')->get();

        return view('professeur.reservations.create',
            compact('salles', 'matieres', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salle_id'    => 'required|exists:salles,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'classe_id'   => 'required|exists:classes,id',
            'date_debut'  => 'required|date|after:now',
            'duree'       => 'required|in:1,1.5,2,2.5,3,3.5,4',
            'motif'       => 'required|string|max:255',
        ], [
            'salle_id.required'   => 'Veuillez choisir une salle.',
            'matiere_id.required' => 'Veuillez choisir une matière.',
            'classe_id.required'  => 'Veuillez choisir une classe.',
            'date_debut.required' => 'La date et heure de début sont obligatoires.',
            'date_debut.after'    => 'La date doit être dans le futur.',
            'duree.required'      => 'La durée est obligatoire.',
            'motif.required'      => 'Le motif est obligatoire.',
        ]);

        // Calculer heure de fin
        $dateDebut = new \DateTime($request->date_debut);
        $minutes   = (int)($request->duree * 60);
        $heureFin  = (clone $dateDebut)->modify("+{$minutes} minutes");

        // Vérifier conflit
        $conflitDebut = $request->date_debut;
        $conflitFin   = $heureFin->format('Y-m-d H:i:s');

        $conflit = Reservation::where('salle_id', $request->salle_id)
            ->where('statut', '!=', 'rejetee')
            ->where(function ($query) use ($conflitDebut, $conflitFin) {
                $query->whereBetween('date_debut', [$conflitDebut, $conflitFin])
                      ->orWhereRaw(
                          "ADDTIME(DATE(date_debut), heure_fin) > ? AND date_debut < ?",
                          [$conflitDebut, $conflitFin]
                      );
            })->exists();

        if ($conflit) {
            return back()->withInput()->withErrors([
                'date_debut' => 'Cette salle est déjà réservée sur cette plage horaire.'
            ]);
        }

        Reservation::create([
            'user_id'    => Auth::id(),
            'salle_id'   => $request->salle_id,
            'matiere_id' => $request->matiere_id,
            'classe_id'  => $request->classe_id,
            'date_debut' => $request->date_debut,
            'heure_fin'  => $heureFin->format('H:i:s'),
            'motif'      => $request->motif,
            'statut'     => 'en_attente',
        ]);

        return redirect()->route('professeur.reservations.index')
            ->with('success', 'Votre demande de réservation a été envoyée.');
    }

    public function annuler(Reservation $reservation)
    {
        // Vérifier que c'est bien sa réservation et qu'elle est en attente
        if ($reservation->user_id !== Auth::id() || !$reservation->isEnAttente()) {
            abort(403);
        }

        $reservation->delete();

        return back()->with('success', 'Réservation annulée.');
    }
}