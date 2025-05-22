<?php

namespace App\Http\Controllers\proprietaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Ajout de l'import manquant pour Auth

class annonceproprietaireController extends Controller
{
    public function index()
    {
        try {
            // Récupérer l'ID de l'utilisateur connecté
            $userId = Auth::id();
            
            if (!$userId) {
                Log::error('Utilisateur non authentifié tentant d\'accéder aux annonces');
                return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à vos annonces');
            }
            
            // Utiliser id_uti au lieu de proprietaire_id pour correspondre à votre structure de base de données
            $annonces = Annonce::where('proprietaire_id', $userId)
                          ->orderBy('date_publication_anno', 'desc')
                          ->paginate(6);  // Utiliser paginate() au lieu de get()
            
            // Ajouter un log pour déboguer
            Log::info('Annonces récupérées pour l\'utilisateur: ' . $userId . ', Nombre: ' . $annonces->count());
            
            return view('proprietaire.annoncesproprietaire', compact('annonces'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des annonces: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la récupération de vos annonces: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Début store method pour enregistrer une annonce.', ['request' => $request->all()]);

            // Vérification de l'authentification
            if (!Auth::check()) {
                Log::error('Tentative d\'enregistrement d\'annonce sans authentification');
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour créer une annonce'
                ], 401);
            }

            // Validation
            $validated = $request->validate([
                'titre_anno' => 'required|string|max:255',
                'prix_log' => 'required|numeric|min:0',
                'localisation_log' => 'required|string|max:255',
                'equipement' => 'nullable|array',
                'photos' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ], [
                // gestion des erreurs 
                'titre_anno.required' => 'Le titre de l\'annonce est obligatoire',
                'prix_log.required' => 'Le prix_log est obligatoire',
                'prix_log.numeric' => 'Le prix_log doit être un nombre',
                'prix_log.min' => 'Le prix_log doit être positif',
                'localisation_log.required' => 'La localisation_log est obligatoire',
                'photos.mimes' => 'Le fichier doit être une image (jpg, jpeg, png)',
                'photos.max' => 'La taille de l\'image ne doit pas dépasser 2Mo',
            ]);

            Log::info('Validation réussie.');

            // Upload photo
            $photoPath = null;
            if ($request->hasFile('photos') && $request->file('photos')->isValid()) {
                Log::info('Photo trouvée, enregistrement en cours.');
                try {
                    $photoPath = $request->file('photos')->store('annonces', 'public');
                    Log::info('Photo enregistrée à: ' . $photoPath);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'upload de la photo: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'upload de la photo: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                Log::info('Pas de photo ou photo invalide.');
            }

            // Enregistrer l'annonce
            DB::beginTransaction();
            try {
                $data = [
                    'titre_anno' => $request->titre_anno,
                    'prix_log' => $request->prix_log,
                    'localisation_log' => $request->localisation_log,
                    'equipement' => json_encode($request->equipement ?? []),
                    'photos' => $photoPath,
                    'date_publication_anno' => now(),
                    'id_uti' => Auth::id(), // Ajout de l'ID utilisateur
                ];
                
                $annonce = Annonce::create($data);
                DB::commit();
                Log::info('Annonce enregistrée avec succès, ID: ' . $annonce->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Annonce enregistrée avec succès!',
                    'annonce' => $annonce
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors de l\'enregistrement dans la base de données: ' . $e->getMessage());
                
                // Si une photo a été uploadée, la supprimer
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Échec de validation: ', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Problème de validation, vérifiez les données!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Problème lors de l\'enregistrement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement de l\'annonce: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Début de suppression de l\'annonce ID: ' . $id);

            // Vérification de l'authentification
            if (!Auth::check()) {
                Log::error('Tentative de suppression d\'annonce sans authentification');
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour supprimer une annonce'
                ], 401);
            }

            $annonce = Annonce::findOrFail($id);
            Log::info('Annonce trouvée: ' . $annonce->id);

            // Vérifier que l'utilisateur est bien le propriétaire de l'annonce
            if ($annonce->id_uti != Auth::id()) {
                Log::warning('Tentative de suppression d\'une annonce par un utilisateur non autorisé. User ID: ' . Auth::id() . ', Annonce ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à supprimer cette annonce'
                ], 403);
            }

            DB::beginTransaction();
            try {
                if ($annonce->photos) {
                    Log::info('Suppression de la photo: ' . $annonce->photos);
                    Storage::disk('public')->delete($annonce->photos);
                    Log::info('Photo supprimée avec succès.');
                } else {
                    Log::info('Pas de photo pour cette annonce.');
                }

                $annonce->delete();
                DB::commit();
                Log::info('Annonce supprimée avec succès.');

                return response()->json([
                    'success' => true,
                    'message' => 'Annonce supprimée avec succès!'
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Annonce non trouvée ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Annonce non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Problème lors de la suppression de l\'annonce ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'annonce: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            // Vérification de l'authentification
            if (!Auth::check()) {
                Log::error('Tentative d\'édition d\'annonce sans authentification');
                return redirect()->route('login')->with('error', 'Veuillez vous connecter pour modifier une annonce');
            }

            $annonce = Annonce::findOrFail($id);
            
            // Vérifier que l'utilisateur est bien le propriétaire de l'annonce
            if ($annonce->id_uti != Auth::id()) {
                Log::warning('Tentative d\'édition d\'une annonce par un utilisateur non autorisé. User ID: ' . Auth::id() . ', Annonce ID: ' . $id);
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce');
            }
            
            return view('proprietaire.modifierannonceproprietaire', compact('annonce'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Annonce non trouvée ID: ' . $id);
            return redirect()->back()->with('error', 'Annonce non trouvée');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'annonce ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la récupération de l\'annonce: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Vérification de l'authentification
            if (!Auth::check()) {
                Log::error('Tentative de mise à jour d\'annonce sans authentification');
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour modifier une annonce'
                ], 401);
            }

            $request->validate([
                'titre_anno' => 'required|string|max:255',
                'prix_log' => 'required|numeric',
                'localisation_log' => 'required|string|max:255',
                'equipement' => 'nullable|array',
                'photos' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
            ], [
                'titre_anno.required' => 'Le titre de l\'annonce est obligatoire',
                'prix_log.required' => 'Le prix_log est obligatoire',
                'prix_log.numeric' => 'Le prix_log doit être un nombre',
                'localisation_log.required' => 'La localisation_log est obligatoire',
                'photos.mimes' => 'Le fichier doit être une image (jpg, jpeg, png)',
                'photos.max' => 'La taille de l\'image ne doit pas dépasser 2Mo',
            ]);

            $annonce = Annonce::findOrFail($id);
            
            // Vérifier que l'utilisateur est bien le propriétaire de l'annonce
            if ($annonce->id_uti != Auth::id()) {
                Log::warning('Tentative de mise à jour d\'une annonce par un utilisateur non autorisé. User ID: ' . Auth::id() . ', Annonce ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à modifier cette annonce'
                ], 403);
            }

            DB::beginTransaction();
            try {
                $photoPath = $annonce->photos;
                if ($request->hasFile('photos') && $request->file('photos')->isValid()) {
                    if ($annonce->photos) {
                        Storage::disk('public')->delete($annonce->photos);
                    }
                    $photoPath = $request->file('photos')->store('annonces', 'public');
                }

                $annonce->update([
                    'titre_anno' => $request->titre_anno,
                    'prix_log' => $request->prix_log,
                    'localisation_log' => $request->localisation_log,
                    'equipement' => json_encode($request->equipement ?? []),
                    'photos' => $photoPath,
                ]);
                
                DB::commit();
                Log::info('Annonce mise à jour avec succès, ID: ' . $annonce->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Annonce mise à jour avec succès.',
                    'annonce' => $annonce
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Annonce non trouvée ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Annonce non trouvée'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Échec de validation pour la mise à jour: ', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Problème de validation, vérifiez les données!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'annonce ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }
}

