<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationRequest;
use App\Models\Information;

class InformationController extends Controller
{
    
    public function index()
    {
        $informations = Information::all();

        return view('auth.information.index', compact('informations'));
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
    
}
