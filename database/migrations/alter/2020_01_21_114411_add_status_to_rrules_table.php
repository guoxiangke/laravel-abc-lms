<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToRrulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rrules', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');//0 暂停 1正常 2过期
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrules', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
