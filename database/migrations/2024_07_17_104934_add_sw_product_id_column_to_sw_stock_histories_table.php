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
        Schema::table('sw_stock_histories', function (Blueprint $table) {
            $table->dropForeign(['sw_product_height_id']);
            $table->dropColumn('sw_product_height_id');
            $table->foreignId('sw_product_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_stock_histories', function (Blueprint $table) {
            $table->dropForeign(['sw_product_id']);
            $table->dropColumn('sw_product_id');
            $table->foreignId('sw_product_height_id')->constrained()->onDelete('cascade');
        });
    }
};
