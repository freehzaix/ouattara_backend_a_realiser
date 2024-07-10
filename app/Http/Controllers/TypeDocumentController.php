<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeDocumentRequest;
use App\Models\Parametre;
use App\Models\Tampon;
use App\Models\TypeDocument;
use Yajra\DataTables\Facades\DataTables;

class TypeDocumentController extends Controller
{


    public function index()
    {
        return view('auth.type-document.index');
    }

    public function chargerListeDeTypeDocument()
    {
        
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = TypeDocument::all();
       
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
                    <a class="btn btn-primary btn-sm" href="/dashboard/type-documents/'.$document->id.'" target="_blank">
                        
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
                                    <form action="'.route('type-document.delete', $document->id).'"
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

    //Fonction Post pour ajouter un guide dans la base de données
    public function create(TypeDocumentRequest $request)
    {
        $request->validated();
        
        if ($request->hasFile('fichier_scanner')) {
            // Obtenir le contenu du fichier PDF
            $contenu = file_get_contents($request->file('fichier_scanner')->path());
            $base64Data = base64_encode($contenu); // Conversion du fichier
            $nomFichier = Parametre::genererNomDuFichier($request->nom_fichier);//1

            // Générer l'empreinte numérique avec la fonction MD5
            $empreinte = Parametre::genererEmpreinte($contenu);//2

            // Récupérer une empreinte existante
            $existe = Parametre::verifierSiEmpreinteExiste($empreinte);//3
            if ($existe === false) {
                // L'empreinte du fichier n'existe pas dans la base de données
                $typeDocument = new TypeDocument();
                $typeDocument->nom_fichier = strtolower($nomFichier);
                $typeDocument->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $typeDocument->empreinte_fichier = $empreinte;
                $typeDocument->save();
                //Enregistrer les empreinte de fichier
                Parametre::sauvegarderTampon($empreinte);//4
                // Après avoir enregistré, faire la redirection
                return redirect()->route('type-document.index')->with('status', 'Le document a bien été enregistré.');
            } 
            return redirect()->back()->with('warning', 'Le document existe déjà dans la base de données.');
        } else {
            return redirect()->back()->withInput()->withErrors(['fichier_scanner' => 'Veuillez sélectionner un fichier.']);
        }

    }

    //Delete TypeDocument
    public function delete($id)
    {
        $typeDocument = TypeDocument::find($id);

        // Récupérer une empreinte existante
        $tampon = Tampon::where('empreinte_fichier', $typeDocument->empreinte_fichier)->first();
        if($tampon){
            $tampon->delete();
        }

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
