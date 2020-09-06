@extends('layouts.mylayout')
<link rel="stylesheet" href="{{ asset('css/Userdashboard.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script> 
<script src="{{ asset('js/GaugeMeter.js')}}"></script> 
<script src="{{ asset('js/userdashboard.js')}}"></script> 
<script src="{{ asset('js/jquery.are-you-sure.js')}}"></script> 
<script>
    $( document ).ready(function() {
        $('form.dirty-check').areYouSure();
        $.ajaxSetup({
    headers: {
      'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
        });
});
    setTimeout(function() {
    $('.hidecroosss').fadeOut('slow');
}, 5000); 

function night_enable(element){
    //alert(room_index);
    state = $(element).is(':checked');
    $.ajax({
               type:'POST',
               url:'/setnightmode',
               data:{state},
               success:function(data) {
               }
            });
}
function auto_enable(element){
    //alert(room_index);
    state = $(element).is(':checked');
    $.ajax({
               type:'POST',
               url:'/setautomode',
               data:{state},
               success:function(data) {
               }
            });
}
function security_enable(element){
    //alert(room_index);
    state = $(element).is(':checked');
    $.ajax({
               type:'POST',
               url:'/setsecuritymode',
               data:{state},
               success:function(data) {
               }
            });
}
function update_state(room_index, device, element){
    //alert(room_index);
    state = $(element).is(':checked');
    $.ajax({
               type:'POST',
               url:'/update_state',
               data:{room_index,device,state},
               success:function(data) {
               }
            });
}

function update_dim_state(room_index, element){
    //alert($(element).val());
    state = $(element).val();
    $.ajax({
               type:'POST',
               url:'/update_dim_state',
               data:{room_index,state},
               success:function(data) {
               }
            });
}
</script>
@section('content')
@if (session('success'))
<div class="alert alert-success hidecroosss">
    <h3> {{ session('success') }}</h3>
</div>
@endif
<div class="row maindiv">
    <!--***********TABS MAIN DIV***********-->
    <div class="tab-div col-lg-2 col-sm-3 col-md-3">
        <div class="logoDiv">
            <a href="{{ url('/') }}">
            <h3 style="color: white">HomeHaven</h3>
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
                src="<?php echo asset("storage/app/public/$image")?>"
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
            <button onclick="openCity(event, 'security')" class="tablinks"><i class="fa fa-camera"></i>&nbsp; Security Logs</button><br>
            <button onclick="openCity(event, 'settings')" class="tablinks"><i class="fa fa-wrench"></i>&nbsp; Settings</button><br>
            <button href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>&nbsp; Sign Out</button>
        </div>
    </div>
    
    <!--PHP CODE TO CHECK FOR LEAST NOT NULL VALUE-->
    @php

    $watt = null;
    $bill_increase = null;
    $bill_decrease = null;
    $increase = null;
    $decrease = null;

    if($power_data != null)
    {
        if ($power_data->log_5)
        {
            $watt = $power_data->log_5;
            $total_records = 6;
        }
        elseif ($power_data->log_4)
        {
            $watt = $power_data->log_4;
            $total_records = 5;
        }
        elseif ($power_data->log_3)
        {
            $watt = $power_data->log_3;
            $total_records = 4;
        }
        elseif ($power_data->log_2)
        {
            $watt = $power_data->log_2;
            $total_records = 3;
        }
        elseif ($power_data->log_1)
        {
            $watt = $power_data->log_1;
            $total_records = 2;
        }
        elseif ($power_data->log_0)
        {
            $watt = $power_data->log_0;
            $total_records = 1;
        }
        else {
            $watt = 0;
        }

        /*Calculating previous day power power*/
        $yest_sum = $yest_power->log_0 + $yest_power->log_1 + $yest_power->log_2 + $yest_power->log_3
                    + $yest_power->log_4 + $yest_power->log_5;
        $yest_sum = $yest_sum/6;
        $today_sum = $power_data->log_0 + $power_data->log_1 + $power_data->log_2 + $power_data->log_3
                    + $power_data->log_4 + $power_data->log_5;
        $today_sum = $today_sum / $total_records;
        $increase = null;
        $decrease = null;
        if($today_sum >= $yest_sum)
        {
            $increase = $today_sum - $yest_sum;
            $increase = ($increase / $yest_sum) * 100;
            if($increase == 0)
            {
                $increase = 1;
            }
            $increase = number_format((float)$increase, 2, '.', '');
        }
        else {
            $decrease = $yest_sum - $today_sum;
            $decrease = ($decrease / $today_sum) * 100;
            if($decrease == 0)
            {
                $decrease = 1;
            }
            $decrease = number_format((float)$decrease, 2, '.', '');
        }

        $bill_increase = null;
        $bill_decrease = null;

        if($bill == null)
        {

        }
        else
            if($bill > $lastmonthbill->amount)
        {
            $bill_increase = $bill - $lastmonthbill->amount;
            $bill_increase = ($bill_increase / $lastmonthbill->amount) * 100;
            if($bill_increase == 0)
            {
                $bill_increase = 1;
            }
            $bill_increase = number_format((float)$bill_increase, 2, '.', '');
        }

        else
        {
            $bill_decrease = $lastmonthbill->amount - $bill;
            $bill_decrease = ($bill_decrease / $bill) * 100;
            if($bill_decrease == 0)
            {
                $bill_decrease = 1;
            }
            $bill_decrease = number_format((float)$bill_decrease, 2, '.', '');
        }
    }
    @endphp
    <!--***********CONTENT MAIN DIV***********-->
    <div class="content-div col-lg-10 col-sm-9 col-xs-12 col-md-9">

        <!--MAIN WINDOW TAB-->
        <div class="tabcontent active1" id="mainwindow" >
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 energydiv">
                <h3>Energy currently being consumed</h3>
                @if ($power_data != null)
                <div class="GaugeMeter" id="PreviewGaugeMeter_4"
                        data-used="{{$watts}}"
                        data-total="1000"
                        data-text="{{$watts}}"
                        data-text_size="0.12"
                        data-append="Watts"
                        data-size="250"
                        data-theme="Green-Gold-Red" 
                        data-animate_gauge_colors="1" 
                        data-animate_text_colors="1" 
                        data-back=null 
                        data-width="15" 
                        data-label="P-Consumption" 
                        data-style="Arch" 
                        data-label_color="#2c94e0"
                        >
                </div>
                @else 
                    <h1 style="padding: 20% 0px; text-align: center; font-weight:300;">No Record Found</h1>
                @endif
                @if($increase != null)
                <p><span class="powerconsumptionspan"><i class="fa fa-arrow-up"></i> {{$increase}}% increase</span> compared to yesterday</p>
                @elseif($decrease != null)
                <p><span class="billpredictionspan"><i class="fa fa-arrow-down"></i> {{$decrease}}% decrease </span> compared to yesterday</p>
                @endif
            </div>

            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 billprediciton">
                <h3>Bill Predicitometer</h3>
                <div class="currentprediciton">
                    <h4>Current Month Prediciton</h4>
                @if ($bill != null)
                <h1><span class="count"> {{$bill}} </span><small style="font-size:22px"> PKR</small> </h1>
                @else 
                <h1 style="font-size: 19px; color: red;">No Record Found</h1>
                @endif
                @if($bill_increase != null)
                <p><span style="color: red" class="powerconsumptionspan"><i class="fa fa-arrow-up"></i> {{$bill_increase}}% increase</span> compared to last month</p>
                @elseif (($bill_decrease != null))
                <p><span style="color: blue"class="billpredictionspan"><i class="fa fa-arrow-down"></i> {{$bill_decrease}}% decrease </span> compared to last month</p>
                @endif
                </div>
                <hr style="color: rgb(133, 133, 133);">
                <div class="last6monthsprediciton">
                    @if ($lastmonthbill != null)
                    <h4>Last Month Bill: <span class="count"> {{$lastmonthbill->amount}} </span><small> PKR</small><i class="fas fa-tachometer-alt dashboardicon"></i></i> </h4>
                    @else
                    <h4 style="font-size: 17px; color: red;">No Record Found</h4>
                    @endif
                </div>
            </div>
        </div>

        <!--END OF TOP ROW-->
        <!--START OF SECOND ROW-->
        <div class="row roomsmaindiv">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="frequentlyusedcontrolles">
                    <h2 style="font-size: 26px">Quick Access</h2>
                    <div class="row">

                        <div class=" col-xs-12 col-sm-12 col-md-6 col-lg-4 ">
                            <h4 style="font-size: 20px">Auto mode :<span>
                                <label style="margin-left:20px; margin-top: 5px; float: none;" class="switch controlswitch">
                                    
                                @if($autoenabled == 0)
                                <input type="checkbox" onclick = 'auto_enable(this)' class="switch-input">
                                @else
                                <input type="checkbox" onclick = 'auto_enable(this)' class="switch-input" checked>
                                @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                </span>
                            </h4>
                            <h6 style="font-size: 15px; color: rgb(136, 136, 136)" align="justify"> 
                             You can activate this mode and then the devices will be controlled
                              by the system and you won't have to do anything. 
                              System will analyze the suroundings and previous data to manage all devices.
                             This is one of the great features of HomeHaven. System keeps a summary of devices' status 
                             during day and night. And does not track whole activity of Users.
                            </h6>
                    
                        </div>

                        <div class=" col-xs-12 col-sm-12 col-md-6 col-lg-4 ">
                            <h4 style="font-size: 20px">Night mode :<span>
                                <label style="margin-left:20px; margin-top: 5px; float: none;" class="switch controlswitch">
                                    
                                @if($nightenabled == 0)
                                <input type="checkbox" onclick = 'night_enable(this)' class="switch-input">
                                @else
                                <input type="checkbox" onclick = 'night_enable(this)' class="switch-input" checked>
                                @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                </span>
                            </h4>
                            <h6 style="font-size: 15px; color: rgb(136, 136, 136)" align="justify">
                            You can set your preferences that which devices you want to turn on/off when
                             night starts and when you activate this mode system will automatically set 
                             devices to your desired status. And in the morning when you turn off this mode
                             divcess will turned back to their previous status.
                              This way you will not have to change devices' status each 
                              morning and night manually.
                            </h6>
                    
                        </div>

                        <div class=" col-xs-12 col-sm-12 col-md-6 col-lg-4 ">
                            <h4 style="font-size: 20px">Security mode :<span>
                                <label style="margin-left:20px; margin-top: 5px; float: none;" class="switch controlswitch">
                                    
                                @if($securityenabled == 0)
                                <input type="checkbox" onclick = 'security_enable(this)' class="switch-input">
                                @else
                                <input type="checkbox" onclick = 'security_enable(this)' class="switch-input" checked>
                                @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                </span>
                            </h4>
                            <h6 style="font-size: 15px; color: rgb(136, 136, 136)" align="justify">
                            When security mode is enabled system will look for any movment and 
                            in case of any disturbance system will take a snap and save it on 
                            web server, and user will be notified via text message. User can log in to the 
                            website and check if the activity is suspicious and take action accordingly.
                            
                            </h6>
                    
                        </div>


                    </div>
                </div>
            </div>
        </div>
        </div>


        <!--*********************CONTROLS MAIN TAB***********************-->


        <div id="controls" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent controlsdiv">
        <div style="margin-left: 10px; " class="row">
        @if ($rooms->isEmpty())
        <h1 style="font-weight: 300; width: 100%; padding: 20px; background: white; text-align: center; color: red;">No Device Found</h1>
        @endif
        @foreach ($rooms as $room)         
            @php
                $state = $room->state;
                $dev1_state = floor($state/128);
                if($dev1_state == 1) 
                    $state -=128;
                $dev2_state = floor($state/64);
                if($dev2_state == 1) 
                    $state -=64;
                $dev3_state = floor($state/32);
                if($dev3_state == 1) 
                    $state -=32;
                $dev4_state = floor($state/16);
                if($dev4_state == 1) 
                    $state -=16;
                $dev5_state = $state;
            @endphp
            <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                <h2>{{$room->name}}</h2>

                <!--DEVICE ONE-->
                    @if ($room->dev1_type == 1)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT<span>
                            <label class="switch controlswitch">
                                @if($dev1_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",1,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",1,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @elseif($room->dev1_type == 2)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed"> 

                            <label class="switch controlswitch">

                                @if($dev1_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",1,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",1,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($room->dev1_type == 3)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET<span class="controlspeed">
                            <label class="switch controlswitch">
                                
                                @if($dev1_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",1,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",1,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @endif

                    <!--DEVICE TWO-->
                    @if ($room->dev2_type == 1)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT<span>
                            <label class="switch controlswitch">
                                
                                @if($dev2_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",2,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",2,this)' type="checkbox" class="switch-input" checked>
                                @endif

                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @elseif($room->dev2_type == 2)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                        
                            <label class="switch controlswitch">
                                   
                            @if($dev2_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",2,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",2,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($room->dev2_type == 3)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET<span class="controlspeed">
                            <label class="switch controlswitch">
                                   
                            @if($dev2_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",2,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",2,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @endif

                    <!--DEVICE THREE-->
                    @if ($room->dev3_type == 1)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT<span>
                            <label class="switch controlswitch">
                                  
                            @if($dev3_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",3,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",3,this)' type="checkbox" class="switch-input" checked>
                                @endif

                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @elseif($room->dev3_type == 2)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                        
                            <label class="switch controlswitch">
                                   
                            @if($dev3_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",3,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",3,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($room->dev3_type == 3)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET<span class="controlspeed">
                            <label class="switch controlswitch">
     
                            @if($dev3_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",3,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",3,this)' type="checkbox" class="switch-input" checked>
                                @endif

                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @endif

                    <!--DEVICE FOUR-->
                    @if ($room->dev4_type == 1)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT<span>
                            <label class="switch controlswitch">
     
                            @if($dev4_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",4,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",4,this)' type="checkbox" class="switch-input" checked>
                                @endif

                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @elseif($room->dev4_type == 2)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                       
                            <label class="switch controlswitch">
                            
                            @if($dev4_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",4,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",4,this)' type="checkbox" class="switch-input" checked>
                                @endif

                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($room->dev4_type == 3)
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET<span class="controlspeed">
                            <label class="switch controlswitch">

                            @if($dev4_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",4,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",4,this)' type="checkbox" class="switch-input" checked>
                                @endif

                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @endif

                    <!--DEVICE FIVE-->
                    @if ($room->dim_type == 1)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT<span class="controlspeed">
                    Speed: <input type ="range"  onclick = 'update_dim_state("{{$room->room_index}}",this)'  value="{{$dev5_state}}" min="0" max="7"/>
                            <label class="switch controlswitch">

                            @if($dev5_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",5,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",5,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @elseif($room->dim_type == 2)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                    Speed: <input type ="range"  onclick = 'update_dim_state("{{$room->room_index}}",this)'  value="{{$dev5_state}}" min="0" max="7"/>
                            <label class="switch controlswitch">

                            @if($dev5_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",5,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",5,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($room->dim_type == 3)
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET<span class="controlspeed">
                    Speed: <input type ="range"  onclick = 'update_dim_state("{{$room->room_index}}",this)'  value="{{$dev5_state}}" min="0" max="7"/>
                            <label class="switch controlswitch">

                            @if($dev5_state == 0)
                                <input onclick = 'update_state("{{$room->room_index}}",5,this)' type="checkbox" class="switch-input">
                                @else
                                <input onclick = 'update_state("{{$room->room_index}}",5,this)' type="checkbox" class="switch-input" checked>
                                @endif
                                
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    
                    @endif
                
            </div>
            @endforeach
            </div> <!--END OF ROW-->

        </div>

         <!--*********************NIGHTMODE MAIN TAB***********************-->
         <div id="nightmode" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent nightmodemain">
            
        @if ($rooms->isEmpty())
        <h1 style="font-weight: 300; width: 100%; padding: 20px; background: white; text-align: center; color: red;">No Device Found</h1>
        
        @else
            
        <form class="dirty-check" action="/savenightmode" method="post">
            @csrf
            <h3>Choose what you would like to turn on/off in night mode <span><button type="submit" class="btn btn-success">Save Changes</button></span></h3>
            <div style="margin-left: 10px;" class="row">

            <div class="md-form md-outline col-md-3 ">
                <h6>Night mode :<span>
                    <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                        
                    @if($nightenabled == 0)
                    <input type="checkbox" onclick = 'night_enable(this)' class="switch-input">
                    @else
                    <input type="checkbox" onclick = 'night_enable(this)' class="switch-input" checked>
                    @endif

                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                    </span>
                </h6>
             </div>

            </div>

            <div style="margin-left: 10px;" class="row">

            @foreach ($rooms as $room)         
            @php
            $night_state = $room->night_state;
            $dev1_state = floor($night_state/128);
            if($dev1_state == 1) 
                $night_state -=128;
            $dev2_state = floor($night_state/64);
            if($dev2_state == 1) 
                $night_state -=64;
            $dev3_state = floor($night_state/32);
            if($dev3_state == 1) 
                $night_state -=32;
            $dev4_state = floor($night_state/16);
            if($dev4_state == 1) 
                $night_state -=16;
            $dev5_state = floor($night_state/8);
            if($dev5_state == 1) 
                $night_state -=8;
            $dev5d_state = $night_state;
            
            @endphp
                <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                    <h2>{{$room->name}}</h2>
                    
                        <!-- DEVICE ONE -->
                        @if ($room->dev1_type == 1)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                    
                                    @if($dev1_state == 0)
                                    <input type="checkbox" name="start1{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start1{{$room->id}}" class="switch-input" checked>
                                    @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
                        
                        @elseif($room->dev1_type == 2)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                           </h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                              
                                    @if($dev1_state == 0)
                                    <input type="checkbox" name="start1{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start1{{$room->id}}" class="switch-input" checked>
                                    @endif
                                    
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
    
                        @elseif($room->dev1_type == 3)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                
                                    @if($dev1_state == 0)
                                    <input type="checkbox" name="start1{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start1{{$room->id}}" class="switch-input" checked>
                                    @endif
                                   
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>

                        @endif

                        <!-- DEVICE TWO -->
                        @if ($room->dev2_type == 1)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">

                                    @if($dev2_state == 0)
                                    <input type="checkbox" name="start2{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start2{{$room->id}}" class="switch-input" checked>
                                    @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
                        
                        @elseif($room->dev2_type == 2)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                           </h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                              
                                    @if($dev2_state == 0)
                                    <input type="checkbox" name="start2{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start2{{$room->id}}" class="switch-input" checked>
                                    @endif
                                    
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
    
                        @elseif($room->dev2_type == 3)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                
                                    @if($dev2_state == 0)
                                    <input type="checkbox" name="start2{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start2{{$room->id}}" class="switch-input" checked>
                                    @endif
                                   
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>

                        @endif

                        <!-- DEVICE THREE -->
                        @if ($room->dev3_type == 1)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">

                                    @if($dev3_state == 0)
                                    <input type="checkbox" name="start3{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start3{{$room->id}}" class="switch-input" checked>
                                    @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
                        
                        @elseif($room->dev3_type == 2)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                           </h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                              
                                    @if($dev3_state == 0)
                                    <input type="checkbox" name="start3{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start3{{$room->id}}" class="switch-input" checked>
                                    @endif
                                    
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
    
                        @elseif($room->dev3_type == 3)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                
                                    @if($dev3_state == 0)
                                    <input type="checkbox" name="start3{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start3{{$room->id}}" class="switch-input" checked>
                                    @endif
                                   
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>

                        @endif

                        <!-- DEVICE FOUR -->
                        @if ($room->dev4_type == 1)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">

                                    @if($dev4_state == 0)
                                    <input type="checkbox" name="start4{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start4{{$room->id}}" class="switch-input" checked>
                                    @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
                        
                        @elseif($room->dev4_type == 2)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                           </h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                              
                                    @if($dev4_state == 0)
                                    <input type="checkbox" name="start4{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start4{{$room->id}}" class="switch-input" checked>
                                    @endif
                                    
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>
    
                        @elseif($room->dev4_type == 3)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                
                                    @if($dev4_state == 0)
                                    <input type="checkbox" name="start4{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start4{{$room->id}}" class="switch-input" checked>
                                    @endif
                                   
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            
                        </div>

                        @endif

                        <!-- DEVICE FIVE -->
                        @if ($room->dim_type == 1)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">

                                    @if($dev5_state == 0)
                                    <input type="checkbox" name="start5{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start5{{$room->id}}" class="switch-input" checked>
                                    @endif

                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                Speed: <input type ="range" name="start5d{{$room->id}}" value="{{$dev5d_state}}" min="0" max="7"/>
                            </span></h6>
                            
                        </div>
                        
                        @elseif($room->dim_type == 2)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                           </h4>
                            <h6>Start State: <span>
                            <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">

                                    @if($dev5_state == 0)
                                    <input type="checkbox" name="start5{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start5{{$room->id}}" class="switch-input" checked>
                                    @endif
                                    
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                Speed: <input type ="range" name="start5d{{$room->id}}" value="{{$dev5d_state}}" min="0" max="7"/>
                            </span></h6>
                            
                        </div>
    
                        @elseif($room->dim_type == 3)
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>SOCKET</h4>
                            <h6>Start State: <span>
                            <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">

                                    @if($dev5_state == 0)
                                    <input type="checkbox" name="start5{{$room->id}}" class="switch-input">
                                    @else
                                    <input type="checkbox" name="start5{{$room->id}}" class="switch-input" checked>
                                    @endif
                                   
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            Speed: <input type ="range" name="start5d{{$room->id}}" value="{{$dev5d_state}}" min="0" max="7"/>
                            </span></h6>
                            
                        </div>

                        @endif
                    
                </div>
            @endforeach 

            </div> <!--END OF ROW-->

        </form>
        @endif
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
                src="<?php echo asset("storage/app/public/$image")?>"
            }
            @endif  
            alt="user">
                 
                <input type="file" class="choosedp" accept="image/*" name="dpimg" id="dpfile">
                <label style="margin-top: 20px" class="dpbutton" for="dpfile">Change Image</label>
                <button style="margin-top: 10px; float: right;" type="submit" class="btn btn-primary" disabled>Save Image</button>
            </form>
            </div>

            <div class="settingcontent editemail">
                <label for="emailid">Email: </label>
                <input style="width: 55%;" type="email" class="form-control" name="email" id="emailid" value="{{Auth::user()->email}}" readonly>
                <button style="float: right; margin-top: -37px" class="btn btn-primary" onclick="window.location='{{ url("changeEmail") }}'">Change Email</button>
            </div>

            <div style="padding: 20px 0" class="settingcontent editpassword">
                <label>Password: <span style="color:#858585;">&nbsp;***********</span></label>
                <button style="float: right;" class="btn btn-primary" onclick="window.location='{{ url("changePassword") }}'">Change Password</button>
            </div>
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
                src="<?php echo asset("storage/app/public/$image")?>"
            }
            @endif  
            alt="user">
                 
                <input type="file" class="choosedp" accept="image/*" name="dpimg" id="dpfile">
                <label style="margin-top: 20px" class="dpbutton" for="dpfile">Change Image</label>
                <button style="margin-top: 10px; float: right;" type="submit" class="btn btn-primary" disabled>Save Image</button>
            </form>
            </div>

            <div class="settingcontent editemail">
                <label for="emailid">Email: </label>
                <input style="width: 55%;" type="email" class="form-control" name="email" id="emailid" value="{{Auth::user()->email}}" readonly>
                <button style="float: right; margin-top: -37px" class="btn btn-primary" onclick="window.location='{{ url("changeEmail") }}'">Change Email</button>
            </div>

            <div style="padding: 20px 0" class="settingcontent editpassword">
                <label>Password: <span style="color:#858585;">&nbsp;***********</span></label>
                <button style="float: right;" class="btn btn-primary" onclick="window.location='{{ url("changePassword") }}'">Change Password</button>
            </div>
        </div>

            
        <!--*****************SECURITY LOGs TAB****************-->
        <div id="security" class="tabcontent settingstab ">
            <div  class = "row">
            <div class ="securitydiv col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <h3>Images captured by security camera.</h3>
            </div>
            @php
                $uid = Auth::user()->id;
                $last_snap = Auth::user()->last_snap;
            @endphp
            @for ($i = $last_snap; $i >= 0; $i--)
                @if (file_exists(public_path('storage/app/images/security/user'.$uid.'/snap_'.$i.'.jpg')))
                    <div class = "col-md-6 col-lg-4 col-sm-12 col-xs-12 top-bottom-padd">
                        <img class = "security"
                            src="<?php echo asset("storage/app/images/security/user$uid/snap_$i.jpg")?>"
                            alt="Security Log Image">
                    </div>
                @else
                @endif
            @endfor
            @for ($i = 9; $i > $last_snap; $i--)
                @if (file_exists(public_path('../storage/app/images/security/user'.$uid.'/snap_'.$i.'.jpg')))
                    <div class = "col-md-6 col-lg-4 col-sm-12 col-xs-12 top-bottom-padd">
                        <img class = "security"
                            src="<?php echo asset("storage/app/images/security/user$uid/snap_$i.jpg")?>"
                            alt="Security Log Image">
                    </div>
                @else
                @endif
            @endfor
            </div>

            
        </div>

    </div>
</div>
<script>
$(".GaugeMeter").gaugeMeter();
$('.count').each(function () {
$(this).prop('Counter',0).animate({
    Counter: $(this).text()
}, {
    duration: 4000,
    easing: 'swing',
    step: function (now) {
        $(this).text(Math.ceil(now));
    }
});
});
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