<?php

use App\Sport\Player;
use App\Sport\Team;
use Illuminate\Database\Seeder;

class PlayerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Player::class, 10)->create([
            'team_id' => Team::inRandomOrder()->first()->id,
        ]);
    }
}
