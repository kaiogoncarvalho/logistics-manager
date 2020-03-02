<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Gender;
use App\Enums\CNH;
use App\Services\DriverService;
use App\Models\Driver;

class SearchDriverByTripEmptyRequest extends FormRequest
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
            'start_trip_date' => 'date_format:Y-m-d|date|before:end_trip_date',
            'end_trip_date'   => 'date_format:Y-m-d|date'
        ];
    }
}
