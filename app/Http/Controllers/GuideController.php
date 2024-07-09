<?php

namespace App\Http\Controllers;

// use DataTables;
use App\Http\Requests\GuideRequest;
use App\Models\Guide;
use App\Models\Tampon;
use Yajra\DataTables\Facades\DataTables;

class GuideController extends Controller
{


    public function index()
    {
        return view('auth.guide.index');
    }

    public function chargerListeDeDocumentLu()
    {
        
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = Guide::all();
       
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
                    <a class="btn btn-primary btn-sm" href="/dashboard/guides/'.$document->id.'" target="_blank">
                        
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
                                    <form action="'.route('guide.delete', $document->id).'"
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
    public function create(GuideRequest $request)
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
                return redirect()->route('guide.index')->with('warning', 'Le document existe déjà dans la base de données.');
            } else {
                // L'empreinte du fichier n'existe pas dans la base de données
                $guide = new Guide();
                $guide->nom_fichier = strtolower(str_replace(' ', '_', $request->nom_fichier)) . '.pdf';
                $guide->fichier_scanner = $base64Data; // Sauvegarde du fichier converti ici
                $guide->empreinte_fichier = $empreinte;
                $guide->save();

                //Enregistrer les empreinte de fichier
                $tampon = new Tampon();
                $tampon->empreinte_fichier = $empreinte;
                $tampon->save();

                // Après avoir enregistré, faire la redirection
                return redirect()->route('guide.index')->with('status', 'Le document a bien été enregistré.');
            }
        } else {
            return redirect()->back()->withInput()->withErrors(['fichier_scanner' => 'Veuillez sélectionner un fichier.']);
        }
    }

    //Delete Guide
    public function delete($id)
    {
        try {
            // Trouver le guide par son ID
            $guide = Guide::findOrFail($id);

            // Trouver le tampon associé au guide par l'empreinte du fichier
            $tampon = Tampon::where('empreinte_fichier', $guide->empreinte_fichier)->first();
            // Supprimer le tampon s'il existe
            if ($tampon) {
                $tampon->delete();
            }

            // Supprimer le guide
            $guide->delete();

            return back()->with('status', 'Guide et tampon supprimés avec succès');
        } catch (\Exception $e) {
            return back()->with('warning', 'Erreur lors de la suppression du guide : ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $guide = Guide::findOrFail($id);
        // Ajoutez votre logique pour afficher le fichier ici
        // Par exemple, retourner une vue avec le guide
        return view('auth.guide.show', compact('guide'));
    }
}
