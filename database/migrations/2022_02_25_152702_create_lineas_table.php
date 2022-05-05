<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineas', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->boolean("status")->default(true);
            $table->boolean("featured")->default(false);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('linea_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('linea_id');
            $table->string("name", 50);
            $table->string("short_name", 80)->nullable();
            $table->text("description");
            $table->string('locale')->index();
            $table->unique(['linea_id', 'locale']);
            $table->foreign('linea_id')->references('id')->on('lineas')->onDelete('cascade');
        });

        Schema::create('lineas_image', function (Blueprint $table) {
            $table->id();
            $table->text("url");
            $table->string("name", 80)->nullable();
            $table->boolean("status")->default(true);
            $table->boolean("cover")->default(false);
            $table->unsignedBigInteger('linea_id');
            $table->foreign('linea_id')->references('id')->on('lineas')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
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
        Schema::dropIfExists('lineas');
        Schema::dropIfExists('lineas_image');
    }
}
