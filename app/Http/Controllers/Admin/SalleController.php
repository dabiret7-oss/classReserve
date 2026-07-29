<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    // pagination
    public function index()
    {
    $salles = Salle::orderBy('niveau')->orderBy('nom')->paginate(10);
    return view('admin.salles.index', compact('salles'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('admin.salles.create');
    }

    // Enregistrer une nouvelle salle
    public function store(Request $request)
    {
        $request->validate([
            'nom'    => 'required|string|max:255|unique:salles',
            'niveau' => 'required|in:RDC,R+1,R+2,R+3',
        ], [
            'nom.required'    => 'Le nom de la salle est obligatoire.',
            'nom.unique'      => 'Une salle avec ce nom existe déjà.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in'       => 'Le niveau choisi est invalide.',
        ]);

        Salle::create([
            'nom'    => $request->nom,
            'niveau' => $request->niveau,
            'statut' => 'active',
        ]);

        return redirect()->route('admin.salles.index')
                         ->with('success', 'Salle créée avec succès.');
    }

    // Activer ou désactiver une salle
    public function toggleStatut(Salle $salle)
    {
        $nouveauStatut = $salle->isActive() ? 'inactive' : 'active';
        $salle->update(['statut' => $nouveauStatut]);

        return back()->with('success', 'Statut de la salle mis à jour.');
    }


    public function edit(Salle $salle)
{
    return view('admin.salles.edit', compact('salle'));
}

public function update(Request $request, Salle $salle)
{
    $request->validate([
        'nom'    => 'required|string|max:255|unique:salles,nom,' . $salle->id,
        'niveau' => 'required|in:RDC,R+1,R+2,R+3',
    ], [
        'nom.required'    => 'Le nom de la salle est obligatoire.',
        'nom.unique'      => 'Une salle avec ce nom existe déjà.',
        'niveau.required' => 'Le niveau est obligatoire.',
    ]);

    $salle->update([
        'nom'    => $request->nom,
        'niveau' => $request->niveau,
    ]);

    return redirect()->route('admin.salles.index')
        ->with('success', "Salle {$salle->nom} modifiée avec succès.");
}
}