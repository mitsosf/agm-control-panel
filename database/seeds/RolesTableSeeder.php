<?php

use App\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Participant
        $role = new Role();
        $role->name = 'Participant';
        $role->description = 'Event participant';
        $role->created_at = Carbon::now();
        $role->save();

        //OC
        $role = new Role();
        $role->name = 'OC';
        $role->description = 'Organising committee';
        $role->created_at = Carbon::now();
        $role->save();

        //NR
        $role = new Role();
        $role->name = 'NR';
        $role->description = 'National Representative';
        $role->created_at = Carbon::now();
        $role->save();

    }
}
