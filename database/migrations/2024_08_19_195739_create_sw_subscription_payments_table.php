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
        Schema::create('sw_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('sw_user_subscription_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('currency');
            $table->string('charge_id');
            $table->string('payment_channel');
            $table->double('amount');
            $table->double('refund_amount')->nullable();
            $table->text('refund_note')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_subscription_payments');
    }
};
