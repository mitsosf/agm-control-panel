<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OCController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('oc');
    }

    public function index()
    {

        //User stats
        $totalUsers = User::all()->count(); //All that have ever logged in
        $approvedUsers = User::where('spot_status', 'approved')->count();

        //Funds stats
        $paidUsers = User::where('fee', '!=', '0')->get();
        $funds = 0;
        foreach ($paidUsers as $user) {
            $funds += $user->fee;
        }

        $paidUsersCount = $paidUsers->count();

        //Rooming stats
        //TODO CHANGE TO ROOMS
        $roomedUsers = User::where('rooming', '!=', 'No')->count();

        //Check-in stats
        $checkedInUsers = User::where('checkin', '!=', '0')->count();


        return view('oc.home', compact('totalUsers', 'approvedUsers', 'roomedUsers', 'funds', 'paidUsersCount', 'checkedInUsers'));
    }

    public function approved()
    {

        $users = User::where('spot_status', 'approved')->get();


        return view('oc.approved', compact('users'));
    }

    public function cashflow()
    {
        return view('oc.cashflow');
    }


    public function cashflowCard()
    {
        return view('oc.cashflowCard');
    }


    public function cashflowBank()
    {
        return view('oc.cashflowBank');
    }

    public function crudHotels()
    {
        $hotels = Hotel::all();
        return view('oc.crud.hotels', compact('hotels'));
    }

    public function showEditHotel(Hotel $hotel)
    {
        //TODO Show form prefilled with hotel data
        return view('oc.crud.editHotel', compact('hotel'));
    }

    public function editHotel(Hotel $hotel)
    {
        //TODO Edit the hotel, update DB
        return redirect(route('oc.crud.hotels'));
    }

    public function deleteHotel(Hotel $hotel)
    {
        $hotel->delete();
        return redirect(route('oc.crud.hotels'));
    }

    public function crudRooms()
    {
        $rooms = Room::all();
        return view('oc.crud.rooms', compact('rooms'));
    }

    public function showEditRoom(Room $room)
    {
        //TODO Show form prefilled with room data
        return view('oc.crud.editRoom', compact('room'));
    }

    public function editRoom(Request $request)
    {
        //TODO Edit the room, update DB
        return redirect(route('oc.crud.rooms'));
    }

    public function deleteRoom($id)
    {
        Room::find($id)->delete();
        return redirect(route('oc.crud.rooms'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
