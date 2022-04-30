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

    public function all()
    {
        ini_set('memory_limit', '2048M');

        $incidents = Incident::workable()->get()->groupBy(function ($val) {
            return \Carbon\Carbon::parse($val->crash_date)->format('Y');
        });

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
            $query->where("crash_date", 'LIKE', '%' . $input . '%');
        } else {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $input . '%');
            }
        }
        $incidents = $query->workable()->get();

        return response()->json($incidents);
    }

    public function searchCountryCode($code)
    {
        $incidents = Incident::workable()->where('incident_country_code', $code)->get();

        return response()->json($incidents);
    }

    public function maps()
    {
        return Storage::get("map.json");
    }

    public function edit(Incident $incident) {
        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident) {

        $incident->update($request->all());

        return redirect()->route('home');
    }
}
