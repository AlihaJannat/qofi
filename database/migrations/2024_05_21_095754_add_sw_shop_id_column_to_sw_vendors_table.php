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
        Schema::table('sw_vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('sw_shop_id')->nullable()->after('id')->comment("This will show to which shop user has access");

            $table->foreign('sw_shop_id')->references('id')->on('sw_shops')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_vendors', function (Blueprint $table) {
            $table->dropForeign(['sw_shop_id']);
            $table->dropColumn('sw_shop_id');
        });
    }
};
