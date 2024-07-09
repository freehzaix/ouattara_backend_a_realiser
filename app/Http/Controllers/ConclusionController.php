<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConclusionRequest;
use App\Models\Conclusion;
use App\Models\Tampon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConclusionController extends Controller
{


    public function index()
    {
        return view('auth.conclusion.index');
    }

    public function chargerListeDeConclusion()
    {
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documents = Conclusion::all();

        return DataTables::of($documents)
            ->addColumn('numero', function () use (&$index) {
                $index++;
                return $index;
            })
            ->addColumn('nomFichier', function ($document) {
                return $document->nom_fichier;
            })
            ->addColumn('pertinence', function ($document) {
                return $document->pertinence;
            })
            ->addColumn('estLuOuPas', function ($document) {
                return $document->estLu == 1 ? 'oui' : 'non';
            })
            ->addColumn('dateAjout', function ($document) {
                return $document->created_at->locale('fr')->diffForHumans();
            })
            ->editColumn('action', function ($document) {
                $action = '
                    <a class="btn btn-primary btn-sm" href="/dashboard/conclusions/' . $document->id . '" target="_blank">
                        Afficher
                    </a>
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                        data-target="#editModal' . $document->id . '">Modifier</button>
                    <div class="modal fade" id="editModal' . $document->id . '" tabindex="-1" role="dialog"
                                        aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Modifier la conclusion</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        
                                                    </button>
                                                </div>
                                                <form method="POST" action="/dashboard/conclusions/' . $document->id . '">
                                                    ' . csrf_field() . '
                                                    ' . method_field('PUT') . '
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="nom_fichier">Nom du fichier</label>
                                                                <input type="text" class="form-control" name="nom_fichier" id="nom_fichier"
                                                                value="' . $document->nom_fichier . '">
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="pertinence">Pertinence du fichier</label>
                                                                <input type="text" class="form-control" name="pertinence" id="pertinence"
                                                                value="' . $document->pertinence . '">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="estLu">Est lu ou pas ?</label>
                                                                <input type="checkbox" name="estLu" id="estLu">
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
                                    Êtes-vous sûr de vouloir supprimer ce audience ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Annuler</button>
                                    <form action="' . route('conclusion.delete', $document->id) . '"
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

        // Récupérer une empreinte existante
        $tampon = Tampon::where('empreinte_fichier', $conclusion->empreinte_fichier)->first();
        if ($tampon) {
            $tampon->delete();
        }

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
