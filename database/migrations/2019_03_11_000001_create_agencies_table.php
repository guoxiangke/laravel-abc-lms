<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('关联登陆用户id');
            $table->text('name')->comment('机构名称')->nullable();
            $table->unsignedTinyInteger('type')->default(0)->comment('代理类型：1金牌 0银牌');
            $table->unsignedTinyInteger('discount')->default(100); //0-100 95%
            $table->schemalessAttributes('extra_attributes');
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
        Schema::dropIfExists('agencies');
    }
}
