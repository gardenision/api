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
        Schema::table('analytics', function (Blueprint $table) {
            $table->integer('analyticable_id')->nullable()->change();
            $table->string('analyticable_type')->nullable()->change();
            $table->timestamp('timestamp')->useCurrent()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
