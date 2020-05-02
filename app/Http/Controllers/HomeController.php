<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use App\Power_log;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rooms = DB::table('rooms')->where('user_id', '=', Auth::user()->id)->get();

        $devices = array();
        foreach($rooms as $room)
        {
            $device = DB::table('devices')->where('room_id', '=', $room->id)->get();
            array_push($devices, $device);
        }

        $id = Auth::user()->id;
       /* $power = Power_log::where([['date', '=', date('y-m-d')], ['user_id','=', 2]])->firstOrFail();
        $previus_id = $power->id-1;
        $yest_power = Power_log::where([['date', '=', date('y-m-d', strtotime("-1 days"))], ['user_id','=', 1]])->firstOrFail();
        */
        /*
        $devices = array();

        $rooms = Room::All()->where('user_id', '=', Auth::user()->id);
        foreach ($rooms as $room)
        {
            array_push($devices, Device::All()->where('room_id', '=', $room->id));
        }
        */

        $power = Power_log::where([['date', '=', '2020-04-28'], ['user_id','=', 2]])->firstOrFail();
        $previus_id = $power->id-1;
        $yest_power = Power_log::where([['date', '2020-04-27'], ['user_id','=', 1]])->firstOrFail();
        
        $data = array(
            'power_data' => $power,
            'yest_power' => $yest_power,
            'devices' => $devices,
            'rooms' => $rooms
        );
       
        if (Auth::user())
        {
            return view('home')->with($data);
        }
        else {
            return view('auth.login');
        }
    }

    public function showChangePasswordForm(){
        return view('auth.passwords.changepassword');
    }

    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");

    }

}
