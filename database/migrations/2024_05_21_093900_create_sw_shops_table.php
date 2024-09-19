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
        Schema::create('sw_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('sw_vendors');
            $table->string('name');
            $table->string('image_name');
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->decimal('latitude', 10,8)->nullable();
            $table->decimal('longitude', 11,8)->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_shops');
    }
};
