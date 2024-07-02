<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvenementRequest;
use App\Models\Evenement;

class EvenementController extends Controller
{

    public function index()
    {
        $evenements = Evenement::all();

        return view('auth.evenement.index', compact('evenements'));
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
        return redirect()->route('evenement.index')->with('status', 'Le document a bien été enregistré.');
        
    }

    //Delete Evènement
    public function delete($id)
    {
        $evenement = Evenement::find($id);

        if (!$evenement) {
            return redirect()->route('evenement.index')->with('error', 'Document non trouvé.');
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
     public function edit(EvenementRequest $request)
     {
         $request->validated();
 
         $evenement = Evenement::find($request->id);
         $evenement->nom = $request->nom;
         $evenement->lieu = $request->lieu;
         $evenement->update();
         //Après avoir modifier, faire la rediretion
         return redirect()->route('evenement.index')->with('status', 'L\'évènement a bien été modifié.');
     }

}
