<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarExpense extends Model
{
    use HasFactory;
    protected $fillable = ['summa' , 'date' , 'comment'];
}
