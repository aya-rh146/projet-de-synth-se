<?php

namespace App\Http\Controllers\proprietaire;

use Illuminate\Http\Request; // Add this import if needed for Request
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // Import the base Controller class
use App\Models\Utilisateur;
use App\Models\Annonce;
use App\Models\Logement;
use App\Models\Reservation;

class AccueilProprietaireController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Faut se connecter dabor!');
        }

        $user = Auth::user();
        $userId = $user->id_util;

        // إحصائيات الحجوزات (للمالك)
        $totalRequests = Reservation::where('proprietaire_id', $userId)->count(); // كل الحجوزات
        $confirmedBookings = Reservation::where('proprietaire_id', $userId)->where('statut_res', 'confirmé')->count(); // الحجوزات المؤكدة
        $rejectedBookings = Reservation::where('proprietaire_id', $userId)->where('statut_res', 'refusé')->count(); // الحجوزات المرفوضة
        $completedBookings = Reservation::where('proprietaire_id', $userId)->where('statut_res', 'terminé')->count(); // الحجوزات المنتهية

        // الإقامات المضافة حديثا (للمالك)
        $latestLogements = Annonce::with('logement')
            ->where('proprietaire_id', $userId)
            ->where('statut_anno', 'disponible')
            ->orderBy('date_publication_anno', 'desc')
            ->take(3)
            ->get();

        // FAQ وإحصائيات (ساكنة حاليا)
        $faqs = [
            ['question' => "Shnu smiya dyal FindStay?", 'answer' => 'FindStay howa plateforme li ysa3dek tbdel l hébergements b zwin...'],
            ['question' => 'Kifash ncree compte?', 'answer' => "Zd zwin l button 'S’inscrire' w smi l formulaire..."],
            ['question' => 'Kifash nbdel wlla nskt reservation?', 'answer' => 'Tgd tbdel wlla tmsk l reservation mn profil dyalk...'],
            ['question' => 'Kayn application mobile?', 'answer' => 'Eee, kayn application l iOS w Android...'],
        ];

        $stats = [
            ['label' => 'Logements m3rbin b Maroc', 'value' => '93'],
            ['label' => 'Jeunes mkrat', 'value' => '1000'],
            ['label' => 'Utilisateurs m9awdin', 'value' => '800+'],
            ['label' => 'Logements mawjudin', 'value' => '90+'],
        ];

        return view('proprietaire.accueilproprietaire', compact('user', 'totalRequests', 'confirmedBookings', 'rejectedBookings', 'completedBookings', 'latestLogements', 'faqs', 'stats'));
        
        
        $totalRequests = Statistique::where('id_utilisateur', $user->id_uti)->value('nombre_reservation');
        $rejectedBookings = Statistique::where('id_utilisateur', $user->id_uti)->value('nombre_reservation_accepter');
        $confirmedBookings = Message::whereHas('conversation', function($query) use ($user) {
            $query->where('expediteur_id', $user->id_uti)
                ->orWhere('destinataire_id', $user->id_uti);
        })->count();
    }
    
}