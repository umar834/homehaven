<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cronJobController extends Controller
{
    //

    
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
