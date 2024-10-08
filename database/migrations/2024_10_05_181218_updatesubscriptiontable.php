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
        Schema::table('sw_subscriptions', function (Blueprint $table) {

            $table->renameColumn('description', 'short_description');

            $table->text('long_description')->after('description')->nullable();
            $table->integer('shops_count')->after('long_description')->default(0);
            $table->integer('cups_count')->after('shops_count')->default(0);
            $table->string('coffee_type')->after('cups_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_subscriptions', function (Blueprint $table) {

            $table->renameColumn('short_description', 'description');

            $table->dropColumn(['long_description', 'shops_count', 'cups_count', 'coffee_type']);
        });
    }
};
