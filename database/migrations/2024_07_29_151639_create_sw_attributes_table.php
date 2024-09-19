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
        Schema::create('sw_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('sw_product_attribute_set_id');
            $table->string('title');
            $table->string('slug');
            $table->string('image');
            $table->integer('status');            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_attributes' , function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
