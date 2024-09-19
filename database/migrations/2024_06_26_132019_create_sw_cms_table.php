<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sw_cms', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->longText('content');
        });

        $pages = ['term-n-condition', 'privacy-policy', 'faqs', 'about-us', 'how-it-works'];
        $data = [];
        foreach ($pages as $key => $page) {
            $data[] = [
                'page' => $page,
                'content' => ''
            ];
        }
        DB::table('sw_cms')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sw_cms');
    }
};
