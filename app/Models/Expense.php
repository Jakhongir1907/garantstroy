<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment' , 'project_id' , 'user_id' , 'date' ,
        'amount' ,'currency' , 'currency_rate' , 'summa' , 'category' ,'income_type'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function expenseItems(){
        return $this->hasMany(ExpenseItem::class);
    }
}
