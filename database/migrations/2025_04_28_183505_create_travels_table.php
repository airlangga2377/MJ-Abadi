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
        $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('destinations');
            $table->timestamps();
            $table->softDeletes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
