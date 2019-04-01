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
            //target_type: Student Teacher Agency 
            // $table->nullableMorphs('target');
            $table->string('name')->nullable()->coment('姓名');//上课学生的中英文名昵称
            $table->boolean('sex')->default(0);
            $table->timestamp('birthday')->nullable();
            $table->string('telephone', 22)->unique()->index();//用来登陆账户
            
            //todo country.
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
        Schema::dropIfExists('profiles');
    }
}
