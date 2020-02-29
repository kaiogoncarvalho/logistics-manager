<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Gender;
use App\Enums\CNH;

class UpdateDriverRequest extends FormRequest
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
        $id = $this->route('driver_id');
        
        return [
            'name'       => 'string|min:3',
            'cpf'        => [
                'cpf',
                "unique:drivers,cpf,{$id},id"
            ],
            'birth_date' => 'date',
            'gender'     => [
                Rule::in(Gender::getAll())
            ],
            'own_truck' => 'boolean',
            'cnh'       => [
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
