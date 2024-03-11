<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('summa' , 15 , 2)->nullable();
            $table->date('date')->nullable();
            $table->text('comment')->nullable();
            $table->enum('currency' , ['dollar' , 'sum'])->default('sum');
            $table->double('currency_rate')->default(1);
            $table->decimal('amount' , 15 ,2)->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->enum('category' , ['salary' , 'food' , 'tool' , 'other'])->default('salary');
            $table->enum('expense_type' , ['cash' , 'transfer'])->default('cash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
