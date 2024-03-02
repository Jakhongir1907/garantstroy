<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
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
            'block' => ['required' , 'string'] ,
            'project_id' => ['required' , 'numeric' , 'min:1' ,'exists:projects,id'] ,
            'currency' => ['required' , 'in:dollar,sum']
        ];
    }
}
