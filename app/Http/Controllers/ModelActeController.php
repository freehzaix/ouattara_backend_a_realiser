<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModelActeRequest;
use App\Models\ModelActe;

class ModelActeController extends Controller
{


    public function index()
    {
        $modelActes = ModelActe::all();

        return view('auth.model-acte.index', compact('modelActes'));
    }

    //Fonction Post pour ajouter un modèle acte dans la base de données
    public function create(ModelActeRequest $request)
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
            $existe = ModelActe::where('empreinte_fichier', $empreinte)->exists();
            if ($existe) {
                // L'empreinte du fichier existe déjà dans la base de données -> on affiche un warning.
                return redirect()->route('model-acte.index')->with('warning', 'Le document existe déjà dans la base de données.');
            } else {
                // L'empreinte du fichier n'existe pas dans la base de données
                $modelActe = new ModelActe();
                $modelActe->nom_fichier = $nomFichier;
                $modelActe->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $modelActe->empreinte_fichier = $empreinte;
                $modelActe->save();

                // Après avoir enregistré, faire la redirection
                return redirect()->route('model-acte.index')->with('status', 'Le document a bien été enregistré.');
            }
        } else {
            return redirect()->back()->withInput()->withErrors(['fichier_scanner' => 'Veuillez sélectionner un fichier.']);
        }

    }

    //Delete ModelActe
    public function delete($id)
    {
        $modelActe = ModelActe::find($id);

        if (!$modelActe) {
            return redirect()->route('model-acte.index')->with('error', 'Document non trouvé.');
        }

        $modelActe->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('model-acte.index')->with('status', 'Le document a bien été supprimé.');
    }


    public function show($id)
    {
        $document = ModelActe::find($id);
        if ($document) {
            return view('auth.model-acte.show', compact('document', 'id'));
        } else {
            return redirect()->route('type-document.index')->withErrors(['documentId' => 'Document non trouvé.']);
        }
    }

}
