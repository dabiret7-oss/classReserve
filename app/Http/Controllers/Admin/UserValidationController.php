<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserValidationController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('role', 'professeur')
            ->where('statut', 'en_cours')
            ->latest()
            ->get();
    
        $toutProfesseur = User::where('role', 'professeur')
            ->whereIn('statut', ['valide', 'rejete', 'desactive'])
            ->latest()
            ->paginate(10);
    
        return view('admin.users.index', compact('pendingUsers', 'toutProfesseur'));
    }

    public function validateUser(User $user)
    {
        $user->update(['statut' => 'valide']);
        return back()->with('success', "Le compte de {$user->nom} a été validé.");
    }

    public function rejectUser(User $user)
    {
        $user->update(['statut' => 'rejete']);
        return back()->with('success', "Le compte de {$user->nom} a été rejeté.");
    }

    // Désactiver ou réactiver un professeur
    public function toggleDesactive(User $user)
    {
        if ($user->statut === 'desactive') {
            $user->update(['statut' => 'valide']);
            return back()->with('success', "Le compte de {$user->nom} a été réactivé.");
        }

        $user->update(['statut' => 'desactive']);
        return back()->with('success', "Le compte de {$user->nom} a été désactivé.");
    }

    // Supprimer le compte (soft delete)
    public function destroy(User $user)
    {
        $user->delete(); // soft delete — données conservées
        return back()->with('success', "Le compte de {$user->nom} a été supprimé.");
    }

    // Restaurer un compte supprimé
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', "Le compte a été restauré.");
    }
}