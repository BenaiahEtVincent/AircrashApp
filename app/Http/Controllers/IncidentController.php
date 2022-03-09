<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Exception;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index($year = null, $month = null, $day = null)
    {
        $query = Incident::query();

        $query->when($year != null, function ($q) use ($year) {
            return $q->whereYear('incident_date_updated', $year);
        });

        $query->when($month != null, function ($q) use ($month) {
            return $q->whereMonth('incident_date_updated', $month);
        });

        $query->when($day != null, function ($q) use ($day) {
            return $q->whereDay('incident_date_updated', $day);
        });

        $incidents = $query->get();


        return response()->json($incidents);
    }
}
