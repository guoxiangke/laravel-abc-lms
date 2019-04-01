<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');//上课计划
            $table->string('string');
            $table->boolean('type')->default(1); //'AOL', 0 'SCHEDULE',1
            //计算属性
            $table->dateTime('start_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')
               ->references('id')
               ->on('orders')
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
        Schema::dropIfExists('rrules');
    }
}
