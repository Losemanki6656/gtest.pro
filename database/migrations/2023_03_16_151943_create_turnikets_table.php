<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurniketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnikets', function (Blueprint $table) {
            $table->id();
            $table->datetime('datetime')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('status')->nullable();
            $table->string('devicename')->nullable();
            $table->string('devicen')->nullable();
            $table->string('name')->nullable();
            $table->string('jshr')->nullable();
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
        Schema::dropIfExists('turnikets');
    }
}
