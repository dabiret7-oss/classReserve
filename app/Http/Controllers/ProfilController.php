<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // Afficher le profil
    public function index()
    {
        return view('profil.index', ['user' => Auth::user()]);
    }

    // Modifier les informations
    public function updateInfos(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom'     => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
        ], [
            'nom.required'     => 'Le nom est obligatoire.',
            'prenoms.required' => 'Le prénom est obligatoire.',
            'email.required'   => 'L\'email est obligatoire.',
            'email.unique'     => 'Cet email est déjà utilisé.',
        ]);

        $user->update([
            'nom'     => $request->nom,
            'prenoms' => $request->prenoms,
            'email'   => $request->email,
        ]);

        return back()->with('success', 'Informations mises à jour.');
    }

    // Changer le mot de passe
    public function updatePassword(Request $request)
    {
        $request->validate([
            'ancien_mot_de_passe'  => 'required',
            'password'             => 'required|min:6|confirmed',
        ], [
            'ancien_mot_de_passe.required' => 'L\'ancien mot de passe est obligatoire.',
            'password.required'            => 'Le nouveau mot de passe est obligatoire.',
            'password.min'                 => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'           => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->ancien_mot_de_passe, Auth::user()->password)) {
            return back()->withErrors([
                'ancien_mot_de_passe' => 'L\'ancien mot de passe est incorrect.'
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Mot de passe modifié avec succès.');
    }
}