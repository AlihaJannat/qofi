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
        Schema::create('sw_subscription_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sw_shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sw_subscription_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_subscription_shops');
    }
};
