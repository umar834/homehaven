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
            <button onclick="openCity(event, 'mainwindow')" class="tablinks active"><i class="fas fa-tachometer-alt"></i>&nbsp; Main Window</button><br>
            <button onclick="openCity(event, 'controls')" class="tablinks"><i class="fas fa-dharmachakra"></i></i>&nbsp; Controls</button><br>
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
                <button type="submit" class="btn btn-primary" id="newaccount">Create New Account</button>
            </div>
            </div>

            <div class="activeusers">
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
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->created_at}}</td>
                            <td><button style="margin: 0px;" class="btn btn-primary">Edit</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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