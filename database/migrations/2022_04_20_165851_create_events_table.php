<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->boolean("status")->default(true);
            $table->string('date_event', 20);
            $table->string('url', 200)->nullable();
            $table->string('url_name', 20)->nullable();
      
            $table->timestamps();
        });

         Schema::create('event_translations', function (Blueprint $table) {
           $table->id();

            $table->unsignedBigInteger('event_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content');
            $table->unique(['event_id', 'locale']);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });


        Schema::create('event_images', function (Blueprint $table) {
            $table->id();
            $table->text("url");
            $table->string("name", 80)->nullable();
            $table->boolean("status")->default(true);
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')
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
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_translations');
    }
}
