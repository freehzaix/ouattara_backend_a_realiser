<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeDocumentRequest;
use App\Models\TypeDocument;

class TypeDocumentController extends Controller
{


    public function index()
    {
        $typeDocuments = TypeDocument::all();

        return view('auth.type-document.index', compact('typeDocuments'));
    }

    //Fonction Post pour ajouter un guide dans la base de données
    public function create(TypeDocumentRequest $request)
    {
        $request->validated();
        
        if ($request->hasFile('fichier_scanner')) {
            // Obtenir le contenu du fichier PDF
            $contenu = file_get_contents($request->file('fichier_scanner')->path());
            $base64Data = base64_encode($contenu); // Conversion du fichier
            $nomFichier = $request->file('fichier_scanner')->getClientOriginalName();

            // Générer l'empreinte numérique avec la fonction MD5
            $empreinte = md5($contenu);

            // Récupérer une empreinte existante
            $existe = TypeDocument::where('empreinte_fichier', $empreinte)->exists();
            if ($existe) {
                // L'empreinte du fichier existe déjà dans la base de données -> on affiche un warning.
                return redirect()->route('type-document.index')->with('warning', 'Le document existe déjà dans la base de données.');
            } else {
                // L'empreinte du fichier n'existe pas dans la base de données
                $typeDocument = new TypeDocument();
                $typeDocument->nom_fichier = $nomFichier;
                $typeDocument->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $typeDocument->empreinte_fichier = $empreinte;
                $typeDocument->save();

                // Après avoir enregistré, faire la redirection
                return redirect()->route('type-document.index')->with('status', 'Le document a bien été enregistré.');
            }
        } else {
            return redirect()->back()->withInput()->withErrors(['fichier_scanner' => 'Veuillez sélectionner un fichier.']);
        }

    }

    //Delete TypeDocument
    public function delete($id)
    {
        $typeDocument = TypeDocument::find($id);

        if (!$typeDocument) {
            return redirect()->route('type-document.index')->with('error', 'Document non trouvé.');
        }

        $typeDocument->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('type-document.index')->with('status', 'Le document a bien été supprimé.');
    }


    public function show($id)
    {
        $document = TypeDocument::find($id);
        if ($document) {
            return view('auth.type-document.show', compact('document', 'id'));
        } else {
            return redirect()->route('type-document.index')->withErrors(['documentId' => 'Document non trouvé.']);
        }
    }

   

}
