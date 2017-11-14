<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_blocks', function (Blueprint $table) {
            $table->increments('id');

            $table->string('key', 25)->index();

            $table->boolean('active')->index()->default(0);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('static_blocks_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('static_block_id')->unsigned();
            $table->longText('text')->nullable()->default(null);
            $table->string('locale')->index();

            $table->unique([
                'static_block_id',
                'locale',
            ]);
            $table->foreign('static_block_id')->references('id')->on('static_blocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('static_blocks');
        Schema::drop('static_blocks_translations');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
