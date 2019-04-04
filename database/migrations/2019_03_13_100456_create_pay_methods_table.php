<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('用户id');
            // $table->nullableMorphs('target');
            // 'PayPal','AliPay','WechatPay','Bank','Skype',
            $table->unsignedTinyInteger('type')->comment('支付类型 0-4');
            $table->string('number')->comment('支付账户');
            $table->text('remark')->comment('备注')->nullable();
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
        Schema::dropIfExists('pay_methods');
    }
}
