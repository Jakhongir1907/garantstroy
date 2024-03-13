<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;
    protected $fillable = ['comment' , 'date' , 'expense_id' ,'summa'];

    public function expense(){
        return $this->belongsTo(Expense::class);
    }
}
