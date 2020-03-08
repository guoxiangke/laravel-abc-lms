<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('student_uid')->default(1)->comment('学生id');
            // Copy values from one column to another in the same table
            // UPDATE `orders` SET student_uid=user_id;
            // update `orders` SET user_id = 1; // creator
            $table->schemalessAttributes('extra_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['student_uid', 'extra_attributes']);
        });
    }
}
