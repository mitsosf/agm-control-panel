<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersRoomsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('users')->
        join('rooms', 'users.room_id', '=', 'rooms.id')->
        select('users.name', 'users.surname', 'users.document', 'users.birthday', 'users.phone', 'users.email')->
        where('rooms.hotel_id','=','2')->
        get();
    }
}
