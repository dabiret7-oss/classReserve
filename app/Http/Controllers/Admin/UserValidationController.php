<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserValidationController extends Controller
{
    public function index(){
        $en_coursUser=User::where('role', 'professeur')
        ->where('statut','en_cours')
        ->latest()
        ->get();
        $toutProfesseur=User::where('role', 'professeur')
        ->whereIn('statut',['valide', 'rejete' ])
        ->latest()->paginate(10);
        return view('admin.users.index',compact('en_coursUser','toutProfesseur'));
    }


    public function validateUser(User $user){
        $user->update(['statut'=>'valide']);
        return back()->with('success', "le compte de {$user->nom} a été validé");
    }

    public function rejectUser(User $user){
        $user->update(['statut'=>'rejete']);
        return back()->with('success', "le compte de {$user->nom} a été rejeté");
    }
}
