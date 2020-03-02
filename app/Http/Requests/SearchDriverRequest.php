<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Gender;
use App\Enums\CNH;
use App\Services\DriverService;
use App\Models\Driver;

class SearchDriverRequest extends FormRequest
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
        $fields = app(Driver::class)->getFillable();
        return [
            'start_birth_date' => 'date_format:Y-m-d|date|before:end_birth_date',
            'end_birth_date'   => 'date_format:Y-m-d|date',
            'name'             => 'string',
            'own_truck'        => 'boolean',
            'gender'           =>
                [
                    'string',
                    Rule::in(Gender::getAll())
                ],
            'genders'          => 'array',
            'genders.*'        => Rule::in(Gender::getAll()),
            'cnh'              => [
                'string',
                Rule::in(CNH::getAll())
            ],
            'cnhs'             => 'array',
            'cnhs.*'           => Rule::in(CNH::getAll()),
            'per_page'         => 'integer',
            'page'             => 'integer',
            'order'            => [
                'string',
                Rule::in($fields)
            ],
            'orders'           => 'array',
            'orders.*'         => [
                'string',
                Rule::in($fields)
            ],
        ];
    }
}
