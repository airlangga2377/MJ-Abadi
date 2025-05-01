<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJanjiTemuServicePivotTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('janjitemu_service', function (Blueprint $table) {
            $table->unsignedInteger('janjitemu_id');

            $table->foreign('janjitemu_id', 'janjitemu_id_fk_05042004')->references('id')->on('janji_temu')->onDelete('cascade');

            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'user_id_fk_05042004')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
