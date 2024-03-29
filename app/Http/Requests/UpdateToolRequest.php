<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateToolRequest extends FormRequest
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
            'name' => ['required' , 'string'] ,
            'image_name' => ['required' , 'string'] ,
            'image_url' => ['required' , 'string'] ,
            'price' => ['required' , 'numeric' , 'min:1'] ,
            'state' =>  ['required' , 'in:active,inactive'] ,
            'project_id' => ['required' , 'numeric' , 'min:1' , 'exists:projects,id']
        ];
    }
}
