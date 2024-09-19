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
            $table->boolean('has_variation')->default(false);
            $table->integer('parent_variation')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_products', function (Blueprint $table) {
            $table->dropColumn('has_variation');
            $table->dropColumn('parent_variation');
        });
    }
};
