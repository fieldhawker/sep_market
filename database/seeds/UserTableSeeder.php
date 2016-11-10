<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        
        DB::table('users')->insert([
          'uid'      => hash("sha256",uniqid(mt_rand(10,10), 1)),
          'name'     => '公式アカウント',
          'kana'     => 'コウシキアカウント',
          'email'    => 'takano@se-project.co.jp',
          'password' => bcrypt('sepadmin'),
        ]);
        
        DB::table('users')->insert([
          'uid'      => hash("sha256",uniqid(mt_rand(10,10), 1)),
          'name'     => 'テスト',
          'kana'     => 'テスト',
          'email'    => 'dev@se-project.co.jp',
          'password' => bcrypt('password'),
        ]);

        for ($i = 1; $i < 100; $i++) {
            DB::table('users')->insert([
              'uid'      => hash("sha256",uniqid(mt_rand(10,10), 1)),
              'name'     => 'テスト' . $i,
              'kana'     => 'テスト' . $i,
              'email'    => 'test' . $i . '@example.com',
              'password' => bcrypt('password'),
            ]);
        }
    }
}
