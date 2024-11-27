<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_videos', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->boolean("status")->default(true);
            $table->boolean("outstanding")->default(false);
            $table->string('link', 200)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')
                ->constrained();
                $table->timestamps();
        });

         Schema::create('product_video_translations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_video_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content');
            $table->unique(['product_video_id', 'locale']);
            $table->foreign('product_video_id')->references('id')->on('product_videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_videos');
        Schema::dropIfExists('product_video_translations');
    }
}
