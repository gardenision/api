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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('device_type_id');
            $table->string('name');
            $table->string('type');
            $table->string('default_unit_type')->nullable();
            $table->string('default_unit_value');
            $table->timestamps();

            $table->foreign('device_type_id')->references('id')->on('device_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
