<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conclusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_fichier',
        'fichier_scanner',
        'empreinte_fichier',
        'pertinence',
        'estLu',
    ];


}
