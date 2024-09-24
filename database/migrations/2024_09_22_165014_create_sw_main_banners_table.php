<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('sw_main_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->boolean('has_button');
            $table->string('button_color')->nullable();
            $table->string('button_bg_color')->nullable();
            $table->string('button_text')->nullable();
            $table->integer('sort_order');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_main_banners');
    }
};
