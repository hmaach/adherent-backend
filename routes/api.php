<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\SearchController;
use \App\Http\Controllers\PosteController;
use \App\Http\Controllers\NotificationController;
use \App\Http\Controllers\StagiaireController;
use \App\Http\Controllers\FiliereController;
use \App\Http\Controllers\AdherentController;
use App\Http\Controllers\AnnounceController;
use \App\Http\Controllers\EvenementController;
use \App\Http\Controllers\PDFController;
use \App\Http\Controllers\PdfCategorieController;
use App\Http\Controllers\ExcelImportController;
use \App\Http\Controllers\SecteurController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('user', 'user')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::get('check-auth', 'checkAuth')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('poste', PosteController::class);
    Route::resource('events', EvenementController::class);
    Route::resource('filiere', FiliereController::class);
    Route::resource('category', PdfCategorieController::class);
//    Route::resource('adherent', AdherentController::class);


    Route::controller(AdherentController::class)->group(function () {
        Route::prefix('adherent')->group(function () {
            Route::get('/{id}', 'index');
            Route::put('/{id}/update', 'update');
            Route::post('/{id}/updateImage', 'updateImage');
            Route::post('/{id}/update-profil','updateProfilAdherent');
            Route::delete('/{id}/remove-profil','removeProfilAdherent');
            Route::post('/{id}/removeImage', 'removeImage');
            Route::post('/{id}/rate', 'rate');
        });
    });

    Route::controller(AnnounceController::class)->group(function () {
        Route::prefix('announce')->group(function () {
            Route::get('/{id}', 'index');
            Route::post('/store', 'store');
            Route::post('/{id}/approve', 'approve');
            Route::put('/edit', 'edit');
            Route::delete('/{id}/delete', 'destroy');
        });
    });


    Route::get('ownarchive', [PdfCategorieController::class, 'index']);
    Route::put('category/update/{category}', [PdfCategorieController::class, 'update']);

    Route::controller(EvenementController::class)->group(function () {
        Route::post('/events/{evenement}/cancel', 'cancelEvent');
        Route::post('/events/{evenement}/restore-color', 'restoreEventColor');
        Route::put('/events/{evenement}', 'update');
        Route::delete('/events/{evenement}', 'destroy');
    });

    Route::controller(PosteController::class)->group(function () {
        Route::put('poste/update', 'update');
        Route::post('/poste/{postId}/like', 'likePost');
    });

    Route::resource('filiere', FiliereController::class);
    Route::post('change-password', [AuthController::class, 'changePassword']);


    Route::controller(PDFController::class)->group(function () {
        Route::put('pdf/update/{pdf}', 'update');
        Route::post('pdf/removecategory/{pdf}', 'removeCategory');

    });
});

Route::get('archive', [PdfCategorieController::class, 'index']);
Route::prefix('public')->group(function () {
    Route::get('adherent/{id}', [AdherentController::class, 'index']);
    Route::post('adherent/{id}/profil', [AdherentController::class, 'updateProfilAdherent']);
    Route::get('4-adherents', [AdherentController::class, 'randomFouradherent']);
    Route::get('announce/{id}', [AnnounceController::class, 'index']);
    Route::get('secteur', [SecteurController::class, 'index']);
});
Route::resource('/pdf', PDFController::class);

Route::get('/downloadpdf', [PDFController::class, 'downloadPDF']);
Route::get('/search', [SearchController::class, 'globalSearch']);
Route::get('/edit', [PosteController::class, 'edit']);
Route::get('eventspublic', [EvenementController::class, 'index']);
Route::get('showevent/{evenement}', [EvenementController::class, 'show']);
Route::get('showevents', [EvenementController::class, 'showEvents']);
Route::get('monthevents', [EvenementController::class, 'thisMonthEvents']);
Route::get('dayevents', [EvenementController::class, 'getByDay']);
Route::get('postespublic', [PosteController::class, 'index']);
Route::resource('notifs', NotificationController::class);
Route::get('fourstagiaires', [StagiaireController::class, 'randomFourStagiaires']);


Route::get('stagiaire/{id}', [StagiaireController::class, 'index']);
Route::put('stagiaire/{id}', [StagiaireController::class, 'update']);

Route::put('stagiaire/{id}/competences/{competenceId}', [StagiaireController::class, 'updateCompetences']);
Route::post('/stagiaire/{id}/competences', [StagiaireController::class, 'addCompetence']);


Route::prefix('stagiaires')->group(function () {
    Route::post('{id}/experiences', [StagiaireController::class, 'addExperience']);
    Route::put('{id}/experiences/{experienceId}', [StagiaireController::class, 'updateExperience']);
});

Route::prefix('formations')->group(function () {
    Route::post('/{id}', [StagiaireController::class, 'addFormation']);
    Route::put('/{id}/{formationId}', [StagiaireController::class, 'updateFormation']);
});


Route::prefix('interets')->group(function () {
    Route::post('/{id}', [StagiaireController::class, 'addInteret']);
    Route::put('/{id}/{interetId}', [StagiaireController::class, 'updateInteret']);
});


Route::get('cv/{id}', [StagiaireController::class, 'show']);
Route::post('/stagiaires/{id}/add-propos', [StagiaireController::class, 'addPropos']);


Route::post('/stagiaires/import', [ExcelImportController::class, 'import']);
Route::get('/import', [ExcelImportController::class, 'importView'])->name('import.view');
Route::post('/stagiaires/import', [ExcelImportController::class, 'import'])->name('import');
Route::post('/search', [ExcelImportController::class, 'search'])->name('search');
Route::get('/stagiairesExc', [ExcelImportController::class, 'index']);


Route::get('/gcv/{id}', [\App\Http\Controllers\CVController::class, 'generate']);
