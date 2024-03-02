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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->decimal('summa', 15 , 2)->default(0.00);
            $table->date('date')->nullable();
            $table->string('comment')->nullable();
            $table->enum('income_type' , ['cash' , 'transfer'])->default('cash');
            $table->enum('currency' , ['dollar' , 'sum'])->default('sum');
            $table->double('currency_rate')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
