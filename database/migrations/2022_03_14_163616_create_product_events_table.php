<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_events', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->boolean("status")->default(true);
            $table->string('date_event', 20);
            $table->string('url', 200)->nullable();
            $table->string('url_name', 20)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')
                ->constrained();

            $table->timestamps();
        });
        Schema::create('product_event_translations', function (Blueprint $table) {
           $table->id();

            $table->unsignedBigInteger('product_event_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content');
            $table->unique(['product_event_id', 'locale']);
            $table->foreign('product_event_id')->references('id')->on('product_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_events');
        Schema::dropIfExists('product_event_translations');
    }
}
