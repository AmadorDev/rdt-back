<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->string("slug", 80);
            $table->string('name',150);
            $table->string('district',100);
            $table->string('city',100);
            $table->string('address',150);
            $table->string('country',50);
       
            $table->boolean('check_google_map')->default(false);
            $table->decimal('lat',11,8);
            $table->decimal('lng',11,8);
            $table->boolean("status")->default(true);

            $table->timestamps();
        });

        // Schema::create('salon_translations', function (Blueprint $table) {
        //     $table->id();

        //     $table->unsignedBigInteger('product_video_id');
        //     $table->string('locale')->index();
        //     $table->string('title');
        //     $table->text('content');
        //     $table->unique(['product_video_id', 'locale']);
        //     $table->foreign('product_video_id')->references('id')->on('product_videos')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salons');
    }
}
