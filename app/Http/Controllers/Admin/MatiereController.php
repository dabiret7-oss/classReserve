<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::orderBy('nom')->paginate(8);
        return view('admin.matieres.index', compact('matieres'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'nom' => 'required|string|max:255|unique:matieres',
    ], [
        'nom.required' => 'Le nom de la matière est obligatoire.',
        'nom.unique'   => 'Cette matière existe déjà.',
    ]);

    // Générer le code automatiquement
    // Ex: "Mathématiques Avancées" → "MATH-001"
    $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $request->nom), 0, 4));
    $count  = \App\Models\Matiere::count() + 1;
    $code   = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

    // S'assurer que le code est unique
    while (\App\Models\Matiere::where('code', $code)->exists()) {
        $count++;
        $code = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    Matiere::create([
        'nom'  => $request->nom,
        'code' => $code,
    ]);

    return back()->with('success', "Matière {$request->nom} ajoutée avec le code {$code}.");
    }

    


    public function edit(Matiere $matiere)
    {
    return view('admin.matieres.edit', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
    $request->validate([
        'nom' => 'required|string|max:255|unique:matieres,nom,' . $matiere->id,
    ], [
        'nom.required' => 'Le nom est obligatoire.',
        'nom.unique'   => 'Cette matière existe déjà.',
    ]);

    // Régénérer le code si le nom change
    if ($request->nom !== $matiere->nom) {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $request->nom), 0, 4));
        $count  = \App\Models\Matiere::count();
        $code   = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        while (\App\Models\Matiere::where('code', $code)->where('id', '!=', $matiere->id)->exists()) {
            $count++;
            $code = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
        $matiere->update(['nom' => $request->nom, 'code' => $code]);
    } else {
        $matiere->update(['nom' => $request->nom]);
    }

    return redirect()->route('admin.matieres.index')
        ->with('success', "Matière {$matiere->nom} modifiée avec succès.");
    }
}