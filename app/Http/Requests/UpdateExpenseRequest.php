<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'comment' => ['required' , 'string'] ,
            'user_id' => ['required' , 'exists:users,id'],
            'project_id' => ['required' , 'exists:projects,id'],
            'date' => ['required' , 'date'] ,
            'expense_type' => ['required' , 'in:cash,transfer'] ,
            'category' => ['required' , 'in:salary,food,tool,other'] ,
            'currency' => ['required' , 'in:sum,dollar'] ,
            'currency_rate'  => ['required' ,'numeric' , 'min:1'] ,
            'summa'  => ['required' ,'numeric' , 'min:1'] ,
        ];
    }
}
