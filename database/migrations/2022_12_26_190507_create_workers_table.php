<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('document_id')->unsigned()->index()->nullable();
            $table->bigInteger('education_id')->unsigned()->index()->nullable();
            $table->bigInteger('region_id')->unsigned()->index()->nullable();
            $table->bigInteger('city_id')->unsigned()->index()->nullable();
            $table->bigInteger('nationality_id')->unsigned()->index()->nullable();
            $table->bigInteger('academic_degree_id')->unsigned()->index()->nullable();
            $table->bigInteger('academic_title_id')->unsigned()->index()->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('staff_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('rail_date')->nullable();
            $table->boolean('rail_status')->default(false);
            $table->string('old_job_name')->nullable();
            $table->string('del_rail_comment')->nullable();
            $table->string('passport')->nullable();
            $table->string('jshshir')->nullable();
            $table->string('other_doc')->nullable();
            $table->string('file1')->nullable();
            $table->string('file2')->nullable();
            $table->string('file3')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('status_worker')->default(false);
            $table->boolean('status')->default(false);
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('education_id')->references('id')->on('education');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('nationality_id')->references('id')->on('nationalities');
            $table->foreign('academic_degree_id')->references('id')->on('academic_degrees');
            $table->foreign('academic_title_id')->references('id')->on('academic_titles');
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
        Schema::dropIfExists('workers');
    }
}
