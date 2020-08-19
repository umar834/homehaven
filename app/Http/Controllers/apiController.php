<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hash;
use Str;

class apiController extends Controller
{

    public function temprature(Request $request)
    {
        $var = $request->get('user_id');
        $rooms = DB::table('rooms')->where('user_id', '=', $var)->get();

        $date = now();
        $time = strtotime($date);
        $time = $time - (5 * 60);
        $date = date("Y-m-d H:i:s", $time);

        foreach ($rooms as $rno => $rval) {
            if ($rval->changed_at > $date)
                var_dump($rval->state);
            else
                var_dump(8);
        }
        //return preg_replace("(\\{|\\}|\"room_index\"\:|\[|\])",'',$rooms);

    }

    public function setroomstate(Request $request){
        if(self::verifytoken($request) == "ok")
        {
            if(self::set_room_state($request) == "ok"){
                return "ok";
            }
        }
        return "invalid";
    }

    public function getroomstate(Request $request){
        if(self::verifytoken($request) == "ok")
        {
            $state =self::get_room_state($request);
            if($state > -1 && $state < 256){
                return $state;
            }
        }
        return "invalid";
    }

    private function get_room_state(Request $request){
        $email = $request->get('email');
        $room_index = $request->get('room_index');

        $user = DB::table('users')->where('email', '=', $email)->first();
        $id = $user->id;

        $room = DB::table('rooms')->where([['user_id', '=', $id],['room_index', '=', $room_index]])->first();
        if($room != null)
            return  sprintf("%03d", $room->state);
        else return -10;
    }
    private function set_room_state(Request $request){
        $email = $request->get('email');
        $room_index = $request->get('room_index');
        $state = $request->get('state');

        $user = DB::table('users')->where('email', '=', $email)->first();
        $id = $user->id;

        //dump($id);
        //dump($state);
        //dump($room_index);
        //dump($email);
        DB::update('update rooms set state = ?, priority = true where user_id = ? AND room_index = ?',
        [$state, $id, $room_index]);
        return "ok";
    }

    private function token(Request $request){
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid";
        }
        if (Hash::check($request->get('password'), $user->password)) {
            return $user->token;
        }
        return "invalid";
    }

    public function gettoken(Request $request)
    {
        $token = self::token($request);
        if($token != "invalid")
        return 'ok' . $token;
        else
        return "invalid";
    }

    public function applogin(Request $request)
    {
        $token = self::token($request);
        $room_info = "inv";
        if($token != "invalid"){
            $room_info = self::roominfo($request, $token);
            if($room_info != "invalid")
                return "ok" . $token . $room_info;
        }
        return $token . $room_info;
    }
    public function verifytoken(Request $request)
    {
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid";
        }
        if ($request->get('token') == $user->token) {
            return 'ok';
        }
        return "invalid";
    }

    public function updatetoken()
    {
        $users = DB::table('users')->get();
        foreach ($users as $key => $user) {
            $new_token = Str::random(12);
            DB::update('update users set token = ? where id = ?',[$new_token, $user->id]);
        }
    }
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
        $power_usage = $request->get('usage');
        ///////////////////////////////////////////////////////////////TODO 
        // Comment it later
        if($power_usage == null) $power_usage = 10;

        $roomArr = array($room0,$room1,$room2,$room3,$room4,$room5,$room6,$room7);
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid ";
        }
        if ($request->get('token') === $user->token) {
            $uid = $user->id;
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
                if ($value->changed_at > $date && $value->priority == true){
                    $rooms_str .= sprintf(":%03d", $value->state); 
                    DB::update('update rooms set priority = ? where user_id = ? AND room_index = ?',
                    [false, $uid, $key]);

                }
                else{
                    $rooms_str .= ":500"; // Just rusbbish/invalid room state
                    if( $roomArr[$key] != null){
                                        
                        DB::update('update rooms set priority = ?, state = ? where user_id = ? AND room_index = ?',
                        [false, $roomArr[$key], $uid, $key]);
                    }
                }
            }
            //dump($rooms_str);
            //dd($rooms);
            return 'ok' . $rooms_str;
        }
        return "invalid";
    }

    public function getroominfo(Request $request)
    {
        $room_info = self::roominfo($request, $request->token);
        if($room_info != "invalid")
        return 'ok' . $room_info;
        else
        return $room_info;
    }
    private function roominfo(Request $request, String $token)
    {
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid ";
        }
        if ($token === $user->token) {
            $uid = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $uid)->get();
            $rooms_count = count($rooms);
            $rooms_str = "";
            foreach ($rooms as $key => $value) {
                $rooms_str .= (':' . $value->room_index . $value->dev1_type . $value->dev2_type .
                    $value->dev3_type . $value->dev4_type . $value->dim_type . sprintf("%03d", $value->state).
                    $value->name);
            }
            //dump($rooms_str);
            //dd($rooms);
            return $rooms_count . $rooms_str ;
        }
        return "invalid";
    }


    public function test(Request $request)
    {

        //dump(Str::random(32));
        $last_log = DB::table('power_log')->where('user_id', '=', '1')->latest('date')->first();
        $scnd_last_log = DB::table('power_log')->where('user_id', '=', '1')->latest('date')->skip('1')->first();

        $last_log_count = 0;
        $scnd_last_log_count = 0;
        $last_total = 0;
        $scnd_total = 0;
        if ($last_log != null) {
            $last_total = $last_log->log_0 + $last_log->log_1 + $last_log->log_2 +
                $last_log->log_3 + $last_log->log_4 + $last_log->log_5;
            $last_log_count += $last_log->log_0 != 0 ? 1 : 0;
            $last_log_count += $last_log->log_1 != 0 ? 1 : 0;
            $last_log_count += $last_log->log_2 != 0 ? 1 : 0;
            $last_log_count += $last_log->log_3 != 0 ? 1 : 0;
            $last_log_count += $last_log->log_4 != 0 ? 1 : 0;
            $last_log_count += $last_log->log_5 != 0 ? 1 : 0;
        }
        if ($scnd_last_log != null) {
            $scnd_total = $scnd_last_log->log_0 + $scnd_last_log->log_1 + $scnd_last_log->log_2 +
                $scnd_last_log->log_3 + $scnd_last_log->log_4 + $scnd_last_log->log_5;
            $scnd_last_log_count += $scnd_last_log->log_0 != 0 ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_1 != 0 ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_2 != 0 ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_3 != 0 ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_4 != 0 ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_5 != 0 ? 1 : 0;
        }
        dump($last_log_count);
        dump($scnd_last_log_count);
        dump($last_total);
        dump($scnd_total);
        dump($last_log);
        dump($scnd_last_log);

        //dd($rooms);
        //return 'o'.$rooms_str.'k';
    }
    
    public function billprediction(Request $request){
        if(self::verifytoken($request) == "ok")
        {
            $email = $request->get('email');

            $user = DB::table('users')->where('email', '=', $email)->first();
            $id = $user->id;

            
            $date =  date("Y-m-d", strtotime(now()->startOfMonth()));
            $power_logs = DB::table('power_log')->where([['user_id', '=', $id],['date', '>=', $date]])->get();
            dump($date);

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
            
            dump($total_power);
            dump($total_days);
            dump($bill);

            return "ok";
        }
        return "invalid";

    }


    public function automodedayjob(){
        
        //$auto_users = DB::table('users')->where('auto_enabled', '!=', '0')->get();
        
        $manual_users = DB::table('users')->where('auto_enabled', '=', '0')->get();
        
        //dump($auto_users);
        //dump($manual_users);
        
        foreach ($manual_users as $u_index => $user) {
            $u_id = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $u_id)->get();
            
            foreach ($rooms as $r_index => $room) {
                
                $room_state = $room->state;
                
                $device1 = floor($room_state /128);if($device1 == 1) $room_state -= 128;
                $device2 = floor($room_state /64);if($device2 == 1) $room_state -= 64;
                $device3 = floor($room_state /32);if($device3 == 1) $room_state -= 32;
                $device4 = floor($room_state /16);if($device4 == 1) $room_state -= 16;
                $device5 = floor($room_state /8);
                
                $dev1 = $room->autoDayDev1;
                $dev2 = $room->autoDayDev2;
                $dev3 = $room->autoDayDev3;
                $dev4 = $room->autoDayDev4;
                $dev5 = $room->autoDayDev5;
                
                if($device1 == 1 && $dev1 < 20) $dev1 +=1;
                if($device1 == 0 && $dev1 > -20) $dev1 -=1;
                if($device2 == 1 && $dev2 < 20) $dev2 +=1;
                if($device2 == 0 && $dev2 > -20) $dev2 -=1;
                if($device3 == 1 && $dev3 < 20) $dev3 +=1;
                if($device3 == 0 && $dev3 > -20) $dev3 -=1;
                if($device4 == 1 && $dev4 < 20) $dev4 +=1;
                if($device4 == 0 && $dev4 > -20) $dev4 -=1;
                if($device5 == 1 && $dev5 < 20) $dev5 +=1;
                if($device5 == 0 && $dev5 > -20) $dev5 -=1;
                
                DB::update('update rooms set autoDayDev1 = ?, autoDayDev2 = ?, autoDayDev3 = ?, autoDayDev4 = ?, autoDayDev5 = ? where id = ?',
                    [$dev1, $dev2, $dev3, $dev4, $dev5, $room->id]);
                
            }
        }
        return "ok";
    }

    public function automodenightjob(){
        
        //$auto_users = DB::table('users')->where('auto_enabled', '!=', '0')->get();
        
        $manual_users = DB::table('users')->where('auto_enabled', '=', '0')->get();
        
        //dump($auto_users);
        //dump($manual_users);
        
        foreach ($manual_users as $u_index => $user) {
            $u_id = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $u_id)->get();
            
            foreach ($rooms as $r_index => $room) {
                $room_state = $room->state;
                
                $device1 = floor($room_state /128);if($device1 == 1) $room_state -= 128;
                $device2 = floor($room_state /64);if($device2 == 1) $room_state -= 64;
                $device3 = floor($room_state /32);if($device3 == 1) $room_state -= 32;
                $device4 = floor($room_state /16);if($device4 == 1) $room_state -= 16;
                $device5 = floor($room_state /8);
                
                $dev1 = $room->autoNightDev1;
                $dev2 = $room->autoNightDev2;
                $dev3 = $room->autoNightDev3;
                $dev4 = $room->autoNightDev4;
                $dev5 = $room->autoNightDev5;
                
                if($device1 == 1 && $dev1 < 20) $dev1 +=1;
                if($device1 == 0 && $dev1 > -20) $dev1 -=1;
                if($device2 == 1 && $dev2 < 20) $dev2 +=1;
                if($device2 == 0 && $dev2 > -20) $dev2 -=1;
                if($device3 == 1 && $dev3 < 20) $dev3 +=1;
                if($device3 == 0 && $dev3 > -20) $dev3 -=1;
                if($device4 == 1 && $dev4 < 20) $dev4 +=1;
                if($device4 == 0 && $dev4 > -20) $dev4 -=1;
                if($device5 == 1 && $dev5 < 20) $dev5 +=1;
                if($device5 == 0 && $dev5 > -20) $dev5 -=1;
                
                DB::update('update rooms set autoNightDev1 = ?, autoNightDev2 = ?, autoNightDev3 = ?, autoNightDev4 = ?, autoNightDev5 = ? where id = ?',
                    [$dev1, $dev2, $dev3, $dev4, $dev5, $room->id]);
                
            }
        }
        return "ok";
    }

    
    public function automodedaysetstate(){
        
        $auto_users = DB::table('users')->where('auto_enabled', '!=', '0')->get();

        foreach ($auto_users as $u_index => $user) {
            $u_id = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $u_id)->get();
            
            foreach ($rooms as $r_index => $room) {
                $room_state = $room->state;
                $temprature = $room->temprature;
                
                $dev1_t = $room->dev1_type;
                $dev2_t = $room->dev2_type;
                $dev3_t = $room->dev3_type;
                $dev4_t = $room->dev4_type;
                $dev5_t = $room->dim_type;
                
                $dim_value = $room_state & 7;
                                
                $dev1 = $room->autoDayDev1;
                $dev2 = $room->autoDayDev2;
                $dev3 = $room->autoDayDev3;
                $dev4 = $room->autoDayDev4;
                $dev5 = $room->autoDayDev5;
                
                $new_state = $dim_value;
                
                if(($dev1 > 0 && $dev1_t != 2) || ($temprature > 25 && $dev1_t == 2)) $new_state += 128;
                if(($dev2 > 0 && $dev2_t != 2) || ($temprature > 25 && $dev2_t == 2)) $new_state += 64;
                if(($dev3 > 0 && $dev3_t != 2) || ($temprature > 25 && $dev3_t == 2)) $new_state += 32;
                if(($dev4 > 0 && $dev4_t != 2) || ($temprature > 25 && $dev4_t == 2)) $new_state += 16;
                if(($dev5 > 0 && $dev5_t != 2) || ($temprature > 25 && $dev5_t == 2)) $new_state += 8;
                
                DB::update('update rooms set state = ?, priority = true where id = ?',
                    [$new_state, $room->id]);
                
            }
        }
        return "ok";
    }

    public function automodenightsetstate(){
        
        $auto_users = DB::table('users')->where('auto_enabled', '!=', '0')->get();

        foreach ($auto_users as $u_index => $user) {
            $u_id = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $u_id)->get();
            
            foreach ($rooms as $r_index => $room) {
                $room_state = $room->state;
                $temprature = $room->temprature;

                $dev1_t = $room->dev1_type;
                $dev2_t = $room->dev2_type;
                $dev3_t = $room->dev3_type;
                $dev4_t = $room->dev4_type;
                $dev5_t = $room->dim_type;

                $dim_value = $room_state & 7;

                $dev1 = $room->autoNightDev1;
                $dev2 = $room->autoNightDev2;
                $dev3 = $room->autoNightDev3;
                $dev4 = $room->autoNightDev4;
                $dev5 = $room->autoNightDev5;

                $new_state = $dim_value;
                
                if(($dev1 > 0 && $dev1_t != 2) || ($temprature > 25 && $dev1_t == 2)) $new_state += 128;
                if(($dev2 > 0 && $dev2_t != 2) || ($temprature > 25 && $dev2_t == 2)) $new_state += 64;
                if(($dev3 > 0 && $dev3_t != 2) || ($temprature > 25 && $dev3_t == 2)) $new_state += 32;
                if(($dev4 > 0 && $dev4_t != 2) || ($temprature > 25 && $dev4_t == 2)) $new_state += 16;
                if(($dev5 > 0 && $dev5_t != 2) || ($temprature > 25 && $dev5_t == 2)) $new_state += 8;

                DB::update('update rooms set state = ?, priority = true where id = ?',
                    [$new_state, $room->id]);
                
            }
        }
        return "ok";
    }

}
