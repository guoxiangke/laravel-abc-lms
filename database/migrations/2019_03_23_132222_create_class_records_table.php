<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('rrule_id');
                $table->unsignedBigInteger('user_id')->comment('学生id');
                $table->unsignedBigInteger('agency_uid')->default(1)->comment('1无代理');
                $table->unsignedBigInteger('teacher_uid')->nullable(); //老师可能临时改变！
            // $table->string('page')->nullable()->comment('页码');
            $table->text('remark')->nullable();
            $table->boolean('weight')->default(true)->comment('上课计数');
            $table->TinyInteger('exception')->default(0)->comment('上课异常');
            $table->dateTime('generated_at'); //不可以为空，自动生成记录时标记
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('rrule_id')
               ->references('id')
               ->on('rrules')
               ->onDelete('cascade');

            $table->foreign('teacher_uid')
                ->references('id')
                ->on('users');
                // ->onDelete('cascade');
            $table->foreign('agency_uid')
                ->references('id')
                ->on('users');
                // ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
                // ->onDelete('cascade');

            $table->unique(['rrule_id', 'teacher_uid', 'generated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_records');
    }
}
