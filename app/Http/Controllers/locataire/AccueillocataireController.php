<?php
namespace App\Http\Controllers\locataire;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccueilLocataireController extends Controller
{
    public function index()
    {
        return view('locataire.accueillocataire');
    }
}