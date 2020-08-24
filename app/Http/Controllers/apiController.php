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
            
            $email = $request->get('email');
            $user = DB::table('users')->where('email', '=', $email)->first();
            $name = $user->name;
            if($room_info != "invalid")
                return "ok" . $token . $room_info . ':'. $name;
        }
        return 'id' . $token . $room_info;
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

    
    public function consumptionData(Request $request){
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
            

            $lastmonthbill = DB::table('bill')->where('user_id', '=', $id)->orderby('date', 'desc')->first();
            
            $last_log = DB::table('power_log')->where('user_id', '=', $id)->latest('date')->first();
            $scnd_last_log = DB::table('power_log')->where('user_id', '=', $id)->latest('date')->skip('1')->first();

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

            $watt = 0;
            if($last_log != null)
            {
                if ($last_log->log_5)
                {
                    $watt = $last_log->log_5;
                }
                elseif ($last_log->log_4)
                {
                    $watt = $last_log->log_4;
                }
                elseif ($last_log->log_3)
                {
                    $watt = $last_log->log_3;
                }
                elseif ($last_log->log_2)
                {
                    $watt = $last_log->log_2;
                }
                elseif ($last_log->log_1)
                {
                    $watt = $last_log->log_1;
                }
                elseif ($last_log->log_0)
                {
                    $watt = $last_log->log_0;
                }
                else {
                    $watt = 0;
                }
            }
            
            $last_avg = $last_total / $last_log_count;
            $scnd_avg = $scnd_total / 6.0;



            return "ok:" . $watt . ':'. $bill . ':' . $lastmonthbill->amount . ':' . $last_avg . ':' . $scnd_avg;
        }
        return "invalid";

    }



}
