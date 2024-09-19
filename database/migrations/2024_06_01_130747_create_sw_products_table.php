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
        Schema::create('sw_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sw_shop_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('image_name');
            $table->text('short_description');
            $table->mediumText('long_description');
            $table->integer('country_id');
            $table->boolean('in_stock')->default(true);
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
        Schema::dropIfExists('sw_products');
    }
};
