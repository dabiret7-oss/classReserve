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



    public function store(Request $request)
    {
        $estExterne     = $request->boolean('activite_externe');
        $estLongPeriode = $request->boolean('longue_periode');

        $request->validate([
            'user_id'          => $estExterne ? 'nullable' : 'required|exists:users,id',
            'salle_id'         => 'required|exists:salles,id',
            'date_debut'       => 'required|date',
            'heure_fin'        => 'required',
            'motif'            => 'required|string|max:255',
            'date_fin_periode' => $estLongPeriode ? 'required|date|after:date_debut' : 'nullable',
        ], [
            'user_id.required'          => 'Veuillez choisir un professeur.',
            'salle_id.required'         => 'Veuillez choisir une salle.',
            'date_debut.required'       => 'La date de début est obligatoire.',
            'heure_fin.required'        => 'L\'heure de fin est obligatoire.',
            'motif.required'            => 'Le motif est obligatoire.',
            'date_fin_periode.required' => 'La date de fin est obligatoire pour une longue période.',
            'date_fin_periode.after'    => 'La date de fin doit être après la date de début.',
        ]);

        // ── LONGUE PÉRIODE ──
        if ($estLongPeriode) {
            $dateDebut = \Carbon\Carbon::parse($request->date_debut);
            $dateFin   = \Carbon\Carbon::parse($request->date_fin_periode);
            $heureFin  = $request->heure_fin;
            $conflicts = [];

            // Vérifier les conflits pour chaque jour
            $current = $dateDebut->copy();
            while ($current->lte($dateFin)) {
                $debutJour = $current->format('Y-m-d') . ' ' . $dateDebut->format('H:i:s');
                $finJour   = $current->format('Y-m-d') . ' ' . $heureFin;

                $dateJour   = $current->format('Y-m-d');
                $heureDebut = $dateDebut->format('H:i:s');

                $conflit = Reservation::where('salle_id', $request->salle_id)
                    ->where('statut', '!=', 'rejetee')
                    ->whereDate('date_debut', $dateJour)
                    ->where('heure_fin', '>', $heureDebut)
                    ->whereRaw('TIME(date_debut) < ?', [$heureFin])
                    ->exists();
            }

            if (!empty($conflicts)) {
                $liste = implode(', ', array_slice($conflicts, 0, 3));
                $plus  = count($conflicts) > 3 ? ' (+' . (count($conflicts) - 3) . ')' : '';
                return back()->withInput()->withErrors([
                    'date_debut' => "Conflits détectés : {$liste}{$plus}. La salle est déjà occupée sur ces jours."
                ]);
            }

            // Créer les réservations
            $groupeId = \Illuminate\Support\Str::uuid();
            $current  = $dateDebut->copy();
            $created  = 0;

            while ($current->lte($dateFin)) {
                Reservation::create([
                    'user_id'          => $request->user_id ?? null,
                    'salle_id'         => $request->salle_id,
                    'matiere_id'       => $request->matiere_id ?? null,
                    'classe_id'        => $request->classe_id ?? null,
                    'date_debut'       => $current->format('Y-m-d') . ' ' . $dateDebut->format('H:i:s'),
                    'heure_fin'        => $heureFin,
                    'motif'            => $request->motif,
                    'statut'           => 'validee',
                    'longue_periode'   => true,
                    'date_fin_periode' => $dateFin->format('Y-m-d'),
                    'groupe_id'        => $groupeId,
                ]);
                $created++;
                $current->addDay();
            }

            // Notifier le professeur si défini
            if ($request->user_id) {
                $resa = Reservation::where('groupe_id', $groupeId)->with(['user','salle'])->first();
                $resa->user->notify(new \App\Notifications\SalleAttribuee($resa));
            }

            $nom = $request->user_id
                ? \App\Models\User::find($request->user_id)->nom
                : 'Activité externe';

            return redirect()->route('admin.reservations.index')
                ->with('success', "{$created} réservation(s) créée(s) du {$dateDebut->format('d/m/Y')} au {$dateFin->format('d/m/Y')} — {$nom}.");

        // ── RÉSERVATION SIMPLE ou ACTIVITÉ EXTERNE ──
    } else {
        $dateResa   = \Carbon\Carbon::parse($request->date_debut)->format('Y-m-d');
        $heureDebut = \Carbon\Carbon::parse($request->date_debut)->format('H:i:s');
        $heureFin   = $request->heure_fin;
        $conflit = Reservation::where('salle_id', $request->salle_id)
            ->where('statut', '!=', 'rejetee')
            ->whereDate('date_debut', $dateResa)
            ->where('heure_fin', '>', $heureDebut)
            ->whereRaw('TIME(date_debut) < ?', [$heureFin])
            ->exists();
        if ($conflit) {
            return back()->withInput()->withErrors([
                'date_debut' => 'Cette salle est déjà réservée sur cette plage horaire.'
            ]);
        }

            if ($conflit) {
                return back()->withInput()->withErrors([
                    'date_debut' => 'Cette salle est déjà réservée sur cette plage horaire.'
                ]);
            }

            $reservation = Reservation::create([
                'user_id'    => $request->user_id ?? null,
                'salle_id'   => $request->salle_id,
                'matiere_id' => $request->matiere_id ?? null,
                'classe_id'  => $request->classe_id ?? null,
                'date_debut' => $request->date_debut,
                'heure_fin'  => $request->heure_fin,
                'motif'      => $request->motif,
                'statut'     => 'validee',
            ]);

            // Notifier si professeur défini
            if ($reservation->user_id) {
                $reservation->load(['user', 'salle']);
                $reservation->user->notify(new \App\Notifications\SalleAttribuee($reservation));
                $msg = "Salle {$reservation->salle->nom} attribuée à {$reservation->user->nom}.";
            } else {
                $msg = "Salle réservée pour activité externe : {$reservation->motif}.";
            }

            return redirect()->route('admin.reservations.index')->with('success', $msg);
        }
    }


    // Calendrier
public function calendrier()
{
    $reservations = Reservation::where('statut', 'validee')
        ->with(['salle', 'user', 'matiere', 'classe'])
        ->get()
        ->map(fn($r) => [
            'id'    => $r->id,
            'title' => $r->salle->nom . ' — ' . ($r->matiere?->nom ?? $r->motif),
            'start' => $r->date_debut,
            'end'   => date('Y-m-d', strtotime($r->date_debut)) . 'T' . $r->heure_fin,
            'color' => $r->salle->statut === 'active' ? '#1a3c6e' : '#6c757d',
            'extendedProps' => [
                'professeur' => $r->user->nom . ' ' . $r->user->prenoms,
                'salle'      => $r->salle->nom . ' — ' . $r->salle->niveau,
                'classe'     => $r->classe?->nom ?? '—',
                'matiere'    => $r->matiere?->nom ?? '—',
            ],
        ]);

    $salles = \App\Models\Salle::where('statut', 'active')->get();

    return view('admin.calendrier', compact('reservations', 'salles'));
}
}