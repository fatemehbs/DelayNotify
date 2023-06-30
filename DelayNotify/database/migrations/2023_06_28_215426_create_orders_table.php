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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('status');
            $table->integer('delivery_time');
            $table->integer('total_price');
            $table->unsignedBigInteger('vendor_id');
            $table->boolean('in_delay_queue')->default(false);
            $table->integer('shipment_amount');
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('id')
                ->on('vendors');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
