<?php

use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\CompteRenduController;
use App\Http\Controllers\ConclusionController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ModelActeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TypeDocumentController;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->group(function () {
    Route::get('/', [PagesController::class, 'dashboard'])->name('dashboard');

    //Guide
    Route::get('/guides', [GuideController::class, 'index'])->name('guide.index');
    Route::get('/chargerdocumentguide', [GuideController::class, 'chargerListeDeDocumentLu'])->name('charger.liste.document');
    // Route en Post pour Ajouter un Guide dans la base de données
    Route::post('/guides/create', [GuideController::class, 'create'])->name('guide.create');
    Route::delete('/guides/{id}', [GuideController::class, 'delete'])->name('guide.delete');
    Route::get('/guides/{id}', [GuideController::class, 'show'])->name('guide.show');
    
    //TypeDocuments
    // Route::resource('type-document', TypeDocumentController::class);
    Route::get('/type-documents', [TypeDocumentController::class, 'index'])->name('type-document.index');
    Route::get('/chargertypedocument', [TypeDocumentController::class, 'chargerListeDeTypeDocument'])->name('charger.liste.typedocument');
    // Route en Post pour Ajouter un type de document dans la base de données
    Route::post('/type-documents/create', [TypeDocumentController::class, 'create'])->name('type-document.create');
    Route::delete('/type-documents/delete/{id}', [TypeDocumentController::class, 'delete'])->name('type-document.delete');
    Route::get('/type-documents/{id}', [TypeDocumentController::class, 'show'])->name('type-document.show');

    //Faq
    Route::get('/faqs', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/chargerdocumentfaq', [FaqController::class, 'chargerListeFaq'])->name('charger.liste.faq');
    // Route en Post pour Ajouter un Guide dans la base de données
    Route::post('/faqs/create', [FaqController::class, 'create'])->name('faq.create');
    Route::delete('/faqs/delete/{id}', [FaqController::class, 'delete'])->name('faq.delete');
    Route::get('/faqs/show/{id}', [FaqController::class, 'show'])->name('faq.show');
    Route::put('/faqs/{id}', [FaqController::class, 'update'])->name('faq.update');
    
    //ModelActes
    // Route::resource('model-acte', ModelActeController::class);
    Route::get('/model-actes', [ModelActeController::class, 'index'])->name('model-acte.index');
    Route::get('/chargerdocumentmodelacte', [ModelActeController::class, 'chargerListeDeModelActe'])->name('charger.liste.modelacte');
    // Route en Post pour Ajouter un modèle acte dans la base de données
    Route::post('/model-actes/create', [ModelActeController::class, 'create'])->name('model-acte.create');
    Route::delete('/model-actes/delete/{id}', [ModelActeController::class, 'delete'])->name('model-acte.delete');
    Route::get('/model-actes/{id}', [ModelActeController::class, 'show'])->name('model-acte.show');

    //Evènements
    // Route::resource('evenement', EvenementController::class);
    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenement.index');
    Route::get('/chargerdocumentevenement', [EvenementController::class, 'chargerListeEvenement'])->name('charger.liste.evenement');
    // Route en Post/Delete/Get pour Ajouter un evènement dans la base de données
    Route::post('/evenements/create', [EvenementController::class, 'create'])->name('evenement.create');
    Route::delete('/evenements/delete/{id}', [EvenementController::class, 'delete'])->name('evenement.delete');
    Route::get('/evenements/{id}', [EvenementController::class, 'show'])->name('evenement.show');
    Route::put('/evenements/{id}', [EvenementController::class, 'update'])->name('evenement.update');

    //Activités
    // Route::resource('activite', ActiviteController::class);
    Route::get('/activites', [ActiviteController::class, 'index'])->name('activite.index');
    Route::get('/chargerdocumentactivite', [ActiviteController::class, 'chargerListeActivite'])->name('charger.liste.activite');
    // Route en Post/Delete/Get pour Ajouter une activité dans la base de données
    Route::post('/activites/create', [ActiviteController::class, 'create'])->name('activite.create');
    Route::delete('/activites/delete/{id}', [ActiviteController::class, 'delete'])->name('activite.delete');
    Route::get('/activites/{id}', [ActiviteController::class, 'show'])->name('activite.show');
    Route::put('/activites/{id}', [ActiviteController::class, 'update'])->name('activite.update');

    //CompteRendus
    // Route::resource('compte-rendu', CompteRenduController::class);
    Route::get('/compte-rendus', [CompteRenduController::class, 'index'])->name('compte-rendu.index');
    Route::get('/chargerdocumentcompterendu', [CompteRenduController::class, 'chargerListeDeCompteRendu'])->name('charger.liste.compterendu');
    // Route en Post pour Ajouter un compte-rendu dans la base de données
    Route::post('compte-rendus/create', [CompteRenduController::class, 'create'])->name('compte-rendu.create');
    Route::delete('/compte-rendus/delete/{id}', [CompteRenduController::class, 'delete'])->name('compte-rendu.delete');
    Route::get('/compte-rendus/{id}', [CompteRenduController::class, 'show'])->name('compte-rendu.show');

    //Informations
    // Route::resource('information', InformationController::class);
    Route::get('/informations', [InformationController::class, 'index'])->name('information.index');
    Route::get('/chargerdocumentInformation', [InformationController::class, 'chargerListeInformation'])->name('charger.liste.information');
    // Route en Post pour Ajouter une information dans la base de données
    Route::post('/informations/create', [InformationController::class, 'create'])->name('information.create');
    Route::delete('/informations/delete/{id}', [InformationController::class, 'delete'])->name('information.delete');
    Route::get('/informations/{id}', [InformationController::class, 'show'])->name('information.show');
    Route::put('/informations/{id}', [InformationController::class, 'update'])->name('information.update');

    //Conclusions
    // Route::resource('conclusion', ConclusionController::class);
    Route::get('/conclusions', [ConclusionController::class, 'index'])->name('conclusion.index');
    Route::get('/chargerdocumentconclusion', [ConclusionController::class, 'chargerListeDeConclusion'])->name('charger.liste.conclusion');
    // Route en Post pour Ajouter une conclusion dans la base de données
    Route::post('conclusions/create', [ConclusionController::class, 'create'])->name('conclusion.create');
    Route::delete('/conclusions/delete/{id}', [ConclusionController::class, 'delete'])->name('conclusion.delete');
    Route::get('/conclusions/{id}', [ConclusionController::class, 'show'])->name('conclusion.show');
    Route::put('/conclusions/{id}', [ConclusionController::class, 'update'])->name('conclusion.update');


});
