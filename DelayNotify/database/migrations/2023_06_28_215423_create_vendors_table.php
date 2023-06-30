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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('phone_number');
            $table->integer('type');
            $table->integer('status');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('banner_image');
            $table->integer('delivery_fee')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
