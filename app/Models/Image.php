<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Image extends Model
{
    use HasFactory;

    protected $table = "images";

    protected $hidden = [
        "url",
        "local_url",
        "created_at",
        "updated_at",

    ];

    protected $appends = [
        "link",
        "full_link",
        //"size",
    ];

    protected function getSizeAttribute()
    {
        $path = "public/planes/" . $this->crash_id . "/" . $this->id . ".jpg";
        if (!Storage::exists($path)) return [];
        $imageData = Storage::get($path);

        $width = getimagesize($imageData)[0]; // getting the image width
        $height = getimagesize($imageData)[1]; // getting the image height

        return [
            'width' => $width,
            'height' => $height,
        ];
    }

    protected function getLinkAttribute()
    {
        return $this->local_url;
        //return env("APP_URL") . "/storage/planes/" . $this->crash_id . "/" . $this->id . ".jpg";
    }
    protected function getFullLinkAttribute()
    {
        return env('MIX_DATA_URL') . $this->local_url;
        //return env("APP_URL") . "/storage/planes/" . $this->crash_id . "/" . $this->id . ".jpg";
    }
}
