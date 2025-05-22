<?php

namespace App\Http\Controllers\locataire;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Http\Controllers\Controller;

class AnnonceLocataireController extends Controller
{
    // ✅ Affichage des annonces
    public function index()
    {
        try {
            $annonces = Annonce::latest()->paginate(6);
            return view('locataire.annonceslocataire', compact('annonces'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la récupération des annonces: ' . $e->getMessage());
        }
    }

    // ✅ Enregistrement d'une nouvelle annonce (avec AJAX support)
    public function store(Request $request)
{
    $request->validate([
        'titre_anno' => 'required|string|max:255',
        'description_anno' => 'required|string',
        'type_log' => 'required|in:studio,appartement,maison',
        'prix_log' => 'required|numeric',
        'ville' => 'required|string|max:255',
        'nombre_colocataire_log' => 'required|integer|min:1',
        'localisation_log' => 'required|string|max:255',
    ]);
    try {
        $logement = new \App\Models\Logement();
        $logement->prix_log = $request->prix_log;
        $logement->localisation_log = $request->localisation_log;
        $logement->date_creation_log = now();
        $logement->type_log = $request->type_log;
        $logement->nombre_colocataire_log = $request->nombre_colocataire_log;
        $logement->ville = $request->ville;
        dd($logement->toArray()); // هنا غادي تعرض البيانات قبل الـ save

        $logement->save();

        $annonce = new Annonce();
        $annonce->titre_anno = $request->titre_anno;
        $annonce->description_anno = $request->description_anno;
        $annonce->date_publication_anno = now();
        $annonce->logement_id = $logement->id;
        $annonce->proprietaire_id = auth()->id();
        dd($annonce->toArray()); // هنا غادي تعرض البيانات قبل الـ save

        $annonce->save();

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Annonce créée avec succès.',
                'annonce' => $annonce->load('logement'),
            ]);
        }
        return redirect()->back()->with('success', 'Annonce créée avec succès.');
    } catch (\Exception $e) {
        dd($e->getMessage()); // هنا غادي تعرض الخطأ إذا كان فيه
        if ($request->ajax()) {
            return response()->json(['message' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
        return redirect()->back()->with('error', 'Erreur lors de la création: ' . $e->getMessage());
    }
}


    // ✅ Suppression d'annonce
    public function destroy($id)
    {
        try {
            $annonce = Annonce::findOrFail($id);

            // Sécurité : vérifier si l'annonce appartient à l'utilisateur connecté
            if ($annonce->proprietaire_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => "Vous n'avez pas le droit de supprimer cette annonce."
                ], 403);
            }

            $annonce->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Annonce supprimée avec succès.'
                ]);
            }

            return redirect()->back()->with('success', 'Annonce supprimée.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'annonce.',
                    'error' => $e->getMessage()
                ], 422);
            }
            return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'annonce: ' . $e->getMessage());
        }
    }

    // ✅ Édition d'une annonce (retourne la vue d'édition)
    public function edit($id)
    {
        try {
            $annonce = Annonce::findOrFail($id);

            // Sécurité : s'assurer que c’est bien l’utilisateur qui possède l’annonce
            if ($annonce->proprietaire_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Accès non autorisé à cette annonce.');
            }

            return view('locataire.editannonce', compact('annonce'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la récupération de l\'annonce: ' . $e->getMessage());
        }
    }
}

