<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory ;
    protected $fillable = [
        'titre_anno', 'description_anno', 'statut_anno',
        'date_publication_anno', 'logement_id', 'proprietaire_id'
    ];
    public function Logements(){
        $this->belongsTo(Logement::class);
    }
    public function Utilisateurs(){
        $this->belongsTo(Utilisateur::class);
    }
    public function Avis (){
        $this->hasMany(Avis::class);
    }
    public function Favori (){
        $this->hasMany(Favori::class);
    }
}
