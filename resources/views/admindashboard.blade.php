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
            <button onclick="openCity(event, 'mainwindow')" class="tablinks active"><i class="fas fa-tachometer-alt"></i>&nbsp; Manage Users</button><br>
            <button onclick="openCity(event, 'controls')" class="tablinks"><i class="fas fa-dharmachakra"></i></i>&nbsp; Manage Rooms</button><br>
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
                <form class="navbar-form" role="search">
                    <div class="form-group input-group col-md-12">
                        <input type="text" class="form-control" placeholder="Search users" name="q" autofocus>
                        <div class="input-group-btn">
                            <button style="background-color: #79abfa; padding: 10px; border-radius: 0px 10px 10px 0px;" 
                            class="btn btn-default" type="submit"><i style="padding: 0px 10px; color:white;" class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3">
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
                <h3 style="font-size: 23px; font-weight: 300">Active Users</h3>
                <table class="table">
                    <thead class="thead-light ">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>    
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
                            <td>{{$new_date}}</td>
                            <td><p><a class="button11" href="#popup{{$user->id}}">Edit</a></p></td>

                            <div id="popup{{$user->id}}" class="overlay11 light11">
	                            <a class="cancel" href="#"></a>
	                            <div class="popup">
                                    <h2>Edit user</h2>
                                    <a class="close" href="#">&times;</a>
		                            <div class="content">
                                      <label for="name">Name: </label>
                                      <input class="form-control" type="text" name="name" value="{{$user->name}}">
                                      <label for="email">Email: </label>
                                      <input class="form-control" type="email" name="email" value="{{$user->email}}">
                                      <label for="status">Status: </label>
                                      <select class="form-control" name="status">
                                        <option selected="selected" value="active">Active</option>
                                        <option value="disabled">Disabled</option>  
                                      </select>
                                      <div>
                                      <button style="margin-top: 10px; float: right" class="btn btn-primary" type="submit">Save</button>
                                      
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
            </div>

        </div>
            
        </div>


        <!--*********************CONTROLS MAIN TAB***********************-->


        <div id="controls" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent controlsdiv">
            <h1>Content 2</h1>
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