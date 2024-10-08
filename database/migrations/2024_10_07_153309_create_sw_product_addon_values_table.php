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
        Schema::create('sw_product_addon_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sw_addon_id')->constrained('sw_product_addons')->onDelete('cascade');
            $table->string('addon_value');
            $table->decimal('price', 8, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('sw_shop_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_product_addon_values');
    }
};
