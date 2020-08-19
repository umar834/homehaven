<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class downloadController extends Controller
{
    public function getmobileapp()
    {
        $file= public_path(). "/downloadables/homehaven.apk";

        $headers = [
            'Content-Type' => 'application/apk',
         ];

        return response()->download($file, 'homehavenapp.apk', $headers);
    }
}
