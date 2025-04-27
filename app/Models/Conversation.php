<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Conversation extends Model
{
    use HasFactory ;
    protected $fillable = [
        'date_debut_conv', 'expediteur_id', 'destinataire_id'
    ];

    public function expediteurs(){
        $this->belongsTo(Utilisateur::class);
    }
    public function destinataires(){
        $this->belongsTo(Utilisateur::class);
    }
    public function mesageriers(){
        $this->hasMany(Messag::class);
    }
}
