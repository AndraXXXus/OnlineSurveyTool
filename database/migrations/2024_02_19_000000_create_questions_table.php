<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('question_position')->nullable();
            $table->string('question_text',255)->required();
            $table->string('question_type',255)->required();
            $table->boolean('question_required')->default(false);
            $table->string('cover_image_path')->nullable();
            $table->string('youtube_id')->nullable();
            $table->boolean('video_no_controlls')->default(false);
            $table->boolean('prefer_video')->default(false);
            $table->timestamps();
            $table->softDeletes();
            // $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->unique(["survey_id", "question_position"], 'survey_id_position_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
