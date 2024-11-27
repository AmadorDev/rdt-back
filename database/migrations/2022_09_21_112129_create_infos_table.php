<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('linea_id');
            $table->foreign('linea_id')->references('id')->on('lineas')
                ->constrained();
            $table->timestamps();
           
        });
        Schema::create('info_translations', function (Blueprint $table) {
            $table->id();
 
             $table->unsignedBigInteger('info_id');
             $table->string('locale')->index();
             $table->text('content');
             $table->unique(['info_id', 'locale']);
             $table->foreign('info_id')->references('id')->on('infos')->onDelete('cascade');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infos');
        Schema::dropIfExists('info_translations');
    }
}
