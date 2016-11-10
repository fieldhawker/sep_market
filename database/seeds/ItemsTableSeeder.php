<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->truncate();

        DB::table('items')->insert([
          'user_id'         => 1,
          'name'            => '商品の名前',
          'price'           => 100,
          'caption'         => '商品の説明',
          'status'          => 1,
          'items_status'    => 1,
          'started_at'      => '2016-01-01 01:00:00',
          'ended_at'        => '2035-12-31 23:59:59',
          'delivery_charge' => 1,
          'delivery_plan'   => 1,
          'pref'            => 1,
          'delivery_date'   => 1,
          'comment'         => 'コメント',
        ]);

        DB::table('items')->insert([
          'user_id'         => 1,
          'name'            => '商品の名前',
          'price'           => 100,
          'caption'         => '商品の説明',
          'status'          => 1,
          'items_status'    => 1,
          'started_at'      => '2016-01-01 01:00:00',
          'ended_at'        => '2035-12-31 23:59:59',
          'delivery_charge' => 1,
          'delivery_plan'   => 1,
          'pref'            => 1,
          'delivery_date'   => 1,
          'comment'         => 'コメント',
        ]);

        for ($i = 1; $i < 100; $i++) {
            DB::table('items')->insert([
              'user_id'         => 1,
              'name'            => '商品の名前' . $i,
              'price'           => 100 + $i,
              'caption'         => '商品の説明' . $i,
              'status'          => 1,
              'items_status'    => 1,
              'started_at'      => '2016-01-01 01:00:00',
              'ended_at'        => '2035-12-31 23:59:59',
              'delivery_charge' => 1,
              'delivery_plan'   => 1,
              'pref'            => 1,
              'delivery_date'   => 1,
              'comment'         => 'コメント',
            ]);
        }
    }
}
