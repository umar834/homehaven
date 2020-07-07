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
    public function gettoken(Request $request)
    {
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user == null) {
            return "invalid ";
        }
        if (Hash::check($request->get('password'), $user->password)) {
            $uid = $user->id;
            $rooms = DB::table('rooms')->where('user_id', '=', $uid)->get();
            $rooms_count = count($rooms);
            $rooms_str = "";
            foreach ($rooms as $key => $value) {
                $rooms_str .= (':' . $value->dev1_type . $value->dev2_type .
                    $value->dev3_type . $value->dev4_type . $value->dim_type);
            }
            //dump($rooms_str);
            //dd($rooms);
            return 'o' . $user->token . ':' . $rooms_count . $rooms_str . 'k';
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
            return 'o' . $rooms_str . 'k';
        }
        return "invalid";
    }


    public function test(Request $request)
    {

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
