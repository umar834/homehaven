<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use App\Power_log;
use App\User;
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

        $power = Power_log::where([['date', '=', '2020-04-28'], ['user_id','=', Auth::user()->id]])->get();

        if (!$power->isEmpty())
        $previus_id = $power->id-1;

        if ($power->isEmpty())
        {
            $power = null;
        }
        $yest_power = Power_log::where([['date', '2020-04-27'], ['user_id','=', Auth::user()->id]])->get();

        if ($yest_power->isEmpty())
        {
            $yest_power = null;
        }

        $nightstarttime = Auth::user()->Night_Start_Time;
        $nightendtime = Auth::user()->Night_End_Time;
        
        $data = array(
            'power_data' => $power,
            'yest_power' => $yest_power,
            'rooms' => $rooms,
            'bill' => $bill,
            'lastmonthbill' => $lastmonthbill,
            'nightstarttime' => $nightstarttime,
            'nightendtime' => $nightendtime
        );
       
        if (Auth::user() && Auth::user()->role != 'admin')
        {
            return view('home')->with($data);
        }

        else if (Auth::user() && Auth::user()->role == 'admin')
        {
            $users_active = User::where([['role', '=', 'user'], ['status','=', 'active']])->get();
          
            return view('admindashboard')->with('users_active',$users_active);
        }
        else {
            return view('auth.login');
        }
    }

    /********************SAVE NIGHT MODE DATA TO TABLE *********************/

    public function changeNightmode(Request $request)
    {
        $rooms = DB::table('rooms')->where('user_id', '=', Auth::user()->id)->get();

        $starttime = $request->get('starttime');
        $stoptime = $request->get('stoptime');

        $user_id = Auth::user()->id;

        $number = 1;
        foreach($rooms as $room)
        {
            $night_state = 0;
            $morning_state = 0;
            if($room->dev1_type != 0)
            {
                $dev1_start = $request->get('start1'.$room->id);
                $dev1_stop = $request->get('end1'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 128;
                }

                if($dev1_stop == null)
                {
                    $morning_state += 0;
                }
                else
                {
                    $morning_state += 128;
                }
            }

            if($room->dev2_type != 0)
            {
                $dev1_start = $request->get('start2'.$room->id);
                $dev1_stop = $request->get('end2'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 64;
                }

                if($dev1_stop == null)
                {
                    $morning_state += 0;
                }
                else
                {
                    $morning_state += 64;
                }
            }

            if($room->dev3_type != 0)
            {
                $dev1_start = $request->get('start3'.$room->id);
                $dev1_stop = $request->get('end3'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 32;
                }

                if($dev1_stop == null)
                {
                    $morning_state += 0;
                }
                else
                {
                    $morning_state += 32;
                }
            }

            if($room->dev4_type != 0)
            {
                $dev1_start = $request->get('start4'.$room->id);
                $dev1_stop = $request->get('end4'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 16;
                }

                if($dev1_stop == null)
                {
                    $morning_state += 0;
                }
                else
                {
                    $morning_state += 16;
                }
            }

            if($room->dim_type != 0)
            {
                $dev1_start = $request->get('start5'.$room->id);
                $dev1_stop = $request->get('end5'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 7;
                }

                if($dev1_stop == null)
                {
                    $morning_state += 0;
                }
                else
                {
                    $morning_state += 7;
                }
            }

            DB::update('update rooms set night_state = ?, morning_state = ? where id = ?',[$night_state,$morning_state, $room->id]);
        }

            $startstate =$request->get('start'.$number);
            $stopstate = $request->get('end'.$number);

            DB::update('update users set Night_Start_Time = ?, Night_End_Time = ? where id = ?',[$starttime,$stoptime, $user_id]);

        return redirect()->back()->with("success","Nighmode changes saved to the database !");
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

    public function storeimage(Request $request){

      
       $imagepath = request('dpimg')->store('images', 'public');
       $user_id = Auth::user()->id;

       DB::update('update users set image_name = ? where id = ?',[$imagepath, $user_id]);
       return redirect()->back()->with("success","mubarak ho ap ki shkul save ho gai !");
    }

    public function showChangeEmailForm(){
        return view('auth.passwords.changeemail');
    }

    public function changeEmail(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your password is not correct. Please try again.");
        }

        if(!strcmp($request->get('new-email'), $request->get('new-email_confirmation')) == 0)
        {
            return redirect()->back()->with("error","New email and confirmation email are not same. Please try again.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);

        //Change Password
        $user = Auth::user();
        $user->email = $request->get('new-email');
        $user->save();

        return redirect()->back()->with("success","Email changed successfully !");

    }
}