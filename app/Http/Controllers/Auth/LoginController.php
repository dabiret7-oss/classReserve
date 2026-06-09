<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm(){
        return view('auth.login');
    }


    public function login(Request $request){
        $credentials= $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ],[
            'email.required'=>'email est obligatoire',
            'password.required'=>'le mot de passe est obligatoire',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email'=>'identifiant incorrect',
            ])->withInput($request->only('email'));
        }

        $user= Auth::user();
        if ($user->role==='professeur' && $user->statut==='en_cours') {
            Auth::logout();
            return back()->withErrors(['email'=>'votre compte est en attente de validation']);
        }

        if ($user->role==='professeur' && $user->statut==='rejete') {
            Auth::logout();
            return back()->withErrors(['email'=>'votre demande d\'inscription à été rejeté ']);
        }

        if ($user->role === 'professeur' && $user->statut === 'desactive') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l\'administrateur.'
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended($user->isAdmin()? route('admin.dashboard'):route('professeur.dashboard'));
    }
    
}
