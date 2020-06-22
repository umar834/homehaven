<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hash;
use Str;

class apiController extends Controller
{
    //Route::post("temprature","apiController@temprature");
    //$rooms = DB::table('rooms')->where('user_id', '=', Auth::user()->id)->get();
     public function temprature(Request $request)
    {
         $var = $request->get('user_id');
         $rooms = DB::table('rooms')->where('user_id', '=', $var)->get();
 
         $date = now();
         $time = strtotime($date);
         $time = $time - (5 * 60);
         $date = date("Y-m-d H:i:s", $time);
         
         foreach ($rooms as $rno => $rval) {
             if($rval->changed_at > $date)
                 var_dump($rval->state);
             else
                 var_dump(8);
                 }
         //return preg_replace("(\\{|\\}|\"room_index\"\:|\[|\])",'',$rooms);
 
         
         
    }
     public function login(Request $request)
    {
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if($user==null)
        {
            return "invalid";
        }
        if (Hash::check($request->get('password'), $user->password)){
            $var = Str::random(32);
            return $var;
        }
        return "invalid";
    }
}
