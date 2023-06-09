<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/import', [ImportController::class, 'importFile'])->name('import');
Route::get('/check-job-progress', [ImportController::class, 'checkJobProgress'])->name('job-progress');

Route::get('/edit', [ImportController::class, 'viewEditImport'])->name('view-edit');
Route::put('/edit-data', [ImportController::class, 'editImport'])->name('edit-data');

Route::post('/delete', [ImportController::class, 'deleteImport'])->name('delete');

Route::post('/export', [ExportController::class, 'exportData'])->name('export');
