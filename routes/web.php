<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TypeDocumentController;
use Illuminate\Support\Facades\Route;



Route::prefix('dashboard')->group(function () {
    Route::get('/', [PagesController::class, 'dashboard'])->name('dashboard');
    //Guide
    Route::get('/guides', [GuideController::class, 'index'])->name('guide.index');
    // Route en Post pour Ajouter un Guide dans la base de données
    Route::post('/guides/create', [GuideController::class, 'create'])->name('guide.create');
    Route::delete('/guides/delete/{id}', [GuideController::class, 'delete'])->name('guide.delete');
    Route::get('/guides/{id}', [GuideController::class, 'show'])->name('guide.show');

    //TypeDocuments
    Route::resource('type-document', TypeDocumentController::class);
    Route::get('/type-documents', [TypeDocumentController::class, 'index'])->name('type-document.index');
    // Route en Post pour Ajouter un type de document dans la base de données
    Route::post('/type-documents/create', [TypeDocumentController::class, 'create'])->name('type-document.create');
    Route::delete('/type-documents/delete/{id}', [TypeDocumentController::class, 'delete'])->name('type-document.delete');
    Route::get('/type-documents/{id}', [TypeDocumentController::class, 'show'])->name('type-document.show');

    //Faq
    Route::get('/faqs', [FaqController::class, 'index'])->name('faq.index');
    // Route en Post pour Ajouter un Guide dans la base de données
    Route::post('/faqs/create', [FaqController::class, 'create'])->name('faq.create');
    Route::get('/faqs/delete/{id}', [FaqController::class, 'delete'])->name('faq.delete');
    Route::get('/faqs/show/{id}', [FaqController::class, 'show'])->name('faq.show');
    Route::post('/faqs/edit', [FaqController::class, 'edit'])->name('faq.edit');
    
    

});
