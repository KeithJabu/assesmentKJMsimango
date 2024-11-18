<?php

use App\Http\Controllers\AssessmentController\BGJobsController;
use App\Http\Controllers\AssessmentController\BGJobsSearchController;
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

Route::get('/dashboard', [BGJobsController::class, 'index'])->name('home');
Route::get('search-datatables/{status}', [BGJobsSearchController::class, 'searchDataTable'])->name('search.datatable');
