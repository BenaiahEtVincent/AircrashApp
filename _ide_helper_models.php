<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Image
 *
 * @property int $id
 * @property int|null $crash_id
 * @property string|null $url
 * @property string|null $local_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCrashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereLocalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUrl($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Incident
 *
 * @property int $id
 * @property string|null $crash_date
 * @property string|null $aircaft_model
 * @property string|null $aircaft_registration
 * @property string|null $aircaft_operator
 * @property string|null $aircaft_nature
 * @property string|null $incident_category
 * @property string|null $incident_Causes
 * @property string|null $incident_location
 * @property float|null $incident_gps_lat
 * @property float|null $incident_gps_lon
 * @property string|null $incident_country
 * @property string|null $incident_country_code
 * @property string|null $aircaft_damage_type
 * @property string|null $aircaft_engines
 * @property string|null $onboard_crew
 * @property string|null $onboard_passengers
 * @property string|null $onboard_total
 * @property int|null $fatalities
 * @property string|null $aircaft_first_flight
 * @property string|null $aircraft_phase
 * @property string|null $departure_airport
 * @property float|null $depart_gps_lat
 * @property float|null $depart_gps_lon
 * @property string|null $destination_airport
 * @property float|null $destination_gps_lat
 * @property float|null $destination_gps_lon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|Incident newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Incident newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Incident query()
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftDamageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftEngines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftFirstFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftNature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircaftRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereAircraftPhase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereCrashDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereDepartGpsLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereDepartGpsLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereDepartureAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereDestinationAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereDestinationGpsLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereDestinationGpsLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereFatalities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentCauses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentGpsLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentGpsLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereIncidentLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereOnboardCrew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereOnboardPassengers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereOnboardTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Incident workable()
 */
	class Incident extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}

