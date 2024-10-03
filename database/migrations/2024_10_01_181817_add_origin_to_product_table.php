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
            $table->bigInteger('sw_product_origin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_products', function (Blueprint $table) {
            $table->dropColumn('sw_product_origin_id');
            //
        });
    }
};
