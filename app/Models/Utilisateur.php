<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Utilisateur extends Authenticatable
{
    use HasFactory; 
    use Notifiable;
    protected $table = 'utilisateurs';
    protected $fillable = [
        'nom_uti', 'prenom', 'email_uti', 'mot_de_passe_uti', 'role_uti',
        'photodeprofil_uti', 'tel_uti', 'date_inscription_uti', 'ville', 'date_naissance'
    ];
    protected $hidden = [
        'mot_de_passe_uti', 'remember_token'
    ];
    public function getAuthPassword()
    {
        return $this->mot_de_passe_uti;
    }
    // Propriétés à caster en types spécifiques
    protected $casts = [
        'date_naissance' => 'datetime', // Exemple de conversion en type date
        'date_inscription_uti' => 'datetime',
        'mot_de_passe_uti' => 'encrypted', // Exemple de champ à crypter
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


    public function getEmailForPasswordReset()
    {
        return $this->email_uti;
    }

   

    // Optionally, disable remember token if not needed
    public function setRememberToken($value)
    {
        // Do nothing to prevent updating a non-existent column
    }

    public function getRememberToken()
    {
        return null; // Or return a custom column if you add one later
    }
    public function getRememberTokenName()
    {
        return ''; // Disable remember token column
    }

   
}
