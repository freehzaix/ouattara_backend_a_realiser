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

        $guide = new Guide();
        $guide->nom_fichier = $request->nom_fichier;
        //ajouter le repertoire "storage" pour l'insertion de la base de données
        $guide->fichier_scanner = "storage". $replace_path;  
        $guide->empreinte_fichier = Hash::make($path);          
        $guide->save();

        //Après avoir enregistrer, faire la rediretion
        return redirect()->route('guide.index')->with('status', 'Le document a bien été enregistré.');

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
