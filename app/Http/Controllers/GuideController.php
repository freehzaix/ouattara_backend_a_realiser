<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuideRequest;
use App\Models\Guide;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuideController extends Controller
{
    
    public function index()
    {
        $guides = Guide::all();

        return view('auth.guide.index', compact('guides'));
    }

    //Fonction Post pour ajouter un guide dans la base de données
    public function create(GuideRequest $request)
    {
        $request->validated();

        //Générer l'empreinte numérique avec la fonction MD5
        $empreinte = md5($request->hasFile('fichier_scanner'));

        //Récupérer une empreinte existant
        $existe = Guide::where('empreinte_fichier', $empreinte)->exists();
        if ($existe) {
            // L'empreinte du fichier existe déjà dans la base de données -> on affiche un warning.
            return redirect()->route('guide.index')->with('warning', 'Le document existe déjà dans la base de données.');
        } else {
            $ancienFichier = "";
            //On va remplacer le repertoire "storage" par "public" concernant
            //l'ancien fichier du document
            $ancienFichier = str_replace('storage/', 'public/', $ancienFichier);

            if ($ancienFichier && Storage::exists($ancienFichier)) {
                Storage::delete($ancienFichier);
            }

            //Stocké l'image dans la variable $path dans
            //le répertoire /storage/public/upload
            //et créer un lien du repertoire storage vers public
            $path = $request->file('fichier_scanner')->store('public/upload');
            //Rétirer le repertoire public avant de mettre dans la base de données
            $replace_path = str_replace('public', '', $path);
            // L'empreinte du fichier n'existe pas dans la base de données
            $guide = new Guide();
            $guide->nom_fichier = $request->nom_fichier;
            //ajouter le repertoire "storage" pour l'insertion de la base de données
            $guide->fichier_scanner = "storage" . $replace_path;
            $guide->empreinte_fichier = $empreinte;
            $guide->save();
            //Après avoir enregistrer, faire la rediretion
            return redirect()->route('guide.index')->with('status', 'Le document a bien été enregistré.');
        }
    }

    //Delete Guide
    public function delete($id)
    {
        $guide = Guide::find($id); //Récupéré le document
        
        $ancienFichier = $guide->fichier_scanner;
        //On va remplacer le repertoire "storage" par "public" concernant
        //l'ancien fichier du document
        $ancienFichier = str_replace('storage/', 'public/', $ancienFichier);
        
        if ($ancienFichier && Storage::exists($ancienFichier)) {
            Storage::delete($ancienFichier);
        }

        $guide->delete();
        //Après avoir supprimer, faire la rediretion
        return redirect()->route('guide.index')->with('status', 'Le document a bien été supprimé.');
    }
    
}
