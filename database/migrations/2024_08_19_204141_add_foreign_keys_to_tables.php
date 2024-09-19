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
        Schema::table('sw_orders', function (Blueprint $table) {
            $table->foreign('sw_order_payment_id')->references('id')->on('sw_order_payments')->onDelete('cascade');
            $table->foreign('sw_order_address_id')->references('id')->on('sw_order_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sw_orders', function (Blueprint $table) {
            $table->dropForeign(['sw_order_payment_id']);
            $table->dropForeign(['sw_order_address_id']);
        });
    }
};
