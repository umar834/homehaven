<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="A place where you can find best Home Automation Solutions" />
        <meta name="author" content="Muhammad Umar Gulzar & Mehmood Ul Hassan" />
        <title>HomeHaven - Secure Home, Secure Devices</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{asset('images/img/favicon.ico')}}" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- Third party plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/HomePagestyles.css') }}" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">HomeHaven</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#services">Features</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about">About Us</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#our-app">Get App</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#faq">FAQs</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- BANNER-->
        @if (session('success'))
            <div id="test" class="alert alert-success hidecroosss">
                <h3> {{ session('success') }}</h3>
            </div>
        @endif
        <header class="masthead" style="background: linear-gradient(to bottom, rgba(66, 71, 92, 0.8) 0%, rgba(66, 74, 92, 0.8) 100%), url('{{ asset('images/img/bg-masthead.jpg')}}');">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                        <h1 class="text-uppercase text-white font-weight-bold">Smart YOU, Smart HOME</h1>
                        <hr class="divider my-4" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p style="font-size: 22px" class="text-white-75 font-weight-light mb-5">Secured home with devices that are controlled smartly and effectively to save energy and cost!</p>
                    </div>
                </div>
            </div>
        </header>
        <!-- Features-->
        <section class="page-section" id="services">
            <div class="container">
                <h2 class="text-center mt-0">HomeHaven Features</h2>
                <hr class="divider my-4" />
                <div class="row">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-laptop-house text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Smart Home</h3>
                            <p class="text-muted mb-0">Control Devices over the internet & using local network!</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-microphone-alt text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Voice Command</h3>
                            <p class="text-muted mb-0">Control the devices through voice commands.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-tachometer-alt text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Power Monitoring</h3>
                            <p class="text-muted mb-0">You can get the information of power being consumed</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-file-invoice-dollar text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Bill Prediction </h3>
                            <p class="text-muted mb-0">Prdicts the electricity bill so that you can control your budget</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-robot text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Auto Mode</h3>
                            <p class="text-muted mb-0">Let the system control your devices while you relax</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-cloud-moon text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Night Mode</h3>
                            <p class="text-muted mb-0">Save your time and let system change status of devices for night</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-comment text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Suggestions & Tips</h3>
                            <p class="text-muted mb-0">System provies suggestions and tips to use system in best way</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-shield-alt text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Security</h3>
                            <p class="text-muted mb-0">You get not only the better control but also the better security</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About-->
        <section class="page-section bg-primary" id="about">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">HomeHaven</h2>
                        <hr class="divider light my-4" />
                        <p style="color: white !important; font-size: 18px" class="text-white-50 mb-4">HomeHaven is an IoT based company that has the aim to take Internet
                            of things to the next level. We are here to provide you with the secure, reliable and fast
                            home Automation solutions that would help you keep your home not only autmoated but also secure. We 
                            provide online controlable devices with power consumpution updates, security alerts and much much more...
                        </p>
                    </div>
                </div>
            </div>
        </section>
         <!-- Dashboard-->
         <section class="page-section bg-light text-black" id="dashboard">
            <div class="container text-center">
                <h2 class="mb-4">Member of HomeHaven? </h2>
                <h5 style="color: #007bff; margin-top: -20px;">Open Your Dashboard Here</h5>
                <p style="padding: 3% 10%">If you are using our services and have got installed HomeHaven System at your home then you can 
                    open your dashboard here. Monitor and control your devices from our web service where we provide 
                    all the functionalities for you. To open your dashboard please click on the button bellow.
                </p>
                <a style="background-color: #007bff" class="btn btn-dark btn-xl" href="{{ url('/home') }}">Goto Dashboard</a>
            </div>
        </section>

         <!-- Dashboard-->
         <section class="page-section bg-dark text-white" id="our-app">
            <div class="container text-center">
                <h2 class="mb-4">Get Our Mobile App </h2>
                <img style="width: 25%" src="{{asset('images/img/Mobile.png')}}" alt="">
                <p style="padding: 3% 10%">
                    Download our mobile application and experience the features of our product with 
                    100% control over the devices, power monitoring, get tips and suggestions, keep an eye over 
                    the house even when you are out and much much more.
                </p>
                <a class="btn btn-light btn-xl" href="#">Download App Now!</a>
            </div>
        </section>
        <!-- FAQ-->
        <section class="page-section bg-light text-black" id="faq">
            <div class="container text-center">
                <h2 class="mb-4">Frequently Asked Questions</h2>
                <div class="accordion">
                    <div class="accordion-item">
                      <button id="accordion-button-1" aria-expanded="false"><span class="accordion-title">What is HomeHaven?</span><span class="icon" aria-hidden="true"></span></button>
                      <div class="accordion-content">
                        <p>HomeHaven is an IoT based company with the aim to provide its customers best
                            Home Automation experience.
                        </p>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button id="accordion-button-2" aria-expanded="false"><span class="accordion-title">What is Auto Mode?</span><span class="icon" aria-hidden="true"></span></button>
                      <div class="accordion-content">
                        <p>Auto Mode is one of the great features of HomeHaven. User can activate this mode and 
                            then the devices will be controlled by the system and user won't have to do anything. 
                            System will analyze the suroundings and previous data to manage all devices. 
                        </p>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button id="accordion-button-3" aria-expanded="false"><span class="accordion-title">What is Night Mode?</span><span class="icon" aria-hidden="true"></span></button>
                      <div class="accordion-content">
                        <p>User can set his preferences that what devices he wants to turn on/off when night starts and what will be 
                            their status at morning time. This way user will not have to change devices' status each morning and night, 
                            devices will change their status automatically at night and change back at morning, all according to user's settings. 
                        </p>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button id="accordion-button-4" aria-expanded="false"><span class="accordion-title">How can I control my devices?</span><span class="icon" aria-hidden="true"></span></button>
                      <div class="accordion-content">
                        <p>User can control devices through local connection when near the devices or over the internet 
                            when he is not home. There is an android application that user can use. Also user can access all the 
                            devices through Dashboard available on our website.
                        </p>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <button id="accordion-button-5" aria-expanded="false"><span class="accordion-title">What is Bill Prediction?</span><span class="icon" aria-hidden="true"></span></button>
                      <div class="accordion-content">
                        <p>Bill prediction is an amazing feature of HomeHaven. User can get updates on each month bill 
                            prediction so that he can control his budget. 
                        </p>
                      </div>
                    </div>
                    <div class="accordion-item">
                        <button id="accordion-button-5" aria-expanded="false"><span class="accordion-title">Why HomeHaven is secure?</span><span class="icon" aria-hidden="true"></span></button>
                        <div class="accordion-content">
                          <p>HomeHaven includes a Camera that records events and provides alerts to the the user 
                              on mobile application. 
                          </p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button id="accordion-button-5" aria-expanded="false"><span class="accordion-title">How can I install HomeHaven?</span><span class="icon" aria-hidden="true"></span></button>
                        <div class="accordion-content">
                          <p>All you need to do is provide your information in Contact Us form below and 
                              our team will contact you soon. We will take all required information and then 
                              install the system at your house according to your requirements. 
                          </p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button id="accordion-button-5" aria-expanded="false"><span class="accordion-title">How can I get access to dashboard?</span><span class="icon" aria-hidden="true"></span></button>
                        <div class="accordion-content">
                          <p>At the time of system isntallation, user gets credentials that can be used 
                              to login into mobile device as well as Web Dashboard. If you have forgotten the password 
                              you can reset it through forget password link on the login page. For further help please 
                              contact us.  
                          </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Portfolio-->
        <div id="portfolio">
            <div class="container-fluid p-0">
                <div class="row no-gutters">
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="{{asset('images/img/portfolio/fullsize/1.jpg')}}">
                            <img class="img-fluid" src="{{asset('images/img/portfolio/fullsize/1.jpg')}}" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Control Devices</div>
                                <div class="project-name">Control Your Devices Smartly</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="{{asset('images/img/portfolio/fullsize/2.jpg')}}">
                            <img class="img-fluid" src="{{asset('images/img/portfolio/fullsize/2.jpg')}}" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Voice Commands</div>
                                <div class="project-name">Control Devices Through Voice Commands</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="{{asset('images/img/portfolio/fullsize/3.jpg')}}">
                            <img class="img-fluid" src="{{asset('images/img/portfolio/fullsize/3.jpg')}}" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Power Monitoring</div>
                                <div class="project-name">Get Updates of Power Consumpution</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="{{asset('images/img/portfolio/fullsize/4.jpg')}}">
                            <img class="img-fluid" src="{{asset('images/img/portfolio/fullsize/4.jpg')}}" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Bill Prediction</div>
                                <div class="project-name">Get Information of Predicted <span class="badge badge-pill badge-primary"></span></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="{{asset('images/img/portfolio/fullsize/5.jpg')}}">
                            <img class="img-fluid" src="{{asset('images/img/portfolio/fullsize/5.jpg')}}" alt="" />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Auto Mode</div>
                                <div class="project-name">Let The System Control Devices</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="{{asset('images/img/portfolio/fullsize/6.jpg')}}">
                            <img class="img-fluid" src="{{asset('images/img/portfolio/fullsize/6.jpg')}}" alt="" />
                            <div class="portfolio-box-caption p-3">
                                <div class="project-category text-white-50">Security</div>
                                <div class="project-name">More Secure House</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact-->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="mt-0">Contact Us</h2>
                        <hr class="divider my-4" />
                        <p class="text-muted mb-5">Get in touch with us.</p>
                    </div>
                </div>
                <form action="{{ url('/contactus') }}" method="POST">
                    @csrf
                    <div style="margin-top: -50px" class="row input-container">
                        <div style="padding-right: 0px; padding-left: 0px;" class="col-sm-12">
                            <div class="styled-input wide">
                                <input name="name" type="text" required />
                                <label>Name</label> 
                            </div>
                        </div>
                        <div style="padding-right: 0px; padding-left: 0px;" class="col-md-6 col-sm-12">
                            <div class="styled-input">
                                <input name="email" type="email" required />
                                <label>Email</label> 
                            </div>
                        </div>
                        <div style="padding-right: 0px; padding-left: 0px;" class="col-md-6 col-sm-12">
                            <div class="styled-input" style="float:right;">
                                <input name="number" type="text" required />
                                <label>Phone Number</label> 
                            </div>
                        </div>
                        <div style="padding-right: 0px; padding-left: 0px;" class="col-sm-12">
                            <div class="styled-input wide">
                                <textarea name="message" required></textarea>
                                <label>Message</label>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <button type="submit" style="float: right" class="btn-lrg submit-btn">Send Message</button>
                        </div>
                    </div>
                </form>
                <div style="text-align: center; margin-top: 20px;" class="row">
                    <h4 style="width: 100%; padding: 20px;" class="mt-0">Or Find Us phone/email</h4>
                    <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                        <i style="color: #007bff !important" class="fas fa-phone fa-3x mb-3 text-muted"></i>
                        <div>+92 51 123-4567</div>
                    </div>
                    <div class="col-lg-4 mr-auto text-center">
                        <i style="color: #007bff !important" class="fas fa-envelope fa-3x mb-3 text-muted"></i>
                        <!-- Make sure to change the email address in BOTH the anchor text and the link target below!-->
                        <a class="d-block" href="mailto:contact@homehaven.website">contact@homehaven.website</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container"><div class="small text-center text-muted">Copyright © 2020 - HomeHaven</div></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{asset('js/HomePagescripts.js')}}"></script>
        <script>
            const items = document.querySelectorAll(".accordion button");

            function toggleAccordion() {
            const itemToggle = this.getAttribute('aria-expanded');
            
            for (i = 0; i < items.length; i++) {
                items[i].setAttribute('aria-expanded', 'false');
            }
            
            if (itemToggle == 'false') {
                this.setAttribute('aria-expanded', 'true');
            }
            }

            items.forEach(item => item.addEventListener('click', toggleAccordion));

            $(function() {
                $('#test').delay(4000).fadeOut();
            });
        </script>
    </body>
</html>
