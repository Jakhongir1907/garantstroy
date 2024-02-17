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
        Schema::create('house_trade_expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('summa' , 15 , 2)->nullable();
            $table->date('date')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('house_trade_id')->nullable();
            $table->foreign('house_trade_id')->references('id')->on('house_trades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_trade_expenses');
    }
};
