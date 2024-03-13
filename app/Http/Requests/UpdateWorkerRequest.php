<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerRequest extends FormRequest
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
            'phone_number' => ['required' , 'string'] ,
            'salary_rate' => ['required' , 'numeric' , 'min:1'] ,
            'position' =>  ['required' , 'in:brigadier,master,form_worker,fitter,worker'] ,
            'project_id' => ['required' , 'numeric' , 'min:1' , 'exists:projects,id'],
            'is_active' => ['required' , 'boolean'] ,
        ];
    }
}
