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
        Schema::create('sw_product_heights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sw_product_id')->constrained()->onDelete('cascade');
            $table->double('value');
            $table->foreignId('sw_unit_id')->constrained()->onDelete('cascade');
            $table->double('price');
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_product_heights');
    }
};
