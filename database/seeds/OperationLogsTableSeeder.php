<?php

use Illuminate\Database\Seeder;

class OperationLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('operation_logs')->truncate();

        DB::table('operation_logs')->insert([
          'screen_number' => 100,
          'target_id' => 100,
          'operator' => 1,
          'comment' => 'コメント',
          'executed_at' => date("Y-m-d H:i:s"),
        ]);

        for ($i = 1; $i < 100; $i++) {
            DB::table('operation_logs')->insert([
              'screen_number' => 100 + $i,
              'target_id' => 100 + $i,
              'operator' => 1,
              'comment' => 'コメント' . $i,
              'executed_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
