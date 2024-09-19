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
        Schema::create('sw_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sw_order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sw_product_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->double('price');
            $table->string('options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_order_products');
    }
};
