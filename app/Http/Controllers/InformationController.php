<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationRequest;
use App\Mail\UserEmailInformation;
use App\Models\Information;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;

class InformationController extends Controller
{
    
    public function index()
    {
        return view('auth.information.index');
    }

    public function chargerListeInformation()
    {
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = Information::all();

        return DataTables::of($documents)
            ->addColumn('numero', function () use (&$index) {
                $index++;
                return $index;
            })
            ->addColumn('contenuMessage', function ($document) {
                return $document->contenu_message;
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
                                                    <h5 class="modal-title" id="editModalLabel">Modifier le contenu du message</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        
                                                    </button>
                                                </div>
                                                <form method="POST" action="/dashboard/informations/' . $document->id . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('PUT') . '
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="contenu_message1">Contenu message</label>
                                                                <textarea class="summernote" name="contenu_message" id="contenu_message1">' . $document->contenu_message . '</textarea>
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
                                                <script>
                                                    $(document).ready(function() {
                                                        $(".summernote").summernote({
                                                            height: 150
                                                        });
                                                    });
                                                </script>
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
                                    <form action="' . route('information.delete', $document->id) . '"
                                        method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-info btn-sm" href="/dashboard/informations/sendmail/' . $document->id . '">Envoyez mail</a>
                ';
                return $action;
            })
            ->make(true);
    }

    //Fonction Post pour ajouter un Information dans la base de données
    public function create(InformationRequest $request)
    {
        $request->validated();

        $information = new Information();
        $information->contenu_message = $request->contenu_message;       
        $information->save();

        //Après avoir enregistrer, faire la rediretion
        return redirect()->route('information.index')->with('status', 'L\'information a bien été enregistré.');

    }

    //Delete information
    public function delete($id)
    {
        $information = Information::find($id); //Récupéré l'information
        $information->delete(); 
        //Après avoir supprimer, faire la rediretion
        return redirect()->route('information.index')->with('status', 'L\'information a bien été supprimé.');
    }

    //Affiche une information
    public function show($id)
    {
        $information = Information::find($id); //Récupéré l'information

        return view('auth.information.show', compact('information'));
    }

    //Modifier une information
    public function update(InformationRequest $request, $id)
    {
        $request->validated();

        $information = Information::findOrFail($id);
        $information->contenu_message = $request->contenu_message;
        $information->save();

        return redirect()->route('information.index')->with('status', 'Information mise à jour avec succès');
    }
    
    //Information Send Email
    public function sendEmailInformation($id)
    {
        $users = User::all();
        $information = Information::find($id);

        // dd($information);

        foreach ($users as $user) {

            Mail::to($user->email)->send(new UserEmailInformation($information));

        }

        return redirect()->route('information.index')->with('status', 'Mail envoyé avec success.');
    }

}
