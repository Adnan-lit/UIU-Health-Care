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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('payment_id');
            $table->string('trx_id');
            $table->string('payer_reference');
            $table->string('merchant_invoice_number');
            $table->string('amount');
            $table->string('currency');
            $table->string('type');
            $table->string('identifier');
            $table->string('payer_account');
            // Foreign Key to Users Table
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
