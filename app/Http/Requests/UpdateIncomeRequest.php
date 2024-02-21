<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomeRequest extends FormRequest
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
            'project_id' => ['required' , 'numeric' ,'min:1' , 'exists:projects,id'] ,
            'summa'  => ['required' ,'numeric' , 'min:1'] ,
            'comment' => ['required' , 'string'] ,
            'date' => ['required' , 'date'] ,
            'income_type' => ['required' , 'in:cash,transfer'] ,
            'currency' => ['required' , 'in:sum,dollar'] ,
            'currency_rate'  => ['required' ,'numeric' , 'min:1'] ,
        ];
    }
}
