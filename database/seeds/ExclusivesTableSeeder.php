<?php

use Illuminate\Database\Seeder;

class ExclusivesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('exclusives')->truncate();

//        DB::table('exclusives')->insert([
//          'screen_number' => 100,
//          'target_id' => 100,
//          'operator' => 100,
//          'expired_at' => date("Y-m-d H:i:s"),
//          'comment' => 'サンプル',
//        ]);
    }
}
