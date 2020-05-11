@extends('layouts.mylayout')
<link rel="stylesheet" href="{{ asset('css/Userdashboard.css') }}">
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
@if (session('nightmodesaved'))
<div style="text-align: center" class="hidecroosss">
    <h3 style="color: #2779ff; padding: 20px">Night mode changes saved to table</h3>
</div>
@endif
<div class="row maindiv">
    <!--***********TABS MAIN DIV***********-->
    <div class="tab-div col-lg-2 col-sm-3 col-md-3">
        <div class="logoDiv">
            <h3>HomeHaven</h3>
        </div>

        <div class="userInfo">
            <img 
            @if (Auth::user()->image == null){
            src="{{asset('images/user.png')}}"
             }
            @endif 
            width="80" alt="user">
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
    
    <!--PHP CODE TO CHECK FOR LEAST NOT NULL VALUE-->
    @php
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

        if($bill->predicted_amount > $lastmonthbill->amount)
        {
            $bill_increase = $bill->predicted_amount - $lastmonthbill->amount;
            $bill_increase = ($bill_increase / $lastmonthbill->amount) * 100;
            if($bill_increase == 0)
            {
                $bill_increase = 1;
            }
            $bill_increase = number_format((float)$bill_increase, 2, '.', '');
        }

        else
        {
            $bill_decrease = $lastmonthbill->amount - $bill->predicted_amount;
            $bill_decrease = ($bill_decrease / $bill->predicted_amount) * 100;
            if($bill_decrease == 0)
            {
                $bill_decrease = 1;
            }
            $bill_decrease = number_format((float)$bill_decrease, 2, '.', '');
        }
    @endphp
    <!--***********CONTENT MAIN DIV***********-->
    <div class="content-div col-lg-10 col-sm-9 col-xs-12 col-md-9">

        <!--MAIN WINDOW TAB-->
        <div class="tabcontent active1" id="mainwindow" >
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 energydiv">
                <h3>Energy currently being consumed</h3>
                <div class="GaugeMeter" id="PreviewGaugeMeter_4"
                        data-used="{{$watt}}"
                        data-total="1000"
                        data-text="{{$watt}}"
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
                @if($increase != null)
                <p><span class="powerconsumptionspan"><i class="fa fa-arrow-up"></i> {{$increase}}% increase</span> compared to yesterday</p>
                @else
                <p><span class="billpredictionspan"><i class="fa fa-arrow-down"></i> {{$decrease}}% decrease </span> compared to yesterday</p>
                @endif
            </div>

            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 billprediciton">
                <h3>Bill Predicitometer</h3>
                <div class="currentprediciton">
                    <h4>Current Month Prediciton</h4>
                <h1><span class="count"> {{$bill->predicted_amount}} </span><small style="font-size:22px"> PKR</small> </h1>
                @if($bill_increase != null)
                <p><span style="color: red" class="powerconsumptionspan"><i class="fa fa-arrow-up"></i> {{$bill_increase}}% increase</span> compared to last month</p>
                @else
                <p><span style="color: blue"class="billpredictionspan"><i class="fa fa-arrow-down"></i> {{$bill_decrease}}% decrease </span> compared to last month</p>
                @endif
                </div>
                <hr style="color: rgb(133, 133, 133);">
                <div class="last6monthsprediciton">
                    <h4>Last Month Bill: <span class="count"> {{$lastmonthbill->amount}} </span><small> PKR</small><i class="fas fa-tachometer-alt dashboardicon"></i></i> </h4>
                </div>
            </div>
        </div>

        <!--END OF TOP ROW-->
        <!--START OF SECOND ROW-->
        <div class="row roomsmaindiv">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="frequentlyusedcontrolles">
                    <h2>Frequently Used</h2>
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 freqcontrol">
                            <i class="fas fa-lightbulb"></i>
                            <label class="switch freqswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <h4>Kitchen Light</h4>
                        </div>

                        <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 freqcontrol">
                            <i class="fas fa-lightbulb"></i>
                            <label class="switch freqswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <h4>Living Room Light-1</h4>
                        </div>

                        <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 freqcontrol">
                            <i class="fas fa-fan"></i>
                            <label class="switch freqswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <div class="slider">
                                <label for="">Speed:
                                <input type ="range" min="0" max="10" onchange="rangevalue.value=value"/>
                                <output id="rangevalue"></output>
                                </label>
                            </div>
                            <h4>Living Room Fan-1</h4>
                        </div>

                        <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 freqcontrol">
                            <i class="fas fa-video"></i>
                            <label class="switch freqswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <h4>Camera-1</h4>
                        </div>

                        <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 freqcontrol">
                            <i class="fas fa-fan"></i>
                            <label class="switch freqswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <div class="slider">
                                <label for="">Speed:
                                <input type ="range" min="0" max="10" onchange="rangevalue1.value=value"/>
                                <output id="rangevalue1"></output>
                                </label>
                            </div>
                            <h4>Bedroom-1 Fan</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>


        <!--*********************CONTROLS MAIN TAB***********************-->
        <div id="controls" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent controlsdiv">
        <div style="margin-left: 10px; " class="row">
        @php
            $room_count = 0;
        @endphp
        @foreach ($devices as $item)         
               
            <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                <h2>{{$rooms[$room_count]->name}}</h2>
                @php
                    $room_count += 1;
                @endphp
                @foreach ($item as $device)
                    @if ($device->device_type == "light")
                    <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>Light<span>
                            <label class="switch controlswitch">
                                @if ($device->State == 1)
                                <input type="checkbox" class="switch-input" checked>
                                @else
                                <input type="checkbox" class="switch-input">
                                @endif
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @elseif($device->device_type == "fan")
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                        Speed: <input type ="range" value="{{$device->State}}" min="0" max="10"/>
                            <label class="switch controlswitch">
                                @if ($device->State > 0)
                                <input type="checkbox" class="switch-input" checked>
                                @else
                                <input type="checkbox" class="switch-input">
                                @endif
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($device->device_type == "socket")
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>PLUG<span class="controlspeed">
                            <label class="switch controlswitch">
                                @if ($device->State == 1)
                                <input type="checkbox" class="switch-input" checked>
                                @else
                                <input type="checkbox" class="switch-input">
                                @endif
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    @elseif($device->device_type == "camera")
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-video"></i>Camera<span class="controlspeed">
                            <label class="switch controlswitch">
                                @if ($device->State == 1)
                                <input type="checkbox" class="switch-input" checked>
                                @else
                                <input type="checkbox" class="switch-input">
                                @endif
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    @endif
                @endforeach
                
            </div>
            @endforeach
            </div> <!--END OF ROW-->

        </div>

         <!--*********************NIGHTMODE MAIN TAB***********************-->
         <div id="nightmode" style="overflow-x: hidden; overflow-y:auto; max-height: 100%;" class="tabcontent nightmodemain">
            
        <form class="dirty-check" action="/savenightmode" method="post">
            @csrf
            <h3>Choose what you would like to turn on/off in night mode <span><button type="submit" class="btn btn-success">Save Changes</button></span></h3>
            <div style="margin-left: 10px;" class="row">

            <div class="md-form md-outline col-md-3 timepicker">
                <label for="default-picker">Night mode activates at</label>
                <input type="time" name="starttime" id="starttime" min="19:00:00" max="23:59:00" value="{{$nightstarttime}}" class="form-control" placeholder="Select time" required>
            </div>
            <div class="md-form md-outline col-md-3 timepicker">
                <label for="default-picker">Night mode deactivates At</label>
            <input type="time" name="stoptime" id="stoptime" min="04:00:00" max="10:00:00" value="{{$nightendtime}}" class="form-control" placeholder="Select time" required>
            </div>

            </div>

            <div style="margin-left: 10px;" class="row">
                @php
                $room_count = 0;
                @endphp
            @foreach ($devices as $item)         
                   
                <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                    <h2>{{$rooms[$room_count]->name}}</h2>
                    @php
                        $room_count += 1;
                    @endphp
                    @foreach ($item as $device)
                        @if ($device->device_type == "light")
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>Light</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                    @if ($device->Night_start_state == 1)
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            <h6>End State: <span>
                                <label style="margin-left:28px; margin-top: 0px; float:none;" class="switch controlswitch">
                                    @if ($device->Night_end_state == 1)
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                        </div>
                        
                        @elseif($device->device_type == "fan")
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                           </h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                    @if ($device->Night_start_state == 1)
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            <h6>End State: <span>
                                <label style="margin-left:28px; margin-top: 0px; float:none;" class="switch controlswitch">
                                    @if ($device->Night_end_state == 1)
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                        </div>
    
                        @elseif($device->device_type == "socket")
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>Socket</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                    @if ($device->Night_start_state == 1)
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            <h6>End State: <span>
                                <label style="margin-left:28px; margin-top: 0px; float:none;" class="switch controlswitch">
                                    @if ($device->Night_end_state == 1)
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                        </div>
    
                        @elseif($device->device_type == "camera")
                        <div class="controldiv">
                            <h4><i style="margin-right: 10px;" class="fas fa-video"></i>Camera</h4>
                            <h6>Start State: <span>
                                <label style="margin-left:20px; margin-top: 0px; float: none;" class="switch controlswitch">
                                    @if ($device->Night_start_state == 1)
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="start{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                            <h6>End State: <span>
                                <label style="margin-left:28px; margin-top: 0px; float:none;" class="switch controlswitch">
                                    @if ($device->Night_end_state == 1)
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input" checked>
                                    @else
                                    <input type="checkbox" name="end{{$device->id}}" class="switch-input">
                                    @endif
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </span></h6>
                        </div>
                        @endif
                    @endforeach
                    
                </div>
                @endforeach 

            </div> <!--END OF ROW-->

        </form>

         </div>


        <!--*****************SETTINGS TAB****************-->
        <div id="settings" class="tabcontent settingstab col-md-8 col-lg-6 col-sm-12 col-xs-12">
            <div class="editdp settingcontent">
                <img id="uploadeddp" src="{{asset('images/user.png')}}" alt="">
                <input type="file" class="choosedp" accept="image/*" name="dpimg" id="dpfile">
                <label class="dpbutton" for="dpfile">Edit</label>
            </div>

            <div class="settingcontent editemail">
                <label for="emailid">Email: </label>
                <input type="email" class="form-control" name="email" id="emailid" value="user@gmail.com">
            </div>

            <div class="settingcontent editpassword">
                <label>Password: <span style="color:#858585;">&nbsp;***********</span></label>
                <label onclick="window.location='{{ url("changePassword") }}'" class="dpbutton editpasswordbutton">Edit</label>
            </div>
            <div class="settingcontent">
                <button type="submit" class="btn btn-success">Save</button>
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
document.getElementById("starttime").defaultValue = "20:00";
document.getElementById("stoptime").defaultValue = "06:00";
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
</script>
@endsection