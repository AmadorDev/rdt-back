<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineaVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linea_videos', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->boolean("status")->default(true);
            $table->boolean("outstanding")->default(false);
            $table->string('link', 200)->nullable();
            $table->unsignedBigInteger('linea_id');
            $table->foreign('linea_id')->references('id')->on('lineas')
                ->constrained();

            $table->timestamps();
        });

        Schema::create('linea_video_translations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('linea_video_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content');
            $table->unique(['linea_video_id', 'locale']);
            $table->foreign('linea_video_id')->references('id')->on('linea_videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linea_videos');
         Schema::dropIfExists('linea_video_translations');
    }
}
