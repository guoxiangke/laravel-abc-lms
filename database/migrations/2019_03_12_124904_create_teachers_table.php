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
            $table->unsignedBigInteger('zoom_id')->nullable()->comment('zoom_id');
            
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
