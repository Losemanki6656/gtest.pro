<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerDriverLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_driver_licenses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index()->nullable();
            $table->bigInteger('driver_license_id')->unsigned()->index()->nullable();
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('driver_license_id')->references('id')->on('driver_licenses');
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
        Schema::dropIfExists('worker_driver_licenses');
    }
}
