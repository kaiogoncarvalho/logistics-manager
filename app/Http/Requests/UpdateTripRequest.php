<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lon;
use App\Rules\Lat;

class UpdateTripRequest extends FormRequest
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
            'driver_id'   => 'integer|exists:drivers,id',
            'truck_id'    => 'integer|exists:trucks,id',
            'loaded'      => 'boolean',
            'origin'      => 'array',
            'origin.lon'  => [
                new Lon()
            ],
            'origin.lat'  => [
                new Lat()
            ],
            'destiny'     => 'array',
            'destiny.lon' => [
                new Lon()
            ],
            'destiny.lat' => [
                new Lat()
            ],
            'trip_date'   => 'date_format:Y-m-d H:i:s|before_or_equal:now'
        ];
    }
    
}
