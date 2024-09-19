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
        Schema::create('sw_shop_day_times', function (Blueprint $table) {
            $table->foreignId('sw_shop_day_id')->constrained()->onDelete('cascade');
            $table->foreignId('sw_time_id')->constrained()->onDelete('cascade');

            $table->primary(['sw_shop_day_id', 'sw_time_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_shop_day_times');
    }
};
