<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoveltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novelties', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80)->unique();
            $table->boolean("status")->default(true);
           
            $table->timestamps();
        });

        Schema::create('novelty_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('novelty_id');
            $table->string("title", 50);
            $table->text("content");
            $table->string('locale')->index();
            $table->unique(['novelty_id', 'locale']);
            $table->foreign('novelty_id')->references('id')->on('novelties')->onDelete('cascade');
        });

        Schema::create('novelty_image', function (Blueprint $table) {
            $table->id();
            $table->text("url");
            $table->string("name", 80)->nullable();
            $table->boolean("status")->default(true);
            $table->boolean("cover")->default(false);
            $table->unsignedBigInteger('novelty_id');
            $table->foreign('novelty_id')->references('id')->on('novelties')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('novelties');
    }
}
