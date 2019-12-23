<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vote_type_id'); // if delete!!!never!
            // $table->unsignedBigInteger('votable_id');//target_id
            // $table->string('votable_type'); //App/Models/classRecord
            //votable_type from vote_types table. 适度冗余
            $table->morphs('votable');
            $table->tinyInteger('votable_value')->default(0); //-1,0-5-10
            $table->timestamps();

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
        Schema::dropIfExists('votes');
    }
}
