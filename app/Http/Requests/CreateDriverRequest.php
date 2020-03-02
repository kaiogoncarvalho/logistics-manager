<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Gender;
use App\Enums\CNH;

class CreateDriverRequest extends FormRequest
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
            'name'       => 'required|string|min:3',
            'cpf'        => [
                'required',
                'cpf',
                'unique:drivers,cpf'
            ],
            'birth_date' => 'required|date_format:Y-m-d|date|before:now',
            'gender'     => [
                'required',
                Rule::in(Gender::getAll())
            ],
            'own_truck' => 'required|boolean',
            'cnh'       => [
                'required',
                Rule::in(CNH::getAll())
            ]
        ];
    }
    
    public function messages()
    {
       return [
            'cpf.cpf' => 'Invalid cpf.'
       ];
    }
}
