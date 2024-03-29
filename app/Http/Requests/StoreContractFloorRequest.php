<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractFloorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => ['required' , 'numeric' , 'min:0'] ,
            'square' => ['required' , 'numeric' , 'min:0'] ,
            'floor' => ['required' , 'string'] ,
            'contract_id' => ['required' , 'numeric' , 'min:1' , 'exists:contracts,id']
        ];
    }
}
