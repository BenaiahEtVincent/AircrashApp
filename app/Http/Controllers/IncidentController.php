<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;


class IncidentController extends Controller
{
    public function index($year, $month = null, $day = null)
    {
        $incidents = Incident::workable()
            ->whereYear('crash_date', $year)
            ->when($month != null, function ($q) use ($month) {
                return $q->whereMonth('crash_date', $month);
            })->when($day != null, function ($q) use ($day) {
                return $q->whereDay('crash_date', $day);
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

    public function search($input)
    {
        $query = Incident::query();
        $columns = Schema::getColumnListing('crashs');

        if ($time = strtotime($input)) {
            $input = date("Y-m-d", $time);
        }

        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', '%' . $input . '%');
        }
        $incidents = $query->get();

        return response()->json($incidents);
    }

    public function maps()
    {
        return Storage::get("map.json");
    }
}
