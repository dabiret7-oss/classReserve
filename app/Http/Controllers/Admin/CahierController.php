<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cahier;
use App\Models\CahierAcces;
use App\Models\Classe;
use Illuminate\Http\Request;

class CahierController extends Controller
{
    // Liste tous les cahiers
    public function index()
    {
        $cahiers = Cahier::with(['classe', 'seances', 'acces'])
            ->orderBy('annee_academique', 'desc')
            ->get()
            ->groupBy('annee_academique');

        return view('admin.cahiers.index', compact('cahiers'));
    }

    // Formulaire création cahier
    public function create()
    {
        $classes = Classe::orderBy('niveau')->orderBy('nom')->get();
        return view('admin.cahiers.create', compact('classes'));
    }

    // Enregistrer un cahier
    public function store(Request $request)
    {
        $request->validate([
            'classe_id'        => 'required|exists:classes,id',
            'annee_academique' => 'required|string|max:9',
        ], [
            'classe_id.required'        => 'Veuillez choisir une classe.',
            'annee_academique.required' => 'L\'année académique est obligatoire.',
        ]);

        // Vérifier qu'il n'existe pas déjà
        $existe = Cahier::where('classe_id', $request->classe_id)
            ->where('annee_academique', $request->annee_academique)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'classe_id' => 'Un cahier existe déjà pour cette classe et cette année.'
            ]);
        }

        Cahier::create($request->only('classe_id', 'annee_academique'));

        return redirect()->route('admin.cahiers.index')
            ->with('success', 'Cahier de texte créé avec succès.');
    }

    // Liste des demandes d'accès en attente
    public function acces()
    {
        $demandes = CahierAcces::where('statut', 'en_attente')
            ->with(['user', 'cahier.classe'])
            ->latest()
            ->get();

        return view('admin.cahiers.acces', compact('demandes'));
    }

    // Valider un accès
    public function validerAcces(CahierAcces $acces)
    {
        $acces->update(['statut' => 'valide']);
        return back()->with('success',
            "{$acces->user->nom} a maintenant accès au cahier.");
    }

    // Rejeter un accès
    public function rejeterAcces(CahierAcces $acces)
    {
        $acces->update(['statut' => 'rejete']);
        return back()->with('success',
            "Accès refusé à {$acces->user->nom}.");
    }

    // Voir le contenu d'un cahier
    public function show(Cahier $cahier)
    {
        $seances = $cahier->seances()
            ->with(['user', 'matiere'])
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(fn($s) => $s->matiere?->nom ?? 'Sans matière');

        return view('admin.cahiers.show', compact('cahier', 'seances'));
    }
}