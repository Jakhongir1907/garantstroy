<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseTradeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'summa' , 'date' , 'comment' , 'house_trade_id'
    ];

    public function houseTrade(){
        return $this->belongsTo(HouseTrade::class);
    }
}
