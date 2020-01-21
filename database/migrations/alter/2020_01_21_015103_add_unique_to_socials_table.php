<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToSocialsTable extends Migration
{
    /**
     * Run the migrations.
     * Add 唯一索引在 social_id + type //确保一个用户在一个平台唯一绑定
     * php artisan migrate --path=/database/migrations/alter.
     * @return void
     */
    public function up()
    {
        Schema::table('socials', function (Blueprint $table) {
            $table->unique(['social_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('socials', function (Blueprint $table) {
            $table->dropUnique('socials_user_id_social_id_type_unique');
        });
    }
}
