<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AccueilController;
use App\Http\Controllers\ExcelImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\AccueilController::class,'index']);
Route::post('/',[\App\Http\Controllers\PosteController::class,'store'])->name('poster');
Route::get('/update',[\App\Http\Controllers\PosteController::class,'edit']);
Route::post('/update',[\App\Http\Controllers\PosteController::class,'update'])->name('updatePoste');
Route::get('/delete',[\App\Http\Controllers\PosteController::class,'destroy'])->name('deletePoste');
Route::get('/f',[\App\Http\Controllers\ReactController::class,'index'])->name('reacter');

//Route::get('/f',[\App\Http\Controllers\ReactController::class,'store']);
//Route::get('/rechercher',[\App\Http\Controllers\AccueilController::class,'rechercher'])->name('rechercher');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'globalSearch'])->name('rechercher');
Route::get('/a', [\App\Http\Controllers\AuthController::class, 'createUser']);
Route::get('/import', [ExcelImportController::class, 'importView'])->name('import.view');
Route::post('/importa', [ExcelImportController::class, 'import'])->name('import');

