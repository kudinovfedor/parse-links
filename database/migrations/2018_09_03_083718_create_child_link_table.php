<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_link', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('link_id');
            $table->unsignedInteger('child_id');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('childs')->onDelete('cascade');
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
        Schema::dropIfExists('child_link');
    }
}
