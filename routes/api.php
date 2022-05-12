<?php

use App\Http\Controllers\IncidentController;
use App\Models\Incident;
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

Route::controller(IncidentController::class)->group(function () {
    Route::get("/incidents", "all");

    Route::get("/incidents/{year}/{month?}/{day?}", "index")
        ->where("year", "19[0-9][0-9]|20[0-2][0-9]")
        ->where("month", "0[1-9]|1[0-2]")
        ->where("day", "0[1-9]|1[0-9]|2[0-9]|3[0-1]")
        ->name("incidents-list");

    Route::get("/maps", "maps")->name("maps");
    Route::get("/search/{input}", "search")->name("search");
    Route::get("/searchCountryCode/{input}", "searchCountryCode")->name("searchCountryCode");
});


//Route::get("/incident/{id}/image", [IncidentController::class, "setImage"])->name("incident-set-image");
