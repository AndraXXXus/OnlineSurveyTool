<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('responder_id')->required()->index();
            $table->foreignUuid('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->foreignUuid('question_id')->references('id')->on('questions')->index();
            $table->foreignUuid('choice_id')->references('id')->on('choices');
            $table->string('answer_text',255)->required();
            $table->timestamp('question_started_at')->required();
            $table->timestamps();
            $table->unique(['responder_id','survey_id','question_id','choice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
