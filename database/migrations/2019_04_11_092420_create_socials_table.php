<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('social_id')->index();
            // ALTER TABLE `socials` CHANGE COLUMN `social_id` `social_id` VARCHAR(255);
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            // ALTER TABLE `socials` Add COLUMN `name` VARCHAR(255) DEFAULT NULL AFTER user_id;
            // ALTER TABLE `socials` Add COLUMN `avatar` VARCHAR(255) DEFAULT NULL AFTER user_id;
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('type')->default(1); //1wechat 2facebook
            $table->timestamps();

            $table->foreign('user_id')
               ->references('id')
               ->on('users')
               ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
