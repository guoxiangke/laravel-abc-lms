<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('关联登陆用户id');
            // contact_id -> skype
            $table->unsignedBigInteger('school_id')->nullable()->comment('学校id，NULL 为freelancer');
            $table->unsignedBigInteger('zoom_id')->nullable();
            // $table->unsignedBigInteger('teacher_uid')->nullable()->comment('推荐关系');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('zoom_id')
                ->references('id')
                ->on('zooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
