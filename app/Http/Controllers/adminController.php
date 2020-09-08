<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class adminController extends Controller
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
    //
    ///***************USER  Relevent Controller functions *************************/
    
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


    public function deleteuser($userid)
    {
        DB::table('users')->where('id', $userid)->delete();
        return redirect()->back()->with("success","User deleted successfully!");
    }

    public function updateuser(Request $request)
    {

        $user = DB::table('users')->where('id', '=',  $request['id'])->first();
        $old_mail = $user->email;
        if($old_mail == $request['email']){
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);
        }
        else{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ]);
        }

        $user_id = $request['id'];
        $name = $request['name'];
        $phone = $request['phone'];
        $email = $request['email'];
        $status = $request['status'];

        DB::update('update users set name = ?, email = ?, status = ?, phone = ? where id = ?',[$name, $email, $status, $phone, $user_id]);

        return redirect()->back()->with("success","User Updated successfully!");;
    }


    ////***********************ROOM Relevent Controller functions ***************************/

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


}
