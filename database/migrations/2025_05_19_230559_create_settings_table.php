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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->morphs('settingable');
            $table->string('key');
            $table->string('value')->nullable();
            $table->string('type')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamp('last_actived_at')->nullable();
            $table->timestamp('last_inactived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
