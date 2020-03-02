<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lon;
use App\Rules\Lat;
use Illuminate\Validation\Rule;

class SearchTripRequest extends FormRequest
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
        $ruleIn = Rule::in(['driver_id', 'truck_id', 'loaded', 'trip_date']);
        return [
            'driver_id'       => 'integer|exists:drivers,id',
            'truck_id'        => 'integer|exists:trucks,id',
            'loaded'          => 'boolean',
            'start_trip_date' => 'date_format:Y-m-d H:i:s|before:end_trip_date',
            'end_trip_date'   => 'date_format:Y-m-d H:i:s|before_or_equal:now',
            'per_page'        => 'integer',
            'page'            => 'integer',
            'order'           => [
                'string',
                $ruleIn
            ],
            'orders'          => 'array',
            'orders.*'        => [
                'string',
                $ruleIn
            ],
        ];
    }
    
}
