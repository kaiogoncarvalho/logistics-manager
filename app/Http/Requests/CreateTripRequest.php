<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lon;
use App\Rules\Lat;

class CreateTripRequest extends FormRequest
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
            'driver_id'   => 'required|integer|exists:drivers,id',
            'truck_id'    => 'required|integer|exists:trucks,id',
            'loaded'      => 'required|boolean',
            'origin'      => 'required|array',
            'origin.lon'  => [
                'required',
                new Lon()
            ],
            'origin.lat'  => [
                'required',
                new Lat()
            ],
            'destiny'     => 'required|array',
            'destiny.lon' => [
                'required',
                new Lon()
            ],
            'destiny.lat' => [
                'required',
                new Lat()
            ],
            'trip_date'   => 'date_format:Y-m-d H:i:s|before_or_equal:now'
        ];
    }
    
}
