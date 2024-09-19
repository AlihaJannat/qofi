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
        Schema::create('sw_product_color', function (Blueprint $table) {
            $table->foreignId('sw_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('sw_color_id')->constrained()->onDelete('cascade');

            $table->primary(['sw_product_id', 'sw_color_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_product_color');
    }
};
