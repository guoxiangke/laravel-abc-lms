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
            $table->unsignedBigInteger('profile_id')->nullable()->comment('用户资料id');
            // 'skype','wechat','qq','.','.',
            $table->unsignedTinyInteger('type')->comment('类型 0-2');
            $table->string('number', 32)->comment('账户');
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
