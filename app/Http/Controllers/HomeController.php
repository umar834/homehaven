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
       
        if (Auth::user() && Auth::user()->role != 'admin')
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

    public function searchuser(Request $request)
    {
        $search = $request['name'];
        $search = "%".$search."%";
        $users_active = User::where([['role', '=', 'user'], ['name','like', $search]])->paginate(6);
  
        if ($users_active->isEmpty())
        {
            $users_active = null;
        }
        $user_rooms = DB::table('rooms')->orderBy('user_id', 'asc')->paginate(20);
        if ($user_rooms->isEmpty())
        {
            $user_rooms = null;
        }
        $all_users = User::where([['role', '=', 'user']])->get();
        $showallusers = 1;
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

    public function searchuserbyid(Request $request)
    {
        $search = $request['id'];
        $users_active = User::where([['role', '=', 'user']])->paginate(6);
        if ($users_active->isEmpty())
        {
            $users_active = null;
        }

        $user_rooms = DB::table('rooms')->where('user_id', '=', $search)->orderBy('user_id', 'asc')->paginate(20);
        if ($user_rooms->isEmpty())
        {
            $user_rooms = null;
        }
        $all_users = User::where([['role', '=', 'user']])->get();
        $showallusers = null;
        $showallrooms = 1;
        $data = array(
            'users_active' => $users_active,
            'showallusers' => $showallusers,
            'user_rooms' => $user_rooms,
            'showallrooms' => $showallrooms,
            'all_users' => $all_users
        );
        return view('admindashboard')->with($data);
    }

    /********************SAVE NIGHT MODE DATA TO TABLE *********************/
    public function nightmode(Request $request)
    {
        $id = Auth::user()->id;
        if($request->state == 'true'){
            DB::update('update users set Night_Enabled = ? where id = ?',[1, $id]);
            DB::update('update rooms set morning_state = state, state = night_state where user_id = ?',[$id]);
        } 
        else{
            DB::update('update users set Night_Enabled = ? where id = ?',[0, $id]);
            DB::update('update rooms set state = morning_state where user_id = ?',[$id]);
        }
        return "Done".$request->state;
    }

    public function changeNightmode(Request $request)
    {
        $rooms = DB::table('rooms')->where('user_id', '=', Auth::user()->id)->get();


        $user_id = Auth::user()->id;

        $number = 1;
        foreach($rooms as $room)
        {
            $night_state = 0;
            $morning_state = 0;
            if($room->dev1_type != 0)
            {
                $dev1_start = $request->get('start1'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 128;
                }

            }

            if($room->dev2_type != 0)
            {
                $dev1_start = $request->get('start2'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 64;
                }

                
            }

            if($room->dev3_type != 0)
            {
                $dev1_start = $request->get('start3'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 32;
                }

                
            }

            if($room->dev4_type != 0)
            {
                $dev1_start = $request->get('start4'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 16;
                }

            }

            if($room->dim_type != 0)
            {
                $dev1_start = $request->get('start5'.$room->id);
                $dev1dim_start = $request->get('start5d'.$room->id);
                if($dev1_start == null)
                {
                    $night_state += 0;
                }
                else
                {
                    $night_state += 8;
                }
                $night_state += $dev1dim_start;

                
            }

            DB::update('update rooms set night_state = ? where id = ?',[$night_state, $room->id]);
        }

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

    public function deleteuser($userid)
    {
        DB::table('users')->where('id', $userid)->delete();
        return redirect()->back()->with("success","User deleted successfully!");
    }

    public function updateuser(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        $user_id = $request['id'];
        $name = $request['name'];
        $email = $request['email'];
        $status = $request['status'];

        DB::update('update users set name = ?, email = ?, status = ? where id = ?',[$name, $email, $status, $user_id]);

        return redirect()->back()->with("success","User Updated successfully!");;
    }


    public function updateroom(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $room_id = $request['id'];
        $name = $request['name'];
        $dev1 = $request['dev1'];
        $dev2 = $request['dev2'];
        $dev3 = $request['dev3'];
        $dev4 = $request['dev4'];
        $devdim = $request['devdim'];
        

        DB::update('update rooms set name = ?, dev1_type = ?, dev2_type = ?, dev3_type = ?, dev4_type = ?, dim_type = ? where id = ?',[$name, $dev1, $dev2, $dev3, $dev4, $devdim, $room_id]);

        return redirect()->back()->with("success","Room Updated successfully!");
    }

    public function addnewroom(Request $request)
    {
        $user_id = $request['user_id'];
        $rooms = DB::table('rooms')->where('user_id', $user_id)->orderby('room_index', 'DESC')->first();
        if ($rooms == null)
        {
            $room_index = 0;
        }
        elseif($rooms->room_index < 7)
        {
            $room_index = $rooms->room_index + 1;
        }
        else
        {
            return redirect()->back()->with("error","This user has reached maximum of 8 rooms limit");
        }

        $room_name = $request['name'];
        $desc = $request['desc'];
        $device_1 = $request['dev1'];
        $device_2 = $request['dev2'];
        $device_3 = $request['dev3'];
        $device_4 = $request['dev4'];
        $dim_type = $request['devdim'];
        $changed_at = now();
        $state = 8;
        $night_state = 8;
        $morning_state = 8;
        DB::insert('insert into rooms (room_index, dev1_type, dev2_type, dev3_type, dev4_type, dim_type, state, night_state, morning_state, name, description, user_id, changed_at)
         values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$room_index, $device_1, $device_2, $device_3, $device_4, $dim_type, $state, $night_state, $morning_state, $room_name, $desc, $user_id, $changed_at]);
        return redirect()->back()->with("success","New room added successfully!");
    }

    public function deleteroom(Request $request)
    {
        $id = $request['room_id'];
        $current_room = DB::table('rooms')->where('id', $id)->first();
        $room_index = $current_room->room_index;
        $user_id = $current_room->user_id;
        
        $rooms = DB::table('rooms')->where([['user_id', '=', $user_id], ['room_index', '>' ,$room_index]])->get();
        if(!$rooms->isEmpty())
        {
            foreach($rooms as $room)
            {
                $new_index =  $room->room_index - 1;
                DB::update('update rooms set room_index = ? where id = ?', [$new_index, $room->id]);
            }
        }

        DB::table('rooms')->where('id', $id)->delete();
        return redirect()->back()->with("success","Room deleted successfully!");
    }

//************************AUTO MODE *************************************************/
    public function automode(Request $request)
    {
        $id = Auth::user()->id;
        if($request->state == 'true'){
            DB::update('update users set Auto_Enabled = ? where id = ?',[1, $id]);
        } 
        else{
            DB::update('update users set Auto_Enabled = ? where id = ?',[0, $id]);
        }
        return "Done".$request->state;
    }
}




