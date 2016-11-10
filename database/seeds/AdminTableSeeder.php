<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();

        DB::table('admins')->insert([
          'name' => 'テスト',
          'email' => 'dev@se-project.co.jp',
          'password' => bcrypt('password'),
        ]);

        DB::table('admins')->insert([
          'name' => 'テスト2',
          'email' => 'takano@se-project.co.jp',
          'password' => bcrypt('password'),
        ]);
        
//        for ($i = 1; $i < 100; $i++) {
//            DB::table('admins')->insert([
//              'name' => 'テスト' . $i,
//              'email' => 'admin' . $i . '@example.com',
//              'password' => bcrypt('password'),
//            ]);
//        }
    }
}
