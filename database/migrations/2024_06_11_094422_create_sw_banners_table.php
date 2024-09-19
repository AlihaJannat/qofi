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
        Schema::create('sw_banners', function (Blueprint $table) {
            $table->id();
            $table->string('page')->nullable();
            $table->string('image_name');
            $table->string('banner_text')->nullable();
            $table->string('url')->nullable();
            $table->integer('sort_order')->nullable();
            $table->enum('type', ['promotional', 'simple'])->default('simple');
            $table->boolean('status')->default(true);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_banners');
    }
};
