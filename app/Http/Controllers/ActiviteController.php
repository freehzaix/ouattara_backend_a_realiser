<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActiviteRequest;
use App\Models\Activite;
use Yajra\DataTables\Facades\DataTables;

class ActiviteController extends Controller
{

    public function index()
    {
        return view('auth.activite.index');
    }

    public function chargerListeActivite()
    {
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = Activite::all();

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
                                                    <h5 class="modal-title" id="editModalLabel">Modifier l\'activité</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        
                                                    </button>
                                                </div>
                                                <form method="POST" action="/dashboard/activites/' . $document->id . '">
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
                                    <form action="' . route('activite.delete', $document->id) . '"
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

    //Fonction Post pour ajouter une activité dans la base de données
    public function create(ActiviteRequest $request)
    {
        $request->validated();

        $activite = new Activite();
        $activite->nom = $request->nom;
        $activite->lieu = $request->lieu;
        $activite->save();
        // Après avoir enregistré, faire la redirection
        return redirect()->route('activite.index')->with('status', 'L\'activité a bien été enregistré.');
    }

    //Delete activité
    public function delete($id)
    {
        $activite = Activite::find($id);

        if (!$activite) {
            return redirect()->route('activite.index')->with('error', 'Activité non trouvé.');
        }

        $activite->delete();

        // Après avoir supprimé, faire la redirection avec un message de succès
        return redirect()->route('activite.index')->with('status', 'L\'activité a bien été supprimé.');
    }


    //Affiche une activité
    public function show($id)
    {
        $activite = Activite::find($id); //Récupéré l'activité

        return view('auth.activite.show', compact('activite'));
    }

    //Modifier une activité
    public function update(ActiviteRequest $request, $id)
    {
        $request->validated();

        $activite = Activite::findOrFail($id);
        $activite->nom = $request->nom;
        $activite->lieu = $request->lieu;
        $activite->save();

        return redirect()->route('activite.index')->with('status', 'Activité mise à jour avec succès');
    }
    
}
