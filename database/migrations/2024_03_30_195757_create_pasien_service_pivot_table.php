<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienServicePivotTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pasien_service', function (Blueprint $table) {
            $table->unsignedInteger('pasien_id');
            $table->foreign('pasien_id', 'pasien_id_fk_195757')->references('id')->on('pasien')->onDelete('cascade');

            $table->unsignedInteger('janji_temu_id');
            $table->foreign('janji_temu_id', 'janji_temu_id_fk_195757')->references('id')->on('janji_temu')->onDelete('cascade');
            
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_195757')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
