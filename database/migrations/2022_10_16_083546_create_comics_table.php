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
        Schema::create('comics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 250);
            $table->string('comic_id', 25);
            $table->uuid('thumbnail_id');
            $table->foreign('thumbnail_id')->references('id')->on('images')->onDelete('cascade');
            $table->uuid('banner_id');
            $table->foreign('banner_id')->references('id')->on('images')->onDelete('cascade');
            $table->text('description');
            $table->integer('status')->default(0);
            $table->integer('count_chap')->default(0)->nullable();
            $table->uuid('author_id');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
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
        Schema::dropIfExists('comics');
    }
};
