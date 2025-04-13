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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->datetime('purchase_date');
            $table->bigInteger('account_id');
            $table->string('purchase_product');
            $table->bigInteger('member_id');
            $table->integer('total_price');
            $table->integer('total_payment');
            $table->integer('change');
            $table->integer('used_point');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
