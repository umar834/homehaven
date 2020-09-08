<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class xapiController extends Controller
{
    //
    
    public function edgesync(Request $request)
    {
        $email = $request->get('email');
        $room0 = $request->get('room0');
        $room1 = $request->get('room1');
        $room2 = $request->get('room2');
        $room3 = $request->get('room3');
        $room4 = $request->get('room4');
        $room5 = $request->get('room5');
        $room6 = $request->get('room6');
        $room7 = $request->get('room7');
        $room0_temp = $request->get('room0_temp');
        $room1_temp = $request->get('room1_temp');
        $room2_temp = $request->get('room2_temp');
        $room3_temp = $request->get('room3_temp');
        $room4_temp = $request->get('room4_temp');
        $room5_temp = $request->get('room5_temp');
        $room6_temp = $request->get('room6_temp');
        $room7_temp = $request->get('room7_temp');
        $power_usage = $request->get('usage');
        ///////////////////////////////////////////////////////////////TODO 
        // Comment it later
        if($power_usage == null) $power_usage = 10;

        $roomArr = array($room0,$room1,$room2,$room3,$room4,$room5,$room6,$room7);
        $roomTemprArr = array($room0_temp,$room1_temp,$room2_temp,$room3_temp,
        $room4_temp,$room5_temp,$room6_temp,$room7_temp);
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid ";
        }
        if ($request->get('token') === $user->token) {
            $uid = $user->id;

            DB::update('update users set Watts = ? where id = ?',
                    [$power_usage, $uid]);
            $rooms = DB::table('rooms')->where('user_id', '=', $uid)->get();
            $rooms_str = "";

                
            $date = now();
            $time = strtotime($date);
            $ago_time = $time - (545 * 60);   
            $date = date("Y-m-d H:i:s", $ago_time);

            $today_time = $time - ($time % 86400) - 18000;   // one day = 86400  => 24 hrs
            $today_date = date("Y-m-d", $today_time);

            $time_slot = (int)(($time - $today_time) / 14400);  // one slot = 14400   => 4 hrs

            //dump($today_date);
            //dump($time_slot);
            $power_log = DB::table('power_log')->where([['user_id', '=', $uid],['date', '=', $today_date]])->first();

            //dump($power_log);

            $slot_usage = 0;
            $slot_count = 0;
            if($power_log == null){        
                DB::insert('insert into power_log (user_id, date) values (?, ?)', [$uid, $today_date]);
            }
            else{ 
                switch ($time_slot) {
                    case 0:
                        $slot_usage = $power_log->log_0;
                        $slot_count = $power_log->log0_count;
                        break;
                    case 1:
                        $slot_usage = $power_log->log_1;
                        $slot_count = $power_log->log1_count;
                        break;
                    case 2:
                        $slot_usage = $power_log->log_2;
                        $slot_count = $power_log->log2_count;
                        break;
                    case 3:
                        $slot_usage = $power_log->log_3;
                        $slot_count = $power_log->log3_count;
                        break;
                    case 4:
                        $slot_usage = $power_log->log_4;
                        $slot_count = $power_log->log4_count;
                        break;
                    case 5:
                        $slot_usage = $power_log->log_5;
                        $slot_count = $power_log->log5_count;
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }

            $slot_usage = (($slot_usage * $slot_count) + $power_usage)/($slot_count + 1);
            $slot_count = $slot_count + 1;

            switch ($time_slot) {
                case 0:
                    DB::update('update power_log set log_0 = ?, log0_count = ? where user_id = ? AND date = ?',
                    [$slot_usage, $slot_count, $uid, $today_date]);
                    break;
                case 1:
                    DB::update('update power_log set log_1 = ?, log1_count = ? where user_id = ? AND date = ?',
                    [$slot_usage, $slot_count, $uid, $today_date]);
                    break;
                case 2:
                    DB::update('update power_log set log_2 = ?, log2_count = ? where user_id = ? AND date = ?',
                    [$slot_usage, $slot_count, $uid, $today_date]);
                    break;
                case 3:
                    DB::update('update power_log set log_3 = ?, log3_count = ? where user_id = ? AND date = ?',
                    [$slot_usage, $slot_count, $uid, $today_date]);
                    break;
                case 4:
                    DB::update('update power_log set log_4 = ?, log4_count = ? where user_id = ? AND date = ?',
                    [$slot_usage, $slot_count, $uid, $today_date]);
                    break;
                case 5:
                    DB::update('update power_log set log_5 = ?, log5_count = ? where user_id = ? AND date = ?',
                    [$slot_usage, $slot_count, $uid, $today_date]);
                    break;
                
                default:
                    # code...
                    break;
            }

            //dump($slot_usage);
            //dump($slot_count);

            foreach ($rooms as $key => $value) {
                //dump($value->changed_at );
                //dump($date );
                //dump($roomArr[$key]);
                //if ($value->changed_at > $date && $value->priority == true){
                if ($value->priority == true){
                    $rooms_str .= sprintf(":%03d", $value->state); 
                    DB::update('update rooms set priority = ?, temprature = ?  where user_id = ? AND room_index = ?',
                    [false, $roomTemprArr[$key], $uid, $key]);

                }
                else{
                    $rooms_str .= ":500"; // Just rusbbish/invalid room state
                    if( $roomArr[$key] != null){
                                        
                        DB::update('update rooms set priority = ?, state = ?, temprature = ? where user_id = ? AND room_index = ?',
                        [false, $roomArr[$key], $roomTemprArr[$key], $uid, $key]);
                    }
                }
            }
            //dump($rooms_str);
            //dd($rooms);
            return 'ok' . $rooms_str;
        }
        return "invalid";
    }


    public function savesnap(Request $request){

        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid ";
        }
        if ($request->get('token') === $user->token && $request->hasFile('photo')) {
            
            $image = $request->file('photo');
            $phone = $user->phone;
            $sendSMSurl = "https://lifetimesms.com/plain?api_token=be156954cc82881b55f850587f8ef1dc5889b03111
            &api_secret=4JK3hsd9NDnEn&to=$phone&from=HomeHaven&message=HomeHaven Security \nSystem has detected some suspicious activity.
            \nPlease visit homehaven.website to view snaps.";

            $response = Http::get($sendSMSurl);

            $uid = $user->id;
            $num = $user->last_snap + 1;
            if($num > 9) $num = 0;
            DB::update('update users set last_snap = ? where id = ?',
                    [$num, $uid]);

            //dd();
            Storage::disk('local')->putFileAs('images/security/user'.$uid, $image, 'snap_'.$num.'.jpg');
            return $response."ok";
        }
        return "invalid";
    }

}
