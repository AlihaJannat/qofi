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
        Schema::create('sw_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sw_shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('code');
            $table->enum('type', ['percent', 'fixed']);
            $table->integer('value');
            $table->integer('use_count')->nullable();
            $table->integer('max_limit')->nullable();
            $table->date('expired_at');
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
        Schema::dropIfExists('sw_coupons');
    }
};
