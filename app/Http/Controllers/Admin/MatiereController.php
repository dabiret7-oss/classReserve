<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::orderBy('nom')->get();
        return view('admin.matieres.index', compact('matieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'  => 'required|string|max:255|unique:matieres',
            'code' => 'required|string|max:20|unique:matieres',
        ], [
            'nom.required'  => 'Le nom de la matière est obligatoire.',
            'nom.unique'    => 'Cette matière existe déjà.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique'   => 'Ce code est déjà utilisé.',
        ]);

        Matiere::create($request->only('nom', 'code'));

        return back()->with('success', "Matière {$request->nom} ajoutée.");
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return back()->with('success', "Matière supprimée.");
    }
}