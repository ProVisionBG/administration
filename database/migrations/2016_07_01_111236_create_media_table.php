<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/*
 * todo@ да се премести в пакета
 */

class CreateMediaTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module', 25)->index();
            $table->string('sub_module', 25)->nullable()->index();
            $table->integer('item_id')->unsigned()->index();
            $table->string('lang', 2)->nullable()->index();
            $table->text('titles')->nullable();
            $table->string('type')->nullable()->index();
            $table->smallInteger('order_index')->unsigned()->index();

            $table->string('file');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('media');
    }
}
