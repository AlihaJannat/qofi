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
        Schema::table('sw_product_heights', function (Blueprint $table) {
            $table->integer('stock')->default(0);
            $table->double('discount')->default(0);
            $table->enum('discount_type', ['fixed', 'percent'])->default('percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_product_heights', function (Blueprint $table) {
            $table->dropColumn('stock');
            $table->dropColumn('discount');
            $table->dropColumn('discount_type');
        });
    }
};
