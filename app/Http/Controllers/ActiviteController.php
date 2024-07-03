<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActiviteRequest;
use App\Models\Activite;

class ActiviteController extends Controller
{

    public function index()
    {
        $activites = Activite::all();

        return view('auth.activite.index', compact('activites'));
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
