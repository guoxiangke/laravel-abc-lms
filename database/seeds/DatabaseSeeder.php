<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ask for confirmation to refresh migration
        // if ($this->command->confirm('Do you wish to refresh migration before seeding, Make sure it will clear all old data ?')) {
        //     $this->command->call('migrate:refresh');
        //     $this->command->warn("Data deleted, starting from fresh database.");
        // }
        $this->call(PermissionsTableSeeder::class);
        $this->command->info('Default Permissions added.');
        $this->call(ZoomsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
