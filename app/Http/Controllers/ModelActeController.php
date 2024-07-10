<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModelActeRequest;
use App\Models\ModelActe;
use App\Models\Tampon;
use Yajra\DataTables\Facades\DataTables;

class ModelActeController extends Controller
{


    public function index()
    {
        return view('auth.model-acte.index');
    }

    public function chargerListeDeModelActe()
    {
        
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = ModelActe::all();
       
        return Datatables::of($documents)
            ->addColumn('numero', function () use (&$index) {
                $index++;
                return $index;
            })
            ->addColumn('nomFichier', function ($document) {
                return $document->nom_fichier;
            })
            ->addColumn('dateAjout', function ($document) {
                return $document->created_at->locale('fr')->diffForHumans();
            })
            ->editColumn('action', function($document){
                $action = '
                    <a class="btn btn-primary btn-sm" href="/dashboard/model-actes/'.$document->id.'" target="_blank">
                        
                        <i class="fa-solid fa-pencil fa-fw"></i>Afficher
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#deleteModal'.$document->id.'">Supprimer
                    </button>
                    <div class="modal fade" id="deleteModal'.$document->id.'" tabindex="-1"
                        role="dialog" aria-labelledby="deleteModalLabel'.$document->id.'" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel'.$document->id.'">Confirmation de
                                        suppression</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer ce audience ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Annuler</button>
                                    <form action="'.route('model-acte.delete', $document->id).'"
                                        method="POST">
                                        '.csrf_field().'
                                        '.method_field('DELETE').'
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
                return $action;
            })
            ->make(true);
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
            $existe = Tampon::where('empreinte_fichier', $empreinte)->exists();
            if ($existe) {
                // L'empreinte du fichier existe déjà dans la base de données -> on affiche un warning.
                return redirect()->route('model-acte.index')->with('warning', 'Le document existe déjà dans la base de données.');
            } else {
                // L'empreinte du fichier n'existe pas dans la base de données
                $modelActe = new ModelActe();
                $modelActe->nom_fichier = strtolower(str_replace(' ', '_', $request->nom_fichier)) . '.pdf';
                $modelActe->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $modelActe->empreinte_fichier = $empreinte;
                $modelActe->save();

                //Enregistrer les empreinte de fichier
                $tampon = new Tampon();
                $tampon->empreinte_fichier = $empreinte;
                $tampon->save();

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

        // Récupérer une empreinte existante
        $tampon = Tampon::where('empreinte_fichier', $modelActe->empreinte_fichier)->first();
        if($tampon){
            $tampon->delete();
        }

        if (!$modelActe) {
            return redirect()->route('model-acte.index')->with('error', 'Document non trouvé.');
        }

        $modelActe->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('model-acte.index')->with('status', 'Le document a bien été supprimé.');
    }


    public function show($id)
    {
        $modelActe = ModelActe::find($id);
        if ($modelActe) {
            return view('auth.model-acte.show', compact('modelActe', 'id'));
        } else {
            return redirect()->route('model-acte.index')->withErrors(['modelActe' => 'Document non trouvé.']);
        }
    }

}
