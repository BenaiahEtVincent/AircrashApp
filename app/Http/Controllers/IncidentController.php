<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Exception;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::all();

        return response()->json($incidents);
    }
}
