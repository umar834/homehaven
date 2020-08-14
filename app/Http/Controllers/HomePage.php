<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;

class HomePage extends Controller
{
    public function index()
    {
        return view('homepage');
    }

    
    public function contactus(Request $request)
    {
        $name = $request['name'];
        $email = $request['email'];
        $number = $request['number'];
        $message = $request['message'];

        $to_name = 'HomeHaven';
        $to_email = 'umargulzar834@gmail.com';

        $data = array('name'=> $name, "body" => $message);

        Mail::send('mail', $data, function($message) use ($to_email, $to_name) {
            $message->to($to_email, $to_name)
                    ->subject('New message revieved.');
            $message->from('gulzarumar21@gmail.com', 'HomeHaven');
        });
        return redirect()->back()->with("success","We have recieved your message. We will contact you soon.");
    }
}
