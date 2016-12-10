<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * todo@ да се премести в пакета
 */

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module', 25)->index();
            $table->string('sub_module', 25)->index();
            $table->integer('item_id')->unsigned()->index();
            $table->string('lang', 2)->nullable()->default(null)->index();
            $table->text('titles')->nullable()->default(null);
            $table->string('type')->nullable()->default(null)->index();
            $table->smallInteger('order_index')->unsigned()->index();

            $table->string('file')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media');
    }
}
