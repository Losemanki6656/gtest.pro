<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerRelativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_relatives', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index()->nullable();
            $table->bigInteger('relative_id')->unsigned()->index()->nullable();
            $table->integer('sort')->nullable();
            $table->string('fullname')->nullable();
            $table->string('birth_place')->nullable();
            $table->text('post')->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(false);
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('relative_id')->references('id')->on('relatives');
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
        Schema::dropIfExists('worker_relatives');
    }
}
