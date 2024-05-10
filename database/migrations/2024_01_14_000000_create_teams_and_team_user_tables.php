<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsAndTeamUserTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->string('team_name', 255)->required();
            $table->timestamps();
            $table->softDeletes();
            $table->string('cover_image_path')->nullable();
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignUuid('team_id')->references('id')->on('teams');
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->primary(['team_id', 'user_id']);
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
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
}
