<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJanjiTemuTable extends Migration

{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('janji_temu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keluhan');
            $table->datetime('tanggal');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('janji_temu');
    }
};
