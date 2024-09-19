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
        Schema::table('sw_order_addresses', function (Blueprint $table) {
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->text('address')->nullable()->after('city');
        });
        Schema::table('sw_user_addresses', function (Blueprint $table) {
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->text('address')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_order_addresses', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('state');
            $table->dropColumn('city');
        });
        Schema::table('sw_user_addresses', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('state');
            $table->dropColumn('city');
        });
    }
};
