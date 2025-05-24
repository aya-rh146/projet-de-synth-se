<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Logement extends Model
{
    use HasFactory ; 
    protected $fillable = [
        'prix_log','localisation_log', 'date_creation_log',
        'type_log','equipements', 'photos',
        'etage', 'nombre_colocataire_log',
        'ville', 'views'
    ];
    protected $casts = [
        'equipements' => 'array',
        'photos' => 'array', // باش Laravel يعرف بلي photos هو array
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }
    public function Annonce(){
        return $this->hasMany(Annonce::class, 'logement_id');
    }


}
