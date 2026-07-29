<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import');
    }

    // ── IMPORT SALLES ──
    public function importSalles(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'fichier.required' => 'Veuillez sélectionner un fichier CSV.',
            'fichier.mimes'    => 'Le fichier doit être au format CSV.',
        ]);

        $fichier  = $request->file('fichier');
        $handle   = fopen($fichier->getPathname(), 'r');
        $ligne    = 0;
        $importes = 0;
        $erreurs  = [];

        // Ignorer l'en-tête
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $ligne++;

            if (count($data) < 2) {
                $erreurs[] = "Ligne {$ligne} : format invalide (nom, niveau requis)";
                continue;
            }

            $nom    = trim($data[0]);
            $niveau = trim($data[1]);

            if (empty($nom) || empty($niveau)) {
                $erreurs[] = "Ligne {$ligne} : nom ou niveau manquant";
                continue;
            }

            $niveauxValides = ['RDC', 'R+1', 'R+2', 'R+3'];
            if (!in_array($niveau, $niveauxValides)) {
                $erreurs[] = "Ligne {$ligne} : niveau invalide '{$niveau}' (valeurs: RDC, R+1, R+2, R+3)";
                continue;
            }

            // Ignorer si la salle existe déjà
            if (Salle::where('nom', $nom)->exists()) {
                $erreurs[] = "Ligne {$ligne} : salle '{$nom}' déjà existante — ignorée";
                continue;
            }

            Salle::create([
                'nom'    => $nom,
                'niveau' => $niveau,
                'statut' => 'active',
            ]);
            $importes++;
        }

        fclose($handle);

        $msg = "{$importes} salle(s) importée(s) avec succès.";
        if (!empty($erreurs)) {
            $msg .= ' ' . count($erreurs) . ' ligne(s) ignorée(s).';
        }

        return back()->with('success', $msg)
                     ->with('erreurs_import', $erreurs);
    }

    // ── IMPORT MATIÈRES ──
    public function importMatieres(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'fichier.required' => 'Veuillez sélectionner un fichier CSV.',
            'fichier.mimes'    => 'Le fichier doit être au format CSV.',
        ]);

        $fichier  = $request->file('fichier');
        $handle   = fopen($fichier->getPathname(), 'r');
        $ligne    = 0;
        $importes = 0;
        $erreurs  = [];

        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $ligne++;

            if (count($data) < 1) {
                $erreurs[] = "Ligne {$ligne} : format invalide (nom requis)";
                continue;
            }

            $nom = trim($data[0]);

            if (empty($nom)) {
                $erreurs[] = "Ligne {$ligne} : nom manquant";
                continue;
            }

            if (Matiere::where('nom', $nom)->exists()) {
                $erreurs[] = "Ligne {$ligne} : matière '{$nom}' déjà existante — ignorée";
                continue;
            }

            // Générer le code automatiquement
            $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nom), 0, 4));
            $count  = Matiere::count() + 1;
            $code   = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            while (Matiere::where('code', $code)->exists()) {
                $count++;
                $code = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }

            Matiere::create(['nom' => $nom, 'code' => $code]);
            $importes++;
        }

        fclose($handle);

        $msg = "{$importes} matière(s) importée(s) avec succès.";
        if (!empty($erreurs)) {
            $msg .= ' ' . count($erreurs) . ' ligne(s) ignorée(s).';
        }

        return back()->with('success', $msg)
                     ->with('erreurs_import', $erreurs);
    }

    // ── IMPORT PROFESSEURS ──
    public function importProfesseurs(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'fichier.required' => 'Veuillez sélectionner un fichier CSV.',
            'fichier.mimes'    => 'Le fichier doit être au format CSV.',
        ]);

        $fichier  = $request->file('fichier');
        $handle   = fopen($fichier->getPathname(), 'r');
        $ligne    = 0;
        $importes = 0;
        $erreurs  = [];

        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $ligne++;

            if (count($data) < 4) {
                $erreurs[] = "Ligne {$ligne} : format invalide (nom, prenoms, email, password requis)";
                continue;
            }

            $nom     = trim($data[0]);
            $prenoms = trim($data[1]);
            $email   = trim($data[2]);
            $password = trim($data[3]);

            if (empty($nom) || empty($prenoms) || empty($email) || empty($password)) {
                $erreurs[] = "Ligne {$ligne} : champ manquant";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "Ligne {$ligne} : email invalide '{$email}'";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $erreurs[] = "Ligne {$ligne} : email '{$email}' déjà utilisé — ignoré";
                continue;
            }

            User::create([
                'nom'      => $nom,
                'prenoms'  => $prenoms,
                'email'    => $email,
                'password' => Hash::make($password),
                'role'     => 'professeur',
                'statut'   => 'valide',
            ]);
            $importes++;
        }

        fclose($handle);

        $msg = "{$importes} professeur(s) importé(s) avec succès.";
        if (!empty($erreurs)) {
            $msg .= ' ' . count($erreurs) . ' ligne(s) ignorée(s).';
        }

        return back()->with('success', $msg)
                     ->with('erreurs_import', $erreurs);
    }



        public function telechargerModele(string $type)
    {
        $modeles = [
            'salles'      => ["nom,niveau", "Salle 10,RDC", "Amphi B,R+1"],
            'matieres'    => ["nom", "Mathématiques", "Algorithmique"],
            'professeurs' => ["nom,prenoms,email,password", "TRAORE,Issouf,i.traore@hetec.edu,Pass123"],
        ];

        if (!isset($modeles[$type])) {
            abort(404);
        }

        $contenu = implode("\n", $modeles[$type]);

        return response($contenu, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$type}.csv",
        ]);
    }
}