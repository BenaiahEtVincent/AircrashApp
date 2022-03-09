<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Incident extends Model
{
    use HasFactory;

    protected $table = "Aircraft_Incident_Dataset";

    protected $hidden = [
        "incident_gps_lat",
        "incident_gps_lon",
        "depart_gps_lat",
        "depart_gps_lon",
        "end_gps_lat",
        "end_gps_lon",
        "created_at",
        "updated_at",
        "Incident_Date",
    ];

    protected $appends = [
        "gps_crash",
        "gps_start",
        "gps_end",
    ];

    protected function gpsCrash(): Attribute
    {
        return new Attribute(
            get: fn () => [
                "lat" => $this->incident_gps_lat,
                "lon" => $this->incident_gps_lon,
            ],
        );
    }
    protected function gpsStart(): Attribute
    {
        return new Attribute(
            get: fn () => [
                "lat" => $this->depart_gps_lat,
                "lon" => $this->depart_gps_lon,
            ],
        );
    }
    protected function gpsEnd(): Attribute
    {
        return new Attribute(
            get: fn () => [
                "lat" => $this->end_gps_lat,
                "lon" => $this->end_gps_lon,
            ],
        );
    }


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
        $this->incident_date_updated = $datetime;
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
        $this->end_gps_lat = $lat;
        $this->end_gps_lon = $lon;

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
}
