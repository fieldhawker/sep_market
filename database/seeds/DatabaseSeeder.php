<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(OperationLogsTableSeeder::class);
        $this->call(ExclusivesTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
    }
}
