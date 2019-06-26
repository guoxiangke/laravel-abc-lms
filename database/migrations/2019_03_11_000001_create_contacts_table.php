<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profile_id')->comment('用户资料id');
            // 'skype','wechat','qq','.','.',
            $table->unsignedTinyInteger('type')->default(0)->comment('类型 0:skype 1:wechat/qq 2:facebook');
            $table->string('number')->comment('账户');
            $table->text('remark')->comment('其他备注')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('profile_id')
               ->references('id')->on('profiles')
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
        Schema::dropIfExists('contacts');
    }
}
