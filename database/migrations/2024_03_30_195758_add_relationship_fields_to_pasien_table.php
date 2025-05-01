<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPasienTable extends Migration
{
    public function up()
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->unsignedInteger('janji_temu_id');

            $table->foreign('janji_temu_id', 'janji_temu_id')->references('id')->on('janji_temu')->onDelete('cascade');
        });
    }
}
