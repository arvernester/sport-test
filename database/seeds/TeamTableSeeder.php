<?php

use Illuminate\Database\Seeder;
use App\Sport\Team;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Team::class, 10)->create();
    }
}
