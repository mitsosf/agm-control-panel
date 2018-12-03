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
        $this->command->info('Seeding roles table!');
        $this->call(RolesTableSeeder::class);
        $this->command->info('Seeding users table!');
        $this->call(UsersTableSeeder::class);
        $this->command->info('Seeding successful!');
    }
}
