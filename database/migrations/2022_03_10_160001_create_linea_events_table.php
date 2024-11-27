<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineaEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linea_events', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->boolean("status")->default(true);
            $table->string('date_event', 20)->nullable();
            $table->string('url', 200)->nullable();
            $table->string('url_name', 20)->nullable();
            $table->unsignedBigInteger('linea_id');
            $table->foreign('linea_id')->references('id')->on('lineas')
                ->constrained();

            $table->timestamps();
        });

         Schema::create('linea_event_translations', function (Blueprint $table) {
           $table->id();

            $table->unsignedBigInteger('linea_event_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content')->nullable();
            $table->unique(['linea_event_id', 'locale']);
            $table->foreign('linea_event_id')->references('id')->on('linea_events')->onDelete('cascade');
        });

        Schema::create('testing_images', function (Blueprint $table) {
            $table->id();
            $table->text("url");
            $table->string("name", 80)->nullable();
            $table->boolean("status")->default(true);
            $table->unsignedBigInteger('linea_event_id');
            $table->foreign('linea_event_id')->references('id')->on('linea_events');
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
        Schema::dropIfExists('linea_events');
          Schema::dropIfExists('linea_event_translations');
          Schema::dropIfExists('testing_images');
    }
}
