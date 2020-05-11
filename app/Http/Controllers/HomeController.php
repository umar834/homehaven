<?php

namespace App\Http\Controllers;

use Request;
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
        $bill = DB::table('bill')->where('user_id', '=', Auth::user()->id)->orderby('date', 'desc')->first();
        $lastmonthbill = DB::table('bill')->where('user_id', '=', Auth::user()->id)->orderby('date', 'desc')
                            ->skip(1)->first();

        $devices = array();
        foreach($rooms as $room)
        {
            $device = DB::table('devices')->where('room_id', '=', $room->id)->get();
            array_push($devices, $device);
        }

        $power = Power_log::where([['date', '=', '2020-04-28'], ['user_id','=', 2]])->firstOrFail();
        $previus_id = $power->id-1;
        $yest_power = Power_log::where([['date', '2020-04-27'], ['user_id','=', 1]])->firstOrFail();
        $nightstarttime = Auth::user()->Night_Start_Time;
        $nightendtime = Auth::user()->Night_End_Time;
        
        $data = array(
            'power_data' => $power,
            'yest_power' => $yest_power,
            'devices' => $devices,
            'rooms' => $rooms,
            'bill' => $bill,
            'lastmonthbill' => $lastmonthbill,
            'nightstarttime' => $nightstarttime,
            'nightendtime' => $nightendtime
        );
       
        if (Auth::user())
        {
            return view('home')->with($data);
        }
        else {
            return view('auth.login');
        }
    }

    /********************SAVE NIGHT MODE DATA TO TABLE *********************/
    public function changeNightmode()
    {
        $rooms = DB::table('rooms')->where('user_id', '=', Auth::user()->id)->get();
        $devices = array();
        foreach($rooms as $room)
        {
            $device = DB::table('devices')->where('room_id', '=', $room->id)->get();
            array_push($devices, $device);
        }

        $starttime = Request::get('starttime');
        $stoptime = Request::get('stoptime');

        $user_id = Auth::user()->id;

        $number = 1;
        foreach($devices as $room)
        {
            foreach($room as $device)
            {
                $startstate = Request::get('start'.$number);
                $stopstate = Request::get('end'.$number);
                if($startstate == null)
                {
                    $startstate = 0;
                }
                else
                {
                    $startstate = 1;
                }

                if($stopstate == null)
                {
                    $stopstate = 0;
                }
                else
                {
                    $stopstate = 1;
                }
                DB::update('update devices set Night_start_state = ?, Night_end_state = ? where id = ?',[$startstate,$stopstate, $device->id]);
                DB::update('update users set Night_Start_Time = ?, Night_End_Time = ? where id = ?',[$starttime,$stoptime, $user_id]);
                $number += 1;
            }
        }
        return redirect()->back()->with("nightmodesaved","Changes saved to the database !");
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
