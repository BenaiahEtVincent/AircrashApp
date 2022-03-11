<?php

use App\Http\Controllers\IncidentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("/incidents/{year}/{month?}/{day?}", [IncidentController::class, "index"])
    ->where('year', "19[0-9][0-9]|20[0-2][0-9]")
    ->where('month', "[1-9]|1[0-2]")
    ->where('day', "[1-9]|1[0-9]|2[0-9]|3[0-1]")
    ->name("incidents-list");
