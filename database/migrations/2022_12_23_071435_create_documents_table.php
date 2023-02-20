<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('management_id')->unsigned()->index()->nullable();
            $table->bigInteger('railway_id')->unsigned()->index()->nullable();
            $table->bigInteger('organization_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_management_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_railway_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_organization_id')->unsigned()->index()->nullable();
            $table->bigInteger('send_user_id')->unsigned()->index()->nullable();
            $table->bigInteger('rec_user_id')->unsigned()->index()->nullable();
            $table->bigInteger('executor_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_executor_id')->unsigned()->index()->nullable();
            $table->bigInteger('type_document_id')->unsigned()->index()->nullable();
            $table->date('to_date')->nullable();
            $table->text('text_message')->nullable();
            $table->text('token_send')->nullable();
            $table->text('token_rec')->nullable();
            $table->boolean('status_send')->default(false);
            $table->boolean('status_doc')->default(false);
            $table->boolean('status_rec')->default(false);
            $table->boolean('file_status')->default(false);
            $table->string('file1')->nullable();
            $table->string('file2')->nullable();
            $table->string('file3')->nullable();
            $table->string('to_file1')->nullable();
            $table->string('to_file2')->nullable();
            $table->string('to_file3')->nullable();
            $table->text('comment')->nullable();
            $table->text('resullt')->nullable();
            $table->boolean('status')->default(false);
            $table->foreign('management_id')->references('id')->on('management');
            $table->foreign('railway_id')->references('id')->on('railways');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('to_management_id')->references('id')->on('management');
            $table->foreign('to_railway_id')->references('id')->on('railways');
            $table->foreign('to_organization_id')->references('id')->on('organizations');
            $table->foreign('send_user_id')->references('id')->on('users');
            $table->foreign('rec_user_id')->references('id')->on('users');
            $table->foreign('executor_id')->references('id')->on('users');
            $table->foreign('to_executor_id')->references('id')->on('users');
            $table->foreign('type_document_id')->references('id')->on('type_documents');
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
        Schema::dropIfExists('documents');
    }
}
