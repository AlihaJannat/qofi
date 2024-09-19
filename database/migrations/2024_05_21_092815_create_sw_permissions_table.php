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
        Schema::create('sw_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module');
            $table->enum('role_module',['user', 'vendor'])->default('vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_permissions');
    }
};
