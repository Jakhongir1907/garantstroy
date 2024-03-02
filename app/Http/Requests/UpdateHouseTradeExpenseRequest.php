<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHouseTradeExpenseRequest extends FormRequest
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
            'summa' => ['required' , 'numeric']  ,
            'date' => ['required' , 'date']  ,
            'comment' => ['string']  ,
            'house_trade_id' => ['required' , 'numeric' , 'min:1' , 'exists:house_trades,id'] ,
            'currency' => ['required' , 'in:sum,dollar'] ,
            'currency_rate'  => ['required' ,'numeric' , 'min:1'] ,
        ];
    }
}
