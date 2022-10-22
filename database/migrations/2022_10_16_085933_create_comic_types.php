<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comic_types', function (Blueprint $table) {
            $table->uuid('type_id');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->uuid('comic_id');
            $table->foreign('comic_id')->references('id')->on('comics')->onDelete('cascade');
            $table->primary(['type_id', 'comic_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comic_types');
    }
};
