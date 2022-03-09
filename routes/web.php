<?php

use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/incidents/{year?}/{month?}/{day?}", [IncidentController::class, "index"])
    ->where('year', "19[0-9][0-9]|20[01][0-9]|202[0-2]")
    ->name("incidents-list");
