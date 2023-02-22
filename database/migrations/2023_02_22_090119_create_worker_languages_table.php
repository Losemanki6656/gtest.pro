<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_languages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index()->nullable();
            $table->bigInteger('language_id')->unsigned()->index()->nullable();
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('language_id')->references('id')->on('languages');
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
        Schema::dropIfExists('worker_languages');
    }
}
