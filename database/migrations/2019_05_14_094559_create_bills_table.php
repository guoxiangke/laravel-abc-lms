<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('price'); //0-100价格amount，单位分
            $table->unsignedTinyInteger('currency')->default(0); //货币类型，默认美元
            // ALTER TABLE `bills` Add COLUMN `currency` tinyint(3) NOT NULL DEFAULT(0) AFTER price;
            $table->unsignedTinyInteger('paymethod_type');
            $table->boolean('status')->default(0); //0:append 1:approved已成交/入账
            $table->text('remark')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
               ->references('id')
               ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
