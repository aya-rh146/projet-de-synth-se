<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory; 
    protected $fillable = [
        'nom_uti', 'prenom', 'email_uti', 'mot_de_passe_uti', 'role_uti',
        'photodeprofil_uti', 'tel_uti', 'date_inscription_uti', 'ville', 'date_naissance'
    ];
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function expeditions()
    {
        return $this->hasMany(Conversation::class, 'expediteur_id');
    }

    public function receptions()
    {
        return $this->hasMany(Conversation::class, 'destinataire_id');
    }
    public function reservations_pro()
    {
        return $this->hasMany(Reservation::class, 'proprietaire_id');
    }
    public function reservations_loca()
    {
        return $this->hasMany(Reservation::class, 'locataire_id');
    }
    
    public function Annonce()
    {
        return $this->hasMany(Annonce::class);
    }
    public function Avis (){
        $this->hasMany(Avis::class);
    }
    public function Favori (){
        $this->hasMany(Favori::class);
    }


    
}
