<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Messag extends Model
{  
    use HasFactory;
    protected $fillable = [
    'contenu_mess', 'date_mess', 'conversation_id' , 'conversation_id'
];
public function Conversations(){
    $this->belongsTo(Conversation::class);
}
}
