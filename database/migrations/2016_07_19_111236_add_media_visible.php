<?php

use Illuminate\Database\Migrations\Migration;

/*
 * todo@ да се премести в пакета
 */

class AddMediaVisible extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('media', function ($table) {
            $table->boolean('visible')->index()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('media', function ($table) {
            $table->dropColumn(['visible']);
        });
    }
}
