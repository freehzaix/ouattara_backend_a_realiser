<?php

namespace App\Http\Controllers;

use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TypeDocumentController extends Controller
{
    
    
    public function index()
    {
        $typeDocuments = TypeDocument::all();

        return view('auth.type-document.index', compact('typeDocuments'));
    }

    //Fonction Post pour ajouter un guide dans la base de données
    public function create(Request $request)
    {
        $request->validate([
            'nom_fichier' => 'required',
            'fichier_scanner' => 'required',
        ]);
        
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

        $typeDocuments = new TypeDocument();
        $typeDocuments->nom_fichier = $request->nom_fichier;
        //ajouter le repertoire "storage" pour l'insertion de la base de données
        $typeDocuments->fichier_scanner = "storage". $replace_path;  
        $typeDocuments->empreinte_fichier = Hash::make($path);          
        $typeDocuments->save();

        //Après avoir enregistrer, faire la rediretion
        return redirect()->route('type-document.index')->with('status', 'Le document a bien été enregistré.');

    }

    //Delete TypeDocument
    public function delete($id)
    {
        $typeDocument = TypeDocument::find($id); //Récupéré le document
        
        $ancienFichier = $typeDocument->fichier_scanner;
        //On va remplacer le repertoire "storage" par "public" concernant
        //l'ancien fichier du document
        $ancienFichier = str_replace('storage/', 'public/', $ancienFichier);
        
        if ($ancienFichier && Storage::exists($ancienFichier)) {
            Storage::delete($ancienFichier);
        }

        $typeDocument->delete();
        //Après avoir supprimer, faire la rediretion
        return redirect()->route('type-document.index')->with('status', 'Le document a bien été supprimé.');
    }
    

}
