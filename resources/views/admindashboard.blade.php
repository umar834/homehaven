@extends('layouts.mylayout')
<link rel="stylesheet" href="{{ asset('css/Userdashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admindashboard.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script> 
<script src="{{ asset('js/GaugeMeter.js')}}"></script> 
<script src="{{ asset('js/userdashboard.js')}}"></script> 
<script src="{{ asset('js/jquery.are-you-sure.js')}}"></script> 
<script>
    $( document ).ready(function() {
        $('form.dirty-check').areYouSure();
});
    setTimeout(function() {
    $('.hidecroosss').fadeOut('slow');
}, 5000); 
</script>
@if($showallrooms == 1)
    <script>
        $(document).ready(function(){
        $("#mainwindow").removeClass("active1");
        $("#controls").addClass("active1");
        $("#mainwindowbutton").removeClass("active");
        $("#controlsbutton").addClass("active");
        });
    </script>
@endif
@section('content')
<div class="row maindiv">
    <!--***********TABS MAIN DIV***********-->
    <div class="tab-div col-lg-2 col-sm-3 col-md-3">
        <div class="logoDiv">
            <a href="{{ url('/') }}">
            <h3 style="color: white">HomeHaven - <span style="font-size: 15px"> Admin</span></h3>
            </a>
        </div>

        <div class="userInfo">
            <img 
            @if (Auth::user()->image_name == null){
            src="{{asset('images/user.png')}}"
             }
            @else{
                @php
                    $image = Auth::user()->image_name;
                @endphp
                src="<?php echo asset("storage/$image")?>"
            }
            @endif 
            width="80" height="80" alt="user">
            <p>Hello,</p>
            <h3>{{ Auth::user()->name }}</h3>
        </div>

        <div class="menus">
            <h4>Dashboard</h4>
            <button onclick="openCity(event, 'mainwindow')" id="mainwindowbutton" class="tablinks active"><i class="fas fa-tachometer-alt"></i>&nbsp; Manage Users</button><br>
            <button onclick="openCity(event, 'controls')" id="controlsbutton" class="tablinks"><i class="fas fa-dharmachakra"></i></i>&nbsp; Manage Rooms</button><br>
            <button onclick="openCity(event, 'nightmode')" class="tablinks"><i class="fas fa-moon"></i></i>&nbsp; Night Mode</button><br>
            <button onclick="openCity(event, 'settings')" class="tablinks"><i class="fa fa-wrench"></i>&nbsp; Settings</button><br>
            <button href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>&nbsp; Sign Out</button>
        </div>
    </div>

    <!--***********CONTENT MAIN DIV***********-->
    <div class="content-div col-lg-10 col-sm-9 col-xs-12 col-md-9">
        @error('name')
        <div class="alert alert-danger hidecroosss">
                            Name is Invalid.
        </div>
         @enderror
         @error('email')
            <div class="alert alert-danger hidecroosss">
                The email has already been taken.
            </div>
         @enderror
         @error('password')
            <div class="alert alert-danger hidecroosss">
                The password confirmation does not match.
            </div>
         @enderror
        @if (session('success'))
                            <div style="margin-top: 10px" class="alert alert-success hidecroosss">
                                {{ session('success') }}
                            </div>
        @endif
        @if (session('error'))
                        <div class="alert alert-danger hidecroosss">
                            {{ session('error') }}
                        </div>
        @endif
 

        <!--MAIN WINDOW TAB-->
        <div style="padding: 10px" class="tabcontent active1" id="mainwindow" >

        <div class="activeusersmain">
            <div class="row" style="width: 100%;">
            <div class="col-md-4 form-group">
                <form action="/searchuser" method="post" class="navbar-form" role="search">
                    @csrf
                    <div class="form-group input-group col-md-12">
                        <input type="text" class="form-control" placeholder="Search users" name="name">
                        <div class="input-group-btn">
                            <button style="background-color: #79abfa; padding: 10px; border-radius: 0px 10px 10px 0px;" 
                            class="btn btn-default" type="submit"><i style="padding: 0px 10px; color:white;" class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3">
                @if($showallusers == 1)
                <p><a class="btn btn-secondary" href="/">Show All Users</a></p>
                @endif
                <p><a class="btn btn-primary" href="#popupnew">Create New Account</a></p>
            </div>


            <div id="popupnew" class="overlay11 light11">
	            <a class="cancel" href="#"></a>
	            <div class="popup">
                    <h2>Edit user</h2>
                    <a class="close" href="#">&times;</a>
		            <div class="content">
                    @php
                    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                    $pass = array(); //remember to declare $pass as an array
                    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                    for ($i = 0; $i < 8; $i++) {
                        $n = rand(0, $alphaLength);
                        $pass[] = $alphabet[$n];
                    }
                    $pass = implode($pass); //turn the array into a string
                    @endphp

                    <form method="POST" action="{{ route('register') }}">
                    @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control is-valid" value="{{$pass}}" name="password" required autocomplete="new-password" readonly>
                                    <span class="valid-feedback">
                                        <strong>Password is auto-generated and will be sent to user through email.</strong>
                                    </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="{{$pass}}" required autocomplete="new-password" readonly>
                            </div>
                            <input type="text" name="role" value="user" hidden>
                            <input type="text" name="status" value="active" hidden>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>


		            </div>
	            </div>
            </div>
            </div>

            <div class="activeusers">
                <h3 style="font-size: 23px; font-weight: 300">Users</h3>
                @if($users_active == null)
                <h2 style="font-weight: 300">No User Found</h2>
                @else
                <table class="table">
                    <thead class="thead-light ">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>    
                        <th>Status</th>
                        <th>Created on</th>
                        <th></th>
                    </tr>
                    </thead>    

                    <tbody>
                        @foreach($users_active as $user)
                        <tr>
                            @php
                            $new_date = date("d-m-Y",strtotime($user->created_at));
                            @endphp
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->status}}</td>
                            <td>{{$new_date}}</td>
                            <td><p><a class="button11" href="#popup{{$user->id}}">Edit</a></p></td>

                            <div id="popup{{$user->id}}" class="overlay11 light11">
	                            <a class="cancel" href="#"></a>
	                            <div class="popup">
                                    <h2>Edit user</h2>
                                    <a class="close" href="#">&times;</a>
                                    <div class="content">

                                    <form action="/updateuser" method="post">
                                    @csrf
                                    <input type="number" name="id" value="{{$user->id}}" hidden>
                                      <label for="name">Name: </label>
                                      <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{$user->name}}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                      <label for="email">Email: </label>
                                      <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{$user->email}}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                      <label for="status">Status: </label>
                                      <select class="form-control" name="status">
                                        @if($user->status == "active")
                                        <option value="active" selected>Active</option>
                                        <option value="disabled">Disabled</option>
                                        @else  
                                        <option value="active">Active</option>
                                        <option value="disabled" selected>Disabled</option>  
                                        @endif
                                      </select> 

                                      <button style="margin-top: 10px; float: right" class="btn btn-primary" type="submit">Save</button>
                                    </form>
                                      
                                      <form onsubmit="return confirm('All the data of this user will be deleted. Do you really want to delete this user?');" action="/deleteuser/{{$user->id}}" method="post">
                                        @csrf
                                        <button value="confirm" style="margin-top: 10px; float: left" class="btn btn-danger" type="submit">Delete User</button>
                                      </form>
                                      </div>
		                            </div>
	                            </div>
                            </div>
                        </tr>
                        @endforeach
                       
                    </tbody>
                </table>
                {{$users_active->links()}}
                @endif
            </div>


        </div>
            
        </div>

        <!--************************************************************-->
        <!--*********************MANAGE ROOMS TAB***********************-->
        <!--************************************************************-->

        <div id="controls" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent controlsdiv">
            
            <div class="activeusersmain">
            <div class="row" style="width: 100%;">
            <div class="col-md-4 form-group">
                <form action="/searchuserbyid" method="post" class="navbar-form" role="search">
                    @csrf
                    <div class="form-group input-group col-md-12">
                        <input type="number" class="form-control" placeholder="Search Rooms by User ID" name="id" required>
                        <div class="input-group-btn">
                            <button style="background-color: #79abfa; padding: 10px; border-radius: 0px 10px 10px 0px;" 
                            class="btn btn-default" type="submit"><i style="padding: 0px 10px; color:white;" class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3">
                @if($showallrooms == 1)
                <p><a class="btn btn-secondary" href="/">Show All Users</a></p>
                @endif
                <p><a class="btn btn-primary" href="#popupnew1">Add New Room</a></p>
            </div>
            <div id="popupnew1" class="overlay11 light11">
	            <a class="cancel" href="#"></a>
	            <div style="height: 535px" class="popup">
                    <h2>Add Room</h2>
                    <a class="close" href="#">&times;</a>
		            <div class="content">

                    <form method="POST" action="/addnewroom">
                    @csrf

                    <div class="form-group row">
                            <label for="user_id" class="col-md-4 col-form-label text-md-right">{{ __('User ID') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="user_id">
                                    @foreach($all_users as $user)
                                    <option value= {{$user->id}} selected>{{$user->id}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __(' Room Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="desc" class="col-md-4 col-form-label text-md-right">{{ __(' Description(Optional)') }}</label>

                            <div class="col-md-6">
                                <input id="desc" type="text" class="form-control" name="desc">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dev1" class="col-md-4 col-form-label text-md-right">{{ __('Device 1') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="dev1">
                                    <option value= 0 selected>None</option>
                                    <option value= 1>Light</option>
                                    <option value= 2>Fan</option>
                                    <option value= 3>Socket</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dev2" class="col-md-4 col-form-label text-md-right">{{ __('Device 2') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="dev2">
                                    <option value= 0 selected>None</option>
                                    <option value= 1>Light</option>
                                    <option value= 2>Fan</option>
                                    <option value= 3>Socket</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dev3" class="col-md-4 col-form-label text-md-right">{{ __('Device 3') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="dev3">
                                    <option value= 0 selected>None</option>
                                    <option value= 1>Light</option>
                                    <option value= 2>Fan</option>
                                    <option value= 3>Socket</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dev4" class="col-md-4 col-form-label text-md-right">{{ __('Device 4') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="dev4">
                                    <option value= 0 selected>None</option>
                                    <option value= 1>Light</option>
                                    <option value= 2>Fan</option>
                                    <option value= 3>Socket</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="devdim" class="col-md-4 col-form-label text-md-right">{{ __('Dimable Device') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="devdim">
                                    <option value= 0 selected>None</option>
                                    <option value= 1>Light</option>
                                    <option value= 2>Fan</option>
                                    <option value= 3>Socket</option>
                                </select>
                            </div>
                        </div>
                            

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Room') }}
                                </button>
                            </div>
                        </div>
                    </form>


		            </div>
	            </div>
            </div>
            </div>

            <div class="activeusers">
                <h3 style="font-size: 23px; font-weight: 300">Rooms</h3>
                @if($user_rooms == null)
                <h2 style="font-weight: 300">No Rooms Found</h2>
                @else
                <table class="table">
                    <thead class="thead-light ">
                    <tr>
                        <th>User ID</th>
                        <th>Room</th>
                        <th>Device 1</th>
                        <th>Device 2</th>    
                        <th>Device 3</th>
                        <th>Device 4</th>
                        <th>Dimable Device</th>
                    </tr>
                    </thead>    

                    <tbody>
                        @foreach($user_rooms as $room)
                        @php
                        if($room->dev1_type == 1)
                        {
                            $room->dev1_type = 'Light';
                        }
                        elseif ($room->dev1_type == 2)
                        {
                            $room->dev1_type = 'Fan';
                        }
                        elseif ($room->dev1_type == 3)
                        {
                            $room->dev1_type = 'Socket';
                        }
                        else {
                            $room->dev1_type = 'None';
                        }


                        if($room->dev2_type == 1)
                        {
                            $room->dev2_type = 'Light';
                        }
                        elseif ($room->dev2_type == 2)
                        {
                            $room->dev2_type = 'Fan';
                        }
                        elseif ($room->dev2_type == 3)
                        {
                            $room->dev2_type = 'Socket';
                        }
                        else {
                            $room->dev2_type = 'None';
                        }


                        if($room->dev3_type == 1)
                        {
                            $room->dev3_type = 'Light';
                        }
                        elseif ($room->dev3_type == 2)
                        {
                            $room->dev3_type = 'Fan';
                        }
                        elseif ($room->dev3_type == 3)
                        {
                            $room->dev3_type = 'Socket';
                        }
                        else {
                            $room->dev3_type = 'None';
                        }


                        if($room->dev4_type == 1)
                        {
                            $room->dev4_type = 'Light';
                        }
                        elseif ($room->dev4_type == 2)
                        {
                            $room->dev4_type = 'Fan';
                        }
                        elseif ($room->dev4_type == 3)
                        {
                            $room->dev4_type = 'Socket';
                        }
                        else {
                            $room->dev4_type = 'None';
                        }


                        if($room->dim_type == 1)
                        {
                            $room->dim_type = 'Light';
                        }
                        elseif ($room->dim_type == 2)
                        {
                            $room->dim_type = 'Fan';
                        }
                        elseif ($room->dim_type == 3)
                        {
                            $room->dim_type = 'Socket';
                        }
                        else {
                            $room->dim_type = 'None';
                        }
                        @endphp
                        <tr>
                            <td>{{$room->user_id}}</td>
                            <td>{{$room->name}}</td>
                            <td>{{$room->dev1_type}}</td>
                            <td>{{$room->dev2_type}}</td>
                            <td>{{$room->dev3_type}}</td>
                            <td>{{$room->dev4_type}}</td>
                            <td>{{$room->dim_type}}</td>
                            <td><p><a class="button11" href="#popuproom{{$room->id}}">Edit</a></p></td>

                            <div id="popuproom{{$room->id}}" class="overlay11 light11">
	                            <a class="cancel" href="#"></a>
	                            <div class="popup popuproom">
                                    <h2>Edit Room</h2>
                                    <a class="close" href="#">&times;</a>
                                    <div class="content">

                                    <form action="/updateroom" method="post">
                                    @csrf
                                    <input type="number" name="id" value="{{$room->id}}" hidden>
                                      <label for="name">Name: </label>
                                      <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{$room->name}}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                      <label for="dev1">Device 1: </label>
                                      <select class="form-control" name="dev1">
                                        @if($room->dev1_type == 'Light')
                                        <option value= 0>None</option>
                                        <option value= 1 selected>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev1_type == 'Fan')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2 selected>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev1_type == 'Socket')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3 selected>Socket</option>
                                        @else  
                                        <option value= 0 selected>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @endif
                                      </select> 

                                      <label for="dev2">Device 2: </label>
                                      <select class="form-control" name="dev2">
                                        @if($room->dev2_type == 'Light')
                                        <option value= 0>None</option>
                                        <option value= 1 selected>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev2_type == 'Fan')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2 selected>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev2_type == 'Socket')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3 selected>Socket</option>
                                        @else  
                                        <option value= 0 selected>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @endif
                                      </select> 

                                      <label for="dev3">Device 3: </label>
                                      <select class="form-control" name="dev3">
                                        @if($room->dev3_type == 'Light')
                                        <option value= 0>None</option>
                                        <option value= 1 selected>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev3_type == 'Fan')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2 selected>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev3_type == 'Socket')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3 selected>Socket</option>
                                        @else  
                                        <option value= 0 selected>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @endif
                                      </select> 

                                      <label for="dev4">Device 4: </label>
                                      <select class="form-control" name="dev4">
                                        @if($room->dev4_type == 'Light')
                                        <option value= 0>None</option>
                                        <option value= 1 selected>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev4_type == 'Fan')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2 selected>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dev4_type == 'Socket')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3 selected>Socket</option>
                                        @else  
                                        <option value= 0 selected>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @endif
                                      </select> 

                                      <label for="devdim">Device 4: </label>
                                      <select class="form-control" name="devdim">
                                        @if($room->dim_type == 'Light')
                                        <option value= 0>None</option>
                                        <option value= 1 selected>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dim_type == 'Fan')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2 selected>Fan</option>
                                        <option value= 3>Socket</option>
                                        @elseif($room->dim_type == 'Socket')
                                        <option value= 0>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3 selected>Socket</option>
                                        @else  
                                        <option value= 0 selected>None</option>
                                        <option value= 1>Light</option>
                                        <option value= 2>Fan</option>
                                        <option value= 3>Socket</option>
                                        @endif
                                      </select> 



                                      <button style="margin-top: 10px; float: right" class="btn btn-primary" type="submit">Update Room</button>
                                    </form>
                                      
                                      <form onsubmit="return confirm('Do you realy want to delete this room?');" action="/deleteroom" method="post">
                                        @csrf
                                        <input type="number" value="{{$room->id}}" hidden name="room_id">
                                        <button value="confirm" style="margin-top: 10px; float: left" class="btn btn-danger" type="submit">Delete Room</button>
                                      </form>
                                      </div>
		                            </div>
	                            </div>
                            </div>
                        </tr>
                        @endforeach
                       
                    </tbody>
                </table>
                {{$user_rooms->links()}}
                @endif
            </div>
        </div>

         <!--*********************NIGHTMODE MAIN TAB***********************-->
         <div id="nightmode" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent nightmodemain">
            <h1>Content 3</h1>
         </div>


        <!--*****************SETTINGS TAB****************-->
        <div id="settings" class="tabcontent settingstab col-md-8 col-lg-6 col-sm-12 col-xs-12">
            <div class="editdp settingcontent">
            <form action="/saveuserimage" method="post" enctype="multipart/form-data">
                @csrf
                <img id="uploadeddp"
            @if (Auth::user()->image_name == null){
            src="{{asset('images/user.png')}}"
             }
            @else{
                @php
                    $image = Auth::user()->image_name;
                @endphp
                src="<?php echo asset("storage/$image")?>"
            }
            @endif 
            alt="user">
                 
                <input type="file" class="choosedp" accept="image/*" name="dpimg" id="dpfile">
                <label class="dpbutton" for="dpfile">Change Image</label>
                <button style="margin-top: 10px; float: right; background-color: #2779ff" type="submit" class="btn btn-success" disabled>Save Image</button>
            </form>
            </div>

            <div class="settingcontent editemail">
                <label for="emailid">Email: </label>
                <input type="email" class="form-control" name="email" id="emailid" value="{{Auth::user()->email}}" readonly>
                <label style="margin-top: 10px" onclick="window.location='{{ url("changeEmail") }}'" class="dpbutton">Change Email</label>
            </div>

            <div class="settingcontent editpassword">
                <label>Password: <span style="color:#858585;">&nbsp;***********</span></label>
                <label onclick="window.location='{{ url("changePassword") }}'" class="dpbutton editpasswordbutton">Change Password</label>
            </div>
            <div class="settingcontent">
               
            </div>
        </div>
    </div>
</div>
<script>

/*UPLOAD IMAGE-DP*/
function readURL(input) {
if (input.files && input.files[0]) {
var reader = new FileReader();
reader.onload = function(e) {
  $('#uploadeddp').attr('src', e.target.result);
}
reader.readAsDataURL(input.files[0]); // convert to base64 string
}
}
$("#dpfile").change(function() {
readURL(this);
});

/*CHECK IF INPUT IS EMPTY*/
$('input[type=file]').change(function(){
    if($('input[type=file]').val()==''){
        $('button').attr('disabled',true)
    } 
    else{
      $('button').attr('disabled',false);
    }
})

</script>
@endsection