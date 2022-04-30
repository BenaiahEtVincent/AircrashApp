<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'incident_gps_lat' => 'required|numeric',
            'incident_gps_lon' => 'required|numeric',
            'depart_gps_lat' => 'required|numeric',
            'depart_gps_lon' => 'required|numeric',
            'destination_gps_lat' => 'required|numeric',
            'destination_gps_lon' => 'required|numeric',
        ];
    }

    public function messages() {
        return  [
            'incident_gps_lat.required' => 'le coordonnée GPS du crash en latitude est requise.',
            'incident_gps_lon.required' => 'le coordonnée GPS du crash en longitude est requise.',
            'depart_gps_lat.required' => 'le coordonnée GPS du départ en latitude est requise.',
            'depart_gps_lon.required' => 'le coordonnée GPS du départ en longitude est requise.',
            'destination_gps_lat.required' => 'le coordonnée GPS de la destination en latitude est requise.',
            'destination_gps_lon.required' => 'le coordonnée GPS de la destination en longitude est requise.',
            'incident_gps_lat.numeric' => 'le coordonnée GPS du crash en latitude doit être un nombre.',
            'incident_gps_lon.numeric' => 'le coordonnée GPS du crash en longitude doit être un nombre.',
            'depart_gps_lat.numeric' => 'le coordonnée GPS du départ en latitude doit être un nombre.',
            'depart_gps_lon.numeric' => 'le coordonnée GPS du départ en longitude doit être un nombre.',
            'destination_gps_lat.numeric' => 'le coordonnée GPS de la destination en latitude doit être un nombre.',
            'destination_gps_lon.numeric' => 'le coordonnée GPS de la destination en longitude doit être un nombre.',
        ];
    }
}
