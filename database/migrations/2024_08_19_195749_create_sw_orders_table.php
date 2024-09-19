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
        Schema::create('sw_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sw_order_payment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sw_order_address_id')->nullable()->constrained()->onDelete('cascade');
            $table->double('amount');
            $table->double('vat_amount');
            $table->double('service_charges');
            $table->string('coupon_code')->nullable();
            $table->double('discount_amount');
            $table->double('sub_total');
            $table->string('delivery_day');
            $table->string('delivery_time');
            $table->date('delivery_date');
            $table->boolean('is_completed');
            $table->string('order_status');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_orders');
    }
};
