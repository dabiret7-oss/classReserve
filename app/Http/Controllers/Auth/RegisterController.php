<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'nom' => 'required|string|max:255',
                'prenoms' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ],
            [
                'nom.required' => 'le nom est obligatoire.',
                'prenoms.required' => 'le prenom est obligatoire.',
                'email.required' => 'le email est obligatoire.',
                'email.unique' => 'cette adresse email est deja utilisée.',
                'password.min' => 'le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'les mots de passe ne correspondent pas.',
            ]
        );

        User::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'professeur',
            'statut' => 'en_cours',
        ]);

        return redirect()->route('login')->with('success', 'inscription envoyée votre compte est en attente de validation');
    }
}
