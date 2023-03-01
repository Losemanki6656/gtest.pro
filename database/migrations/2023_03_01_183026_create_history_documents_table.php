<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('management_id')->unsigned()->index()->nullable();
            $table->bigInteger('railway_id')->unsigned()->index()->nullable();
            $table->bigInteger('organization_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_management_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_railway_id')->unsigned()->index()->nullable();
            $table->bigInteger('to_organization_id')->unsigned()->index()->nullable();
            $table->bigInteger('send_user_id')->unsigned()->index()->nullable();
            $table->bigInteger('rec_user_id')->unsigned()->index()->nullable();
            $table->bigInteger('document_id')->unsigned()->index()->nullable();
            $table->bigInteger('type_document_id')->unsigned()->index()->nullable();
            $table->bigInteger('type_history_id')->unsigned()->index()->nullable();
            $table->date('to_date')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('status_history')->default(false);
            $table->foreign('management_id')->references('id')->on('management');
            $table->foreign('railway_id')->references('id')->on('railways');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('to_management_id')->references('id')->on('management');
            $table->foreign('to_railway_id')->references('id')->on('railways');
            $table->foreign('to_organization_id')->references('id')->on('organizations');
            $table->foreign('send_user_id')->references('id')->on('users');
            $table->foreign('rec_user_id')->references('id')->on('users');
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('type_document_id')->references('id')->on('type_documents');
            $table->foreign('type_history_id')->references('id')->on('type_histories');
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
        Schema::dropIfExists('history_documents');
    }
}
