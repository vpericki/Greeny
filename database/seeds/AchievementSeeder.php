<?php

use App\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('achievements')->insert([
            [
                'name' => 'Just started greenyfying',
                'description' => 'Collect 5 points',
                'required_points' => 5
            ],
            [
                'name' => 'Go green',
                'description' => 'Collect 10 points',
                'required_points' => 10
            ],
            [
                'name' => 'Green expert',
                'description' => 'Collect 25 points',
                'required_points' => 25
            ],
            [
                'name' => 'Mad green',
                'description' => 'Collect 50 points',
                'required_points' => 50
            ]]);
    }
}
