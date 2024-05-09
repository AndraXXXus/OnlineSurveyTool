<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->unsignedInteger('choice_position')->nullable();
            $table->string('choice_label',255)->nullable();
            $table->string('choice_text',255)->required();
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //$table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->unique(["question_id", "choice_position"], 'question_id_position_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('choices');
    }
}
