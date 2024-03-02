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
        Schema::create('hired_worker_expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('summa' , 15 , 2)->nullable();
            $table->date('date')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('hired_worker_id')->nullable();
            $table->foreign('hired_worker_id')->references('id')->on('hired_workers');
            $table->enum('currency' , ['dollar' , 'sum'])->default('sum');
            $table->double('currency_rate')->default(1);
            $table->decimal('amount' , 15 ,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hired_worker_expenses');
    }
};
