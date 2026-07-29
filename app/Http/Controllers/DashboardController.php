<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $Professeur=User::where('role','professeur')->where('statut','en_cours')->count();
        return view('admin.dashboard',compact('Professeur'));
    }
}
