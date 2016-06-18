<?php
namespace ProVision\Administration\Database\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(InitSeeder::class);
    }
}
