<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_record_id');
            $table->string('task_id')->index();  // upyun check ID
            $table->string('start_time');  // 剪辑开始时间，格式为 HH:MM:SS，默认视频开始时间
            $table->string('end_time');  // 剪辑结束时间，格式为 HH:MM:SS，默认视频结束时间
            $table->string('path');  // 剪辑完成后的存放的Upyun路径，没完成时是null
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
        Schema::dropIfExists('videos');
    }
}
