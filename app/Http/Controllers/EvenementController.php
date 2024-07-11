<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvenementRequest;
use App\Models\Evenement;
use Yajra\DataTables\Facades\DataTables;

class EvenementController extends Controller
{

    public function index()
    {
        return view('auth.evenement.index');
    }

    public function chargerListeEvenement()
    {
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = Evenement::all();

        return DataTables::of($documents)
            ->addColumn('numero', function () use (&$index) {
                $index++;
                return $index;
            })
            ->addColumn('nom', function ($document) {
                return $document->nom;
            })
            ->addColumn('lieu', function ($document) {
                return $document->lieu;
            })
            ->addColumn('dateAjout', function ($document) {
                return $document->created_at->locale('fr')->diffForHumans();
            })
            ->editColumn('action', function ($document) {
                $action = '
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                        data-target="#editModal' . $document->id . '">Modifier</button>
                    <div class="modal fade" id="editModal' . $document->id . '" tabindex="-1" role="dialog"
                                        aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Modifier l\'évènement</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        
                                                    </button>
                                                </div>
                                                <form method="POST" action="/dashboard/evenements/' . $document->id . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('PUT') . '
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="nom">Nom</label>
                                                                <input type="text" class="form-control" name="nom" id="nom"
                                                                value="' . $document->nom . '">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="lieu">Lieu</label>
                                                                <input type="text" class="form-control" name="lieu" id="lieu"
                                                                value="' . $document->lieu . '">
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal"><i class="fas fa-reply"></i> Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#deleteModal' . $document->id . '">Supprimer
                    </button>
                    <div class="modal fade" id="deleteModal' . $document->id . '" tabindex="-1"
                        role="dialog" aria-labelledby="deleteModalLabel' . $document->id . '" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel' . $document->id . '">Confirmation de
                                        suppression</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer cet élément ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info"
                                        data-dismiss="modal"><i class="fas fa-reply"></i> Annuler</button>
                                    <form action="' . route('evenement.delete', $document->id) . '"
                                        method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
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

    //Fonction Post pour ajouter un evènement dans la base de données
    public function create(EvenementRequest $request)
    {
        $request->validated();
        
        $evenement = new Evenement();
        $evenement->nom = $request->nom;    
        $evenement->lieu = $request->lieu;    
        $evenement->save();
        // Après avoir enregistré, faire la redirection
        return redirect()->route('evenement.index')->with('status', 'L\'évènement a bien été enregistré.');
        
    }

    //Delete Evènement
    public function delete($id)
    {
        $evenement = Evenement::find($id);

        if (!$evenement) {
            return redirect()->route('evenement.index')->with('error', 'Evènement non trouvé.');
        }

        $evenement->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('evenement.index')->with('status', 'L\'évènement a bien été supprimé.');

    }


     //Affiche un evenement
     public function show($id)
     {
         $evenement = Evenement::find($id); //Récupéré l'évènement
 
         return view('auth.evenement.show', compact('evenement'));
     }
 
     //Modifier un évènement
    public function update(EvenementRequest $request, $id)
    {
        $request->validated();

        $evenement = Evenement::findOrFail($id);
        $evenement->nom = $request->nom;
        $evenement->lieu = $request->lieu;
        $evenement->save();
        
        return redirect()->route('evenement.index')->with('status', 'Evènement mise à jour avec succès');
    }

}
