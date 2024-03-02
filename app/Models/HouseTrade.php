<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseTrade extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_name' , 'image_url' , 'name' , 'address'
    ];

    public function houseTradeExpenses(){
        return $this->hasMany(HouseTradeExpense::class);
    }



}
