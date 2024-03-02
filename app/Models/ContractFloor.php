<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractFloor extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor' , 'price' , 'square' , 'contract_id' , 'amount'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class);
    }
}
