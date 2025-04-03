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
        Schema::create('garden_device_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('garden_device_id');
            $table->unsignedInteger('module_id');
            $table->boolean('is_active');
            $table->string('unit_value');
            $table->string('unit_type');
            $table->timestamps();
            
            $table->foreign('garden_device_id')->references('id')->on('garden_devices')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garden_device_modules');
    }
};
