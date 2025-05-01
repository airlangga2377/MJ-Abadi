<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->increments('id');
            $table->string('diagnosa');
            $table->string('penanganan');
            $table->longText('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
