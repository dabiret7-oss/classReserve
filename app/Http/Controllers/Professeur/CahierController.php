<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Cahier;
use App\Models\CahierAcces;
use App\Models\CahierSeance;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CahierController extends Controller
{
    // Liste tous les cahiers + statut d'accès du prof
    public function index()
    {
    $userId = Auth::id();

    // Cahiers où le prof a un accès validé
    $cahiersAcces = CahierAcces::where('user_id', $userId)
        ->where('statut', 'valide')
        ->with(['cahier.classe', 'cahier.seances'])
        ->get();

    // Cahiers disponibles sans accès validé
    $cahiersAvecAcces = $cahiersAcces->pluck('cahier_id');

    $cahiersSansAcces = Cahier::where('statut', 'actif')
        ->whereNotIn('id', $cahiersAvecAcces)
        ->with(['classe', 'acces' => fn($q) => $q->where('user_id', $userId)])
        ->orderBy('annee_academique', 'desc')
        ->get();

    return view('professeur.cahiers.index', compact('cahiersAcces', 'cahiersSansAcces'));
    }

    // Demander l'accès à un cahier
    public function demanderAcces(Cahier $cahier)
    {
        if ($cahier->demandeEnCours(Auth::id())) {
            return back()->with('success', 'Vous avez déjà fait une demande pour ce cahier.');
        }

        CahierAcces::create([
            'cahier_id' => $cahier->id,
            'user_id'   => Auth::id(),
            'statut'    => 'en_attente',
        ]);

        return back()->with('success', 'Demande d\'accès envoyée. En attente de validation.');
    }

    // Voir et renseigner un cahier (si accès validé)
    public function show(Cahier $cahier)
    {
        if (!$cahier->accesValide(Auth::id())) {
            abort(403, 'Vous n\'avez pas accès à ce cahier.');
        }

        $matieres = Matiere::orderBy('nom')->get();
        $seances  = $cahier->seances()
            ->where('user_id', Auth::id())
            ->with('matiere')
            ->orderBy('date_seance')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(fn($s) => $s->matiere?->nom ?? 'Sans matière');

        return view('professeur.cahiers.show', compact('cahier', 'seances', 'matieres'));
    }

    // Ajouter une séance
    public function storeSeance(Request $request, Cahier $cahier)
    {
        if (!$cahier->accesValide(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'matiere_id'   => 'required|exists:matieres,id',
            'date_seance'  => 'required|date',
            'heure_debut'  => 'required',
            'heure_fin'    => 'required',
            'titre_module' => 'required|string|max:255',
            'contenu'      => 'required|string',
        ], [
            'matiere_id.required'   => 'Veuillez choisir une matière.',
            'date_seance.required'  => 'La date est obligatoire.',
            'heure_debut.required'  => 'L\'heure de début est obligatoire.',
            'heure_fin.required'    => 'L\'heure de fin est obligatoire.',
            'titre_module.required' => 'Le titre est obligatoire.',
            'contenu.required'      => 'Le contenu est obligatoire.',
        ]);

        CahierSeance::create([
            'cahier_id'    => $cahier->id,
            'user_id'      => Auth::id(),
            'matiere_id'   => $request->matiere_id,
            'date_seance'  => $request->date_seance,
            'heure_debut'  => $request->heure_debut,
            'heure_fin'    => $request->heure_fin,
            'titre_module' => $request->titre_module,
            'contenu'      => $request->contenu,
        ]);

        return back()->with('success', 'Séance ajoutée au cahier.');
    }


    public function extrairePdf(Request $request)
    {
    $request->validate([
        'pdf' => 'required|file|mimes:pdf|max:10240',
    ], [
        'pdf.required' => 'Veuillez sélectionner un fichier PDF.',
        'pdf.mimes'    => 'Le fichier doit être au format PDF.',
        'pdf.max'      => 'Le fichier ne doit pas dépasser 10 Mo.',
    ]);

    try {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($request->file('pdf')->getPathname());
        $texte  = $pdf->getText();

        // Extraire les titres — lignes courtes en majuscules ou commençant par Chapitre/Partie/Section
        $lignes = explode("\n", $texte);
        $titres = [];

        foreach ($lignes as $ligne) {
            $ligne = trim($ligne);

            if (empty($ligne) || strlen($ligne) < 3) continue;

            // Détecter titres : Chapitre X, Partie X, Section X, ou ligne courte en majuscules
            $estTitre = false;

            if (preg_match('/^(chapitre|partie|section|module|unité|leçon|thème|cours)\s+\w+/iu', $ligne)) {
                $estTitre = true;
            } elseif (preg_match('/^\d+[\.\-]\s+.{5,60}$/', $ligne)) {
                $estTitre = true;
            } elseif (strlen($ligne) <= 80 && $ligne === strtoupper($ligne) && preg_match('/[A-ZÀ-Ü]{3,}/', $ligne)) {
                $estTitre = true;
            }

            if ($estTitre && strlen($ligne) <= 150) {
                $titres[] = $ligne;
            }
        }

        // Dédupliquer et limiter à 50 titres
        $titres = array_unique($titres);
        $titres = array_slice(array_values($titres), 0, 50);

        if (empty($titres)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun titre détecté dans ce PDF. Essayez avec un PDF contenant des chapitres numérotés.',
            ]);
        }

        return response()->json([
            'success' => true,
            'titres'  => $titres,
            'count'   => count($titres),
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la lecture du PDF : ' . $e->getMessage(),
            ]);
        }
    }
}