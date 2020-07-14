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
            if(self::setroom($request) == "ok"){
                return "ok";
            }
        }
        return "invalid";
    }

    private function getroom(Request $request){
        $email = $request->get('email');
        $room_index = $request->get('room_index');

        $user = DB::table('users')->where('email', '=', $email)->first();
        $id = $user->id;

        $room = DB::table('rooms')->where([['user_id', '=', $id],['room_index', '=', $room_index]])->first();
        return $room;
    }
    private function setroom(Request $request){
        $email = $request->get('email');
        $room_index = $request->get('room_index');
        $state = $request->get('state');

        $user = DB::table('users')->where('email', '=', $email)->first();
        $id = $user->id;

        //dump($id);
        //dump($state);
        //dump($room_index);
        //dump($email);
        DB::update('update rooms set state = ? where user_id = ? AND room_index = ?',
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
    public function getddata(Request $request)
    {
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid ";
        }
        if ($request->get('token') === $user->token) {
            $uid = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $uid)->get();
            $rooms_str = "";
            foreach ($rooms as $key => $value) {
                $rooms_str .= sprintf(":%03d", $value->state);
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

        dump(Str::random(32));
        $last_log = DB::table('power_log')->where('user_id', '=', '1')->latest('date')->first();
        $scnd_last_log = DB::table('power_log')->where('user_id', '=', '1')->latest('date')->skip('1')->first();

        $last_log_count = 0;
        $scnd_last_log_count = 0;
        $last_total = 0;
        $scnd_total = 0;
        if ($last_log != null) {
            $last_total = $last_log->log_0 + $last_log->log_1 + $last_log->log_2 +
                $last_log->log_3 + $last_log->log_4 + $last_log->log_5;
            $last_log_count += $last_log->log_0 != null ? 1 : 0;
            $last_log_count += $last_log->log_1 != null ? 1 : 0;
            $last_log_count += $last_log->log_2 != null ? 1 : 0;
            $last_log_count += $last_log->log_3 != null ? 1 : 0;
            $last_log_count += $last_log->log_4 != null ? 1 : 0;
            $last_log_count += $last_log->log_5 != null ? 1 : 0;
        }
        if ($scnd_last_log != null) {
            $scnd_total = $scnd_last_log->log_0 + $scnd_last_log->log_1 + $scnd_last_log->log_2 +
                $scnd_last_log->log_3 + $scnd_last_log->log_4 + $scnd_last_log->log_5;
            $scnd_last_log_count += $scnd_last_log->log_0 != null ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_1 != null ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_2 != null ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_3 != null ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_4 != null ? 1 : 0;
            $scnd_last_log_count += $scnd_last_log->log_5 != null ? 1 : 0;
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
}
