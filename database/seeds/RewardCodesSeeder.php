<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reward_codes') -> insert([
            [
                'unique_code' => 'gr33n1fy',
                'reward' => 10
            ],
            [
                'unique_code' => 'gr33ny',
                'reward' => 5
            ],
            [
                'unique_code' => '0n3w1thn4tur3',
                'reward' => 20
            ],
            [
                'unique_code' => 'c0nc3rn3dc1t1z3n',
                'reward' => 15
            ],
            [
                'unique_code' => 'b4ckt0gr33n',
                'reward' => 3
            ],
            [
                'unique_code' => 'w00t',
                'reward' => 5
            ],

        ]);
    }
}
