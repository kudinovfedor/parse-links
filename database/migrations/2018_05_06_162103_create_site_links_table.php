<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 255);
            $table->boolean('external')->default(false);
            $table->unsignedInteger('site_id');
            $table->foreign('site_id')->references('id')->on('parse_sites');
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
        Schema::dropIfExists('site_links');
    }
}
