<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('关联登陆用户id');
            $table->string('name');
            // 学校logo is user->avatar
            $table->text('remark')->comment('学校介绍')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')
               ->references('id')->on('users')
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
        Schema::dropIfExists('schools');
    }
}
