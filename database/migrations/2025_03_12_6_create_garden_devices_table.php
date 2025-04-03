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
        Schema::create('garden_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('garden_id');
            $table->unsignedInteger('device_id');
            $table->timestamps();
            
            $table->foreign('garden_id')->references('id')->on('gardens')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garden_devices');
    }
};
