<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    use HasFactory;

    public static function verifierSiEmpreinteExiste($empreinte){
        $existe = Tampon::where('empreinte_fichier', $empreinte)->exists();
        if($existe){
            return true;
        }
        return false;
    }

    public static function genererEmpreinte($valeur){
        return md5($valeur);
    }

    public static function sauvegarderTampon($empreinte, $colonneTab = 'empreinte_fichier')
    {
        $tampon = new Tampon();
        $tampon->$colonneTab = $empreinte;
        $tampon->save();
    }

    public static function genererNomDuFichier($valeurSaisieParUtilisateur){
        $nomConvertir= str_replace(" ", '_', $valeurSaisieParUtilisateur);
        return $nomFichier = $nomConvertir.'.pdf';
    }

}
