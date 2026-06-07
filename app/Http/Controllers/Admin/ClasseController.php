<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::orderBy('niveau')->orderBy('nom')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:255|unique:classes',
            'filiere' => 'required|string|max:255',
            'niveau'  => 'required|in:Licence 1,Licence 2,Licence 3,Master 1,Master 2',
        ], [
            'nom.required'     => 'Le nom de la classe est obligatoire.',
            'nom.unique'       => 'Cette classe existe déjà.',
            'filiere.required' => 'La filière est obligatoire.',
            'niveau.required'  => 'Le niveau est obligatoire.',
        ]);

        Classe::create($request->only('nom', 'filiere', 'niveau'));

        return back()->with('success', "Classe {$request->nom} ajoutée.");
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return back()->with('success', "Classe supprimée.");
    }
}