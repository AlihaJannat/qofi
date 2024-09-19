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
        Schema::table('sw_products', function (Blueprint $table) {
            $table->after('in_stock', function(Blueprint $table) {
                $table->double('height')->nullable();
                $table->foreignId('sw_unit_id')->nullable()->constrained()->onDelete('set null');
                $table->double('price')->nullable();
                $table->integer('stock')->nullable();
                $table->double('discount')->nullable();
                $table->enum('discount_type', ['fixed', 'percent'])->nullable()->default('percent');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_products', function (Blueprint $table) {
            $table->dropForeign(['sw_unit_id']);
            $table->dropColumn(['height', 'sw_unit_id', 'price', 'stock', 'discount', 'discount_type']);
        });
    }
};
