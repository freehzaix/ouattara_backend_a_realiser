<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    
    public function index()
    {
        $faqs = Faq::all();

        return view('auth.faq.index', compact('faqs'));
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
