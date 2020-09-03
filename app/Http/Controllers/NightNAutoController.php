<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NightNAutoController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /********************SAVE NIGHT MODE DATA TO TABLE *********************/
    public function nightmode(Request $request)
    {
        $id = Auth::user()->id;
        if($request->state == 'true'){
            DB::update('update users set Night_Enabled = ? where id = ?',[1, $id]);
            DB::update('update rooms set morning_state = state, state = night_state, priority = true where user_id = ?',[$id]);
        } 
        else{
            DB::update('update users set Night_Enabled = ? where id = ?',[0, $id]);
            DB::update('update rooms set state = morning_state, priority = true where user_id = ?',[$id]);
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
