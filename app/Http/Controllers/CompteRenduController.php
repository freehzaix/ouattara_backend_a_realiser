<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompteRenduRequest;
use App\Models\CompteRendu;
use App\Models\Tampon;

class CompteRenduController extends Controller
{


    public function index()
    {
        $compteRendus = CompteRendu::all();

        return view('auth.compte-rendu.index', compact('compteRendus'));
    }

    //Fonction Post pour ajouter un compte rendu dans la base de données
    public function create(CompteRenduRequest $request)
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
            $existe = Tampon::where('empreinte_fichier', $empreinte)->exists();
            if ($existe) {
                // L'empreinte du fichier existe déjà dans la base de données -> on affiche un warning.
                return redirect()->route('compte-rendu.index')->with('warning', 'Le compte rendu existe déjà dans la base de données.');
            } else {
                // L'empreinte du fichier n'existe pas dans la base de données
                $compteRendu = new CompteRendu();
                $compteRendu->nom_fichier = strtolower($request->nom_fichier) . '.pdf';
                $compteRendu->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $compteRendu->empreinte_fichier = $empreinte;
                $compteRendu->save();

                //Enregistrer les empreinte de fichier
                $tampon = new Tampon();
                $tampon->empreinte_fichier = $empreinte;
                $tampon->save();

                // Après avoir enregistré, faire la redirection
                return redirect()->route('compte-rendu.index')->with('status', 'Le compte rendu a bien été enregistré.');
            }
        } else {
            return redirect()->back()->withInput()->withErrors(['fichier_scanner' => 'Veuillez sélectionner un fichier.']);
        }

    }

    //Delete Compte Rendu
    public function delete($id)
    {
        $compteRendu = CompteRendu::find($id);

        if (!$compteRendu) {
            return redirect()->route('compte-rendu.index')->with('error', 'Compte rendu non trouvé.');
        }

        $compteRendu->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('compte-rendu.index')->with('status', 'Le compte rendu a bien été supprimé.');
    }


    public function show($id)
    {
        $compteRendu = CompteRendu::find($id);
        if ($compteRendu) {
            return view('auth.compte-rendu.show', compact('compteRendu', 'id'));
        } else {
            return redirect()->route('compte-rendu.index')->withErrors(['documentId' => 'C ompte rendu non trouvé.']);
        }
    }

}
