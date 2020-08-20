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
    public function update_state(Request $request)
    {
        
        $device = $request['device'];
        $room_index = $request['room_index'];
        $state = $request['state'];
        $bit = 2 ** (8-$device);
        $room = DB::table('rooms')->where([['user_id', '=', Auth::user()->id],['room_index','=',$room_index]])->first();
        $old_state = $room->state;
        $new_state = 0;
        if($state == 'true') $new_state = $old_state | $bit;
        else $new_state = $old_state & (255 - $bit);
        DB::update('update rooms set state = ?, priority = true where id = ?', [$new_state, $room->id]);
        
        return $new_state;
    }
    
    public function update_dim_state(Request $request)
    {
        $room_index = $request['room_index'];
        $state = $request['state'];
        $room = DB::table('rooms')->where([['user_id', '=', Auth::user()->id],['room_index','=',$room_index]])->first();
        $old_state = $room->state;
        $new_state = (248 & $old_state) + $state;
        DB::update('update rooms set state = ?, priority = true where id = ?', [$new_state, $room->id]);
        
        return $new_state;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        $rooms = DB::table('rooms')->where('user_id', '=', Auth::user()->id)->get();
        $id = Auth::user()->id;

            
        $date =  date("Y-m-d", strtotime(now()->startOfMonth()));
        $power_logs = DB::table('power_log')->where([['user_id', '=', $id],['date', '>=', $date]])->get();
        //dump($date);

        $total_power = 0;
        $total_days = 0;
        
        foreach ($power_logs as $index => $power_log) {
            $total_power += $power_log->log_0 * 4;
            $total_power += $power_log->log_1 * 4;
            $total_power += $power_log->log_2 * 4;
            $total_power += $power_log->log_3 * 4;
            $total_power += $power_log->log_4 * 4;
            $total_power += $power_log->log_5 * 4;
            $total_days += 1;
        }

        $Whs = 0;
        if($total_days)($total_power / $total_days) * 30; // Watt hours
        $kWhs = $Whs /1000;
        $bill = 75;              // Minimum bill
        if($kWhs <= 50){
            $bill += 2 * $kWhs;
        }
        else{
            $units = $kWhs;
            if($units < 101){
                $bill += 5.79 * $units;
            }
            elseif($units < 201){
                $bill += 5.79 * 100;
                $units -= 100;
                $bill += 8.11 * $units;
                
            }
            elseif($units < 301){
                $bill += 8.11 * 200;
                $units -= 200;
                $bill += 10.2 * $units;
                
            }
            elseif($units < 701){
                $hunds = (int)$units /100;
                $bill += 10.2 * 100 * $hunds;
                $units -= $hunds * 100;
                $bill += 17.6 * $units;
            }
            else{
                $bill += 20.7 * $units;
            }
        }
        // Add 10% tax
        $bill *= 1.1;

        $lastmonthbill = DB::table('bill')->where('user_id', '=', Auth::user()->id)->orderby('date', 'desc')->first();

        $power = Power_log::where( 'user_id','=', Auth::user()->id)->orderby('date', 'desc')->first();

        
        $yest_power = Power_log::where( 'user_id','=', Auth::user()->id)->orderby('date', 'desc')->skip(1)->first();


        $nightenabled = Auth::user()->Night_Enabled;
        $autoenabled = Auth::user()->Auto_Enabled;
        $bill_target = Auth::user()->bill_target;

        $data = array(
            'power_data' => $power,
            'yest_power' => $yest_power,
            'rooms' => $rooms,
            'bill' => $bill,
            'bill_target' => $bill_target,
            'lastmonthbill' => $lastmonthbill,
            'nightenabled' => $nightenabled,
            'autoenabled' => $autoenabled
        );
        
        if (Auth::user() && Auth::user()->status == 'disabled')
        {
             Auth::logout();
             return redirect('/login')
             ->with('error',"Sorry, your accound is disabled. Please contact our support team.");
        }
        
        else if (Auth::user() && Auth::user()->role != 'admin')
        {
            return view('home')->with($data);
        }

        else if (Auth::user() && Auth::user()->role == 'admin')
        {
            $users_active = User::where([['role', '=', 'user']])->paginate(6);
            $all_users = User::where([['role', '=', 'user']])->get();
            if ($users_active->isEmpty())
            {
                $users_active = null;
            }
            $user_rooms = DB::table('rooms')->orderBy('user_id', 'asc')->paginate(20);
            if ($user_rooms->isEmpty())
            {
                $user_rooms = null;
            }
            $showallusers = null;
            $showallrooms = null;
            $data = array(
                'users_active' => $users_active,
                'showallusers' => $showallusers,
                'user_rooms' => $user_rooms,
                'showallrooms' => $showallrooms,
                'all_users' => $all_users
            );
            return view('admindashboard')->with($data);
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




