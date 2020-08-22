<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class profileImageController extends Controller
{
    //
    public function get_image(Request $request)
    {
        $email = $request->get('email');
        $user = DB::table('users')->where('email', '=', $email)->first();
        $image = 'images/user.png';
        if ($user !== null) {
            $image = $user->image_name;
        }
        return '<img src='.asset("storage/app/public/$image").' height="150px" width="150px" />';
    }
}
