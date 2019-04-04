<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户id');
            $table->string('name')->nullable()->coment('真名');
            $table->boolean('sex')->default(0);
            $table->timestamp('birthday')->nullable();
            $table->string('telephone', 22)->unique()->index();//用来登陆账户9-13 with(+)86
            $table->unsignedBigInteger('recommend_uid')->nullable()->comment('用户关系');
            //country. see telephone with(+)86
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
               ->references('id')->on('users')
               ->onDelete('cascade');

            $table->foreign('recommend_uid')
               ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
