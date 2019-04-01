<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('关联登陆用户id');
            // 0代表幼儿园 1-9年级 高中1-3(10-12) 大学1-4(13-16) 17成人
            $table->unsignedTinyInteger('grade')->nullable()->comment('年级0-17');
            $table->unsignedTinyInteger('level')->nullable()->comment('英语水平等级 or 测试等级');
            // $table->unsignedBigInteger('book_id')->unsigned()->nullable()->comment('同步教材id');
            //todo 
            $table->unsignedBigInteger('agency_uid')->nullable()->comment('客服代理');
            $table->unsignedBigInteger('recommender_uid')->nullable()->comment('推荐人');
            $table->text('remark')->comment('备注')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
               ->references('id')->on('users')
               ->onDelete('cascade');

            $table->foreign('agency_uid')
                ->references('id')
                ->on('agencies')
                ->onDelete('cascade');
                
            $table->foreign('recommender_uid')
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
        Schema::dropIfExists('students');
    }
}
