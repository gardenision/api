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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('device_type_id');
            $table->string('name');
            $table->string('serial_number');
            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('device_type_id')->references('id')->on('device_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
