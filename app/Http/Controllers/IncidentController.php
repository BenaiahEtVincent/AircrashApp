<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class IncidentController extends Controller
{
    public function index($year = null, $month = null, $day = null)
    {
        $incidents = Incident::workable()
            ->when($year != null, function ($q) use ($year) {
                return $q->whereYear('incident_date_updated', $year);
            })->when($month != null, function ($q) use ($month) {
                return $q->whereMonth('incident_date_updated', $month);
            })->when($day != null, function ($q) use ($day) {
                return $q->whereDay('incident_date_updated', $day);
            })->get();


        return response()->json($incidents);
    }

    public function setImage($id)
    {
        $incident = Incident::findOrFail($id);

        $imgIndex = 0;

        $url = $incident->images[$imgIndex]->local_url;

        echo "<img src='$url'>";


        dd($url);
    }
}
