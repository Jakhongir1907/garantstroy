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
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount' , 15 ,2)->nullable();
            $table->date('date')->default(now());
            $table->enum('type',['advance','salary'])->default('advance');
            $table->unsignedBigInteger('worker_account_id')->nullable();
            $table->foreign('worker_account_id')->references('id')->on('worker_accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
    }
};
