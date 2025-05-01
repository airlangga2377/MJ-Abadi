<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->string('telp');

            $table->datetime('tanggal_lahir');

            // $table->string('umur');

            $table->string('jenis_kelamin');

            $table->string('alamat');

            $table->string('email');

            $table->datetime('email_verified_at')->nullable();

            $table->string('password');
            
            $table->rememberToken();
            $table->string('api_token')->nullable(true);

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
