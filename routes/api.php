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


Route::get("/incidents/{year?}/{month?}/{day?}", [IncidentController::class, "index"])
    ->where('year', "19[0-9][0-9]|20[0-2][0-9]")
    ->name("incidents-list");
