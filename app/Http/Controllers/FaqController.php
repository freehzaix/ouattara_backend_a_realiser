<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    
    public function index()
    {
        return view('auth.faq.index');
    }

    public function chargerListeFaq()
    {
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = Faq::all();

        return DataTables::of($documents)
            ->addColumn('numero', function () use (&$index) {
                $index++;
                return $index;
            })
            ->addColumn('question', function ($document) {
                return $document->question;
            })
            ->addColumn('reponse', function ($document) {
                return $document->reponse;
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
                                                    <h5 class="modal-title" id="editModalLabel">Modifier le FAQ</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        
                                                    </button>
                                                </div>
                                                <form method="POST" action="/dashboard/faqs/' . $document->id . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('PUT') . '
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="question">Question</label>
                                                                <input type="text" class="form-control" name="question" id="question"
                                                                value="' . $document->question . '">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="reponse">Réponse</label>
                                                                <input type="text" class="form-control" name="reponse" id="reponse"
                                                                value="' . $document->reponse . '">
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-primary">Enregistrer</button>
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
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Annuler</button>
                                    <form action="' . route('faq.delete', $document->id) . '"
                                        method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
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

    //Fonction Post pour ajouter un FAQ dans la base de données
    public function create(FaqRequest $request)
    {
        $request->validated();

        $faq = new Faq();
        $faq->question = $request->question;         
        $faq->reponse = $request->reponse;         
        $faq->save();

        //Après avoir enregistrer, faire la rediretion
        return redirect()->route('faq.index')->with('status', 'Le FAQ a bien été enregistré.');

    }

    //Delete FAQ
    public function delete($id)
    {
        $faq = Faq::find($id); //Récupéré le FAQ
        $faq->delete(); 
        //Après avoir supprimer, faire la rediretion
        return redirect()->route('faq.index')->with('status', 'Le FAQ a bien été supprimé.');
    }

    //Affiche un FAQ
    public function show($id)
    {
        $faq = Faq::find($id); //Récupéré le FAQ

        return view('auth.faq.show', compact('faq'));
    }

    //Modifier un FAQ
    public function update(FaqRequest $request, $id)
    {
        $request->validated();

        $faq = Faq::findOrFail($id);
        $faq->question = $request->question;
        $faq->reponse = $request->reponse;
        $faq->save();

        return redirect()->route('faq.index')->with('status', 'FAQ mise à jour avec succès');
    }
    
}
