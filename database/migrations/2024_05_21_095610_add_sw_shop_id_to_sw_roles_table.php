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
        Schema::table('sw_roles', function (Blueprint $table) {
            $table->foreignId('sw_shop_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_roles', function (Blueprint $table) {
            $table->dropForeign(['sw_shop_id']);
            $table->dropColumn('sw_shop_id');
        });
    }
};
