<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Casts\Attribute;
use DOMDocument;


class Incident extends Model
{
    use HasFactory;

    protected $table = "crashs";

    protected $hidden = [
        "incident_gps_lat",
        "incident_gps_lon",
        "depart_gps_lat",
        "depart_gps_lon",
        "destination_gps_lat",
        "destination_gps_lon",
        "created_at",
        "updated_at",
        "Incident_Date",
    ];

    protected $appends = [
        "gps_crash",
        "gps_depart",
        "gps_destination",
        "deaths",
        "occupations"
    ];

    protected $with = [
        "images",
    ];

    protected function getGpsCrashAttribute()
    {
        return  [
            "lat" => $this->incident_gps_lat,
            "lon" => $this->incident_gps_lon,
        ];
    }
    protected function getGpsDepartAttribute()
    {
        return [
            "lat" => $this->depart_gps_lat,
            "lon" => $this->depart_gps_lon,
        ];
    }
    protected function getGpsDestinationAttribute()
    {
        return [
            "lat" => $this->destination_gps_lat,
            "lon" => $this->destination_gps_lon,
        ];
    }

    protected function getDeathsAttribute()
    {
        $onBoardCrew = (int)explode("/", explode("Fatalities:", $this->onboard_crew)[1])[0];
        $onBoardPassengers = (int)explode("/", explode("Fatalities:", $this->onboard_passengers)[1])[0];


        return [
            "on_board" => [
                "crew" => $onBoardCrew,
                "passengers" => $onBoardPassengers,
                "total" => $onBoardPassengers + $onBoardCrew
            ],
            "total" => $this->fatalities
        ];
    }

    protected function getOccupationsAttribute()
    {
        $onBoardCrew = (int) explode("Occupants:", $this->onboard_crew)[1];
        $onBoardPassengers = (int) explode("Occupants:", $this->onboard_passengers)[1];
        $onBoardTotal = (int) explode("Occupants:", $this->onboard_total)[1];

        return [
            "crew" => $onBoardCrew,
            "passengers" => $onBoardPassengers,
            "total" => $onBoardTotal
        ];
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'crash_id', 'id');
    }

    public function scopeWorkable($query)
    {
        return $query->where('incident_gps_lat', "!=", null)
            ->where('depart_gps_lat', "!=", null)
            ->where('destination_gps_lat', "!=", null);
    }

    /* 
    public function cleanDate()
    {
        $dateSplit = explode("-", $this->Incident_Date);
        $day = $dateSplit[0];
        $month = $this->monthString[$dateSplit[1]];
        $year = $dateSplit[2];

        $time = explode(":", $this->Time);
        $hour = (int)$time[0] ?? 0;
        $minute = (int)$time[1] ?? 0;

        $datetime = new \DateTime();
        $datetime->setDate($year, $month, $day);
        $datetime->setTime($hour, $minute);
        $this->date = $datetime;
        $this->update();
    }

    private $monthString = [
        "JAN" => 1,
        "FEB" => 2,
        "MAR" => 3,
        "APR" => 4,
        "MAY" => 5,
        "JUN" => 6,
        "JUL" => 7,
        "AUG" => 8,
        "SEP" => 9,
        "OCT" => 10,
        "NOV" => 11,
        "DEC" => 12
    ];

    private $urlGPS = "https://nominatim.openstreetmap.org/search?q={{}}&format=json";

    public function setGPSEnd()
    {
        $name = explode(" ", $this->Destination_Airport)[0];

        $url = ($this->formatGPSURL($name . " airport"));
        //dd($url);
        $res = Http::get($url);
        $json = json_decode($res->body())[0];
        $lat  = ($json->lat);
        $lon  = ($json->lon);
        $this->destination_gps_lat = $lat;
        $this->destination_gps_lon = $lon;

        $this->update();
    }
    public function setGPSStart()
    {
        $name = explode(" ", $this->Departure_Airport)[0];

        $url = ($this->formatGPSURL($name . " airport"));
        //dd($url);
        $res = Http::get($url);
        $json = json_decode($res->body())[0];
        $lat  = ($json->lat);
        $lon  = ($json->lon);
        $this->depart_gps_lat = $lat;
        $this->depart_gps_lon = $lon;

        $this->update();
    }
    public function setGPSCrash()
    {
        $name = strtolower($this->Incident_Location);

        $name = str_replace("ai...", "airport", $name);
        $name = str_replace("air...", "airport", $name);
        $name = str_replace("airp...", "airport", $name);
        $name = str_replace("airpo...", "airport", $name);
        $name = str_replace("airpor...", "airport", $name);
        $name = str_replace("airport...", "airport", $name);
        $this->Incident_Location = $name;

        $url = ($this->formatGPSURL($name));
        //dd($url);
        $res = Http::get($url);
        $json = json_decode($res->body())[0];
        $lat  = ($json->lat);
        $lon  = ($json->lon);
        $this->incident_gps_lat = $lat;
        $this->incident_gps_lon = $lon;

        $this->update();
    }

    private function formatGPSURL($place)
    {
        return (str_replace("{{}}", urlencode($place), $this->urlGPS));
    }

    public function setImage()
    {

        /* $nbrImageToKeep = 5;

        $url = "https://contextualwebsearch-websearch-v1.p.rapidapi.com/api/Search/ImageSearchAPI?q={{}}&pageNumber=1&pageSize=$nbrImageToKeep&autoCorrect=true";

        $url = str_replace("{{}}", urlencode($this->Aircaft_Model . " plane aircraft"), $url);


        //dd($url);

        //dd($url);

        $res = Http::withHeaders([
            'x-rapidapi-host' => 'contextualwebsearch-websearch-v1.p.rapidapi.com',
            'x-rapidapi-key' => '1e75c99c2fmsh4ab4e0c53ab1d16p1b9b70jsn09c6d64f72be'
        ])->get($url); */






    /* $search = $this->Aircaft_Model . " " . $this->Aircaft_Registration . " " . $this->Aircaft_Operator;
        //dd($search);
        $page = 3;
        $per_page = 10;
        $orientation = 'landscape';

        $res = \Unsplash\Search::photos($search, $page, $per_page, $orientation);

        $datas = $res->getResults(); 



        $search = $this->Aircaft_Model . " " . $this->Aircaft_Registration . " " . $this->Aircaft_Operator;
        $url = "https://www.google.com/search?tbm=isch&q=" . urlencode($search);

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_use_internal_errors(false);

        $tagsImg = $doc->getElementsByTagName("img");

        $images = [];

        for ($i = 0; $i < 10; $i++) {
            $img = $tagsImg->item($i);
            if ($img) {
                $src = $img->getAttribute('src');

                if (str_starts_with($src, "http")) {
                    $im = new Image();
                    $im->url = $src;
                    $images[] = $im;
                }
            }
        }

        $this->images()->saveMany($images);
    } */


    public function setCrashCountry()
    {
        $result = Http::get("http://api.positionstack.com/v1/reverse?access_key=d4a5ca7d9a3a90fc168894a55bf718f8&query=" . $this->incident_gps_lat . ", " . $this->incident_gps_lon . "&output=json");

        $data = json_decode($result);
        $data1 = $data->data[0];


        $this->incident_country = $data1->country;
        $this->incident_country_code = $data1->country_code;

        $this->update();
    }
}
