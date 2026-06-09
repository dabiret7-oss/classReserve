<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Cahier;
use App\Models\CahierAcces;
use App\Models\CahierSeance;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CahierController extends Controller
{
    // Liste tous les cahiers + statut d'accès du prof
    public function index()
    {
        $cahiers = Cahier::with(['classe', 'acces' => function ($q) {
            $q->where('user_id', Auth::id());
        }])->where('statut', 'actif')
            ->orderBy('annee_academique', 'desc')
            ->get();

        return view('professeur.cahiers.index', compact('cahiers'));
    }

    // Demander l'accès à un cahier
    public function demanderAcces(Cahier $cahier)
    {
        if ($cahier->demandeEnCours(Auth::id())) {
            return back()->with('success', 'Vous avez déjà fait une demande pour ce cahier.');
        }

        CahierAcces::create([
            'cahier_id' => $cahier->id,
            'user_id'   => Auth::id(),
            'statut'    => 'en_attente',
        ]);

        return back()->with('success', 'Demande d\'accès envoyée. En attente de validation.');
    }

    // Voir et renseigner un cahier (si accès validé)
    public function show(Cahier $cahier)
    {
        if (!$cahier->accesValide(Auth::id())) {
            abort(403, 'Vous n\'avez pas accès à ce cahier.');
        }

        $matieres = Matiere::orderBy('nom')->get();
        $seances  = $cahier->seances()
            ->where('user_id', Auth::id())
            ->with('matiere')
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(fn($s) => $s->matiere?->nom ?? 'Sans matière');

        return view('professeur.cahiers.show', compact('cahier', 'seances', 'matieres'));
    }

    // Ajouter une séance
    public function storeSeance(Request $request, Cahier $cahier)
    {
        if (!$cahier->accesValide(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'matiere_id'   => 'required|exists:matieres,id',
            'date_seance'  => 'required|date',
            'heure_debut'  => 'required',
            'heure_fin'    => 'required',
            'titre_module' => 'required|string|max:255',
            'contenu'      => 'required|string',
        ], [
            'matiere_id.required'   => 'Veuillez choisir une matière.',
            'date_seance.required'  => 'La date est obligatoire.',
            'heure_debut.required'  => 'L\'heure de début est obligatoire.',
            'heure_fin.required'    => 'L\'heure de fin est obligatoire.',
            'titre_module.required' => 'Le titre est obligatoire.',
            'contenu.required'      => 'Le contenu est obligatoire.',
        ]);

        CahierSeance::create([
            'cahier_id'    => $cahier->id,
            'user_id'      => Auth::id(),
            'matiere_id'   => $request->matiere_id,
            'date_seance'  => $request->date_seance,
            'heure_debut'  => $request->heure_debut,
            'heure_fin'    => $request->heure_fin,
            'titre_module' => $request->titre_module,
            'contenu'      => $request->contenu,
        ]);

        return back()->with('success', 'Séance ajoutée au cahier.');
    }
}