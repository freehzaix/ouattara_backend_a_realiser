<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConclusionRequest;
use App\Models\Conclusion;
use App\Models\Tampon;
use Illuminate\Http\Request;

class ConclusionController extends Controller
{


    public function index()
    {
        $conclusions = Conclusion::all();

        return view('auth.conclusion.index', compact('conclusions'));
    }

    //Fonction Post pour ajouter une conclusion dans la base de données
    public function create(ConclusionRequest $request)
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
                return redirect()->route('conclusion.index')->with('warning', 'Le document existe déjà dans la base de données.');
            } else {
                // L'empreinte du fichier n'existe pas dans la base de données
                $conclusion = new Conclusion();
                $conclusion->nom_fichier = strtolower($request->nom_fichier) . '.pdf';
                $conclusion->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $conclusion->empreinte_fichier = $empreinte;
                if ($request->estLu == "on") {
                    $conclusion->estLu = 1;
                } else {
                    $conclusion->estLu = 0;
                }
                $conclusion->pertinence = $request->pertinence;
                $conclusion->save();

                //Enregistrer les empreinte de fichier
                $tampon = new Tampon();
                $tampon->empreinte_fichier = $empreinte;
                $tampon->save();

                // Après avoir enregistré, faire la redirection
                return redirect()->route('conclusion.index')->with('status', 'La conclusion a bien été enregistré.');
            }
        } else {
            return redirect()->back()->withInput()->withErrors(['fichier_scanner' => 'Veuillez sélectionner un fichier.']);
        }
    }

    //Delete conclusion
    public function delete($id)
    {
        $conclusion = Conclusion::find($id);

        if (!$conclusion) {
            return redirect()->route('conclusion.index')->with('error', 'Document non trouvé.');
        }

        $conclusion->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('conclusion.index')->with('status', 'La conclusion a bien été supprimé.');
    }


    public function show($id)
    {
        $conclusion = Conclusion::find($id);
        if ($conclusion) {
            return view('auth.conclusion.show', compact('conclusion', 'id'));
        } else {
            return redirect()->route('conclusion.index')->withErrors(['documentId' => 'Document non trouvé.']);
        }
    }

    //Modifier une conclusion
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_fichier' => 'required',
        ]);
        // L'empreinte du fichier n'existe pas dans la base de données
        $conclusion = Conclusion::findOrFail($id);
        $conclusion->nom_fichier = strtolower(str_replace('.pdf', '', $request->nom_fichier)) . '.pdf';         
        if ($request->estLu == "on") {
            $conclusion->estLu = 1;
        } else {
            $conclusion->estLu = 0;
        }
        $conclusion->pertinence = $request->pertinence;
        $conclusion->save();
        // Après avoir enregistré, faire la redirection
        return redirect()->route('conclusion.index')->with('status', 'Conclusion mise à jour avec succès');
           
    }

}
