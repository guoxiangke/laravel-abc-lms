<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('学生id');
            $table->unsignedBigInteger('teacher_uid')->nullable();
            $table->unsignedBigInteger('agency_uid')->default(1)->comment('1无代理');
            $table->unsignedBigInteger('book_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('price')->comment('单位分');
            $table->unsignedInteger('period')->comment('课时');
            $table->timestamp('expired_at')->nullable();
            // $table->unsignedBigInteger('rrule_id')->nullable();//上课计划
            //0订单作废 1订单正常* 2订单完成  3订单暂停上课  4订单过期
            $table->unsignedTinyInteger('status')->default(1)->comment('订单状态');
            $table->text('remark')->nullable();

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')
               ->references('id')
               ->on('users')
               ->onDelete('cascade');
            $table->foreign('teacher_uid')
                ->references('id')
                ->on('users');
            // ->onDelete('cascade');
            $table->foreign('agency_uid')
                ->references('id')
                ->on('users');
            // ->onDelete('cascade');
                
            $table->foreign('book_id')
                ->references('id')
                ->on('books');
            // ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
            // ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
