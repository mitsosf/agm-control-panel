<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->username = "dimitris.frangiadakis";
        $admin->role_id = 2;
        $admin->setCreatedAt(Carbon::now());
        $admin->setUpdatedAt(Carbon::now());
        $admin->save();

    }
}
