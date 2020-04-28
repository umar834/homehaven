@extends('layouts.mylayout')
<link rel="stylesheet" href="{{ asset('css/Userdashboard.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script> 
<script src="{{ asset('js/GaugeMeter.js')}}"></script> 
<script src="{{ asset('js/userdashboard.js')}}"></script> 
@section('content')
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
        if ($power_data->log_5 != null)
        {
            $watt = $power_data->log_5;
        }
        elseif ($power_data->log_4 != null)
        {
            $watt = $power_data->log_4;
        }
        elseif ($power_data->log_3 != null)
        {
            $watt = $power_data->log_3;
        }
        elseif ($power_data->log_2 != null)
        {
            $watt = $power_data->log_2;
        }
        elseif ($power_data->log_1 != null)
        {
            $watt = $power_data->log_1;
        }
        elseif ($power_data->log_0 != null)
        {
            $watt = $power_data->log_0;
        }
        else {
            $watt = 0;
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
                <p><span class="powerconsumptionspan"><i class="fa fa-arrow-up"></i> 33% increase</span> compared to yesterday</p>
            </div>
            
            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 billprediciton">
                <h3>Bill Predicitometer</h3>
                <div class="currentprediciton">
                    <h4>Current Month Prediciton</h4>
                    <h1><span class="count"> 1780.70 </span><small style="font-size:22px"> PKR</small> </h1>
                    <p><span class="billpredictionspan"><i class="fa fa-arrow-down"></i> 12% decrease</span> compared to last month</p>
                </div>
                <hr style="color: rgb(133, 133, 133);">
                <div class="last6monthsprediciton">
                    <h4>Last Month Bill: <span class="count"> 1945 </span><small> PKR</small><i class="fas fa-tachometer-alt dashboardicon"></i></i> </h4>
                </div>
                <div style="margin-bottom: 10px;" class="last6monthsprediciton">
                    <h4>Last 6 Months Bill Average: <span class="count"> 1720 </span><small> PKR</small><i class="fas fa-tachometer-alt dashboardicon"></i> </h4>
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
        <div id="controls" class="tabcontent controlsdiv">
            <div style="margin-left: 10px;" class="row">
            <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                <h2>Room-1</h2>
                
                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-I<span>
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>

                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-II<span>
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>

                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                            Speed: <input type ="range" min="0" max="10"/>
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>

                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>PLUG<span class="controlspeed">
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>
                
            </div>

            <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                <h2>Room-2</h2>
                
                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-I<span>
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>

                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-II<span>
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>

                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                            Speed: <input type ="range" min="0" max="10"/>
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>

                <div class="controldiv">
                    <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>PLUG<span class="controlspeed">
                        <label class="switch controlswitch">
                            <input type="checkbox" class="switch-input" checked>
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </span></h4>
                </div>
                
            </div>

            </div> <!--END OF ROW-->

        </div>

         <!--*********************CONTROLS MAIN TAB***********************-->
         <div id="nightmode" class="tabcontent nightmodemain">
            <h3>Choose what you would like to turn on/off in night mode <span><button class="btn btn-success">Save Changes</button></span></h3>
            <div style="margin-left: 10px;" class="row">

            <div class="md-form md-outline col-md-3 timepicker">
                <label for="default-picker">Night mode activates at</label>
                <input type="time" id="starttime" min="20:00" max="01:00" value="10:00:AM" class="form-control" placeholder="Select time">
            </div>
            <div class="md-form md-outline col-md-3 timepicker">
                <label for="default-picker">Night mode deactivates At</label>
                <input type="time" id="stoptime" min="20:00" max="01:00" value="10:00:AM" class="form-control" placeholder="Select time">
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6 saveButton">
                
            </div>

            </div>

            <div style="margin-left: 10px;" class="row">
                <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                    <h2>Room-1</h2>
                    
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-I<span>
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-II<span>
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                                Speed: <input type ="range" min="0" max="10"/>
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>PLUG<span class="controlspeed">
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    
                </div>

                <div class="onecontrol col-md-5 col-sm-6 col-xs-12">
                    <h2>Room-2</h2>
                    
                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-I<span>
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-lightbulb"></i>LIGHT-II<span>
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-fan"></i>FAN<span class="controlspeed">
                                Speed: <input type ="range" min="0" max="10"/>
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>

                    <div class="controldiv">
                        <h4><i style="margin-right: 10px;" class="fas fa-plug"></i>PLUG<span class="controlspeed">
                            <label class="switch controlswitch">
                                <input type="checkbox" class="switch-input" checked>
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </span></h4>
                    </div>
                    
                </div>

                </div> <!--END OF ROW-->
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
