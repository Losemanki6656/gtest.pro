<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('management_id')->unsigned()->index()->nullable();
            $table->bigInteger('railway_id')->unsigned()->index()->nullable();
            $table->string('name')->nullable();
            $table->text('fullname')->nullable();
            $table->boolean('status')->default(true);
            $table->foreign('management_id')->references('id')->on('management');
            $table->foreign('railway_id')->references('id')->on('railways');
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
        Schema::dropIfExists('organizations');
    }
}
