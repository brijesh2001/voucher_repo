@extends('layouts.front.app')
@section('content')

    <!-- banner-->


    <section id="bannerContainer" class="section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-10">
                    <div class="contents text-center">
                        <h1 class="wow fadeInDown headline" data-wow-duration="1000ms" data-wow-delay="0.3s">PTE
                            Voucher Online At ₹ {{$rate or ''}} & Get Free</h1>
                        <h4 class="wow fadeInDown top-class" data-wow-duration="1000ms" data-wow-delay="0.3s">15 Scored
                            Mock Test Free</h4>
                        <h4 class="wow fadeInDown top-class" data-wow-duration="1000ms" data-wow-delay="0.3s">Get the
                            best real time platform with updated question banks</h4>
                    </div>
                </div>
                <div class="col-md-10 pteForm">
                    <div class="contents1 text-center">
                        <div class="help-block with-errors">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul id='error-list'>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="flash-message">
                                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                    @if(Session::has('alert-' . $msg))
                                        <p class="alert alert-{{ $msg }}"
                                           style="text-align:center;">{{ Session::get('alert-' . $msg) }} <a
                                                    href="#"></a>
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <form class="pay-form" method="post" id="ptevouForm" action="{{url('pte/confirm')}}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="state" id="state" required>
                                            <option value="">State</option>
                                            @if(count($state) > 0)
                                                @foreach($state as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{$name or ''}}" placeholder="Name" required
                                               data-error="Please enter your name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="mobile" name="mobile"
                                               value="{{$mobile or ''}}" placeholder="Mobile No." maxlength="10"
                                               minlength="10" required data-error="Please enter your mobile no.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="email" placeholder="Email" id="email" class="form-control"
                                               value="{{$email or ''}}" name="email" required
                                               data-error="Please enter your email">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="number_of_voucher" id="number_of_voucher"
                                                value="{{old('number_of_voucher')}}" required>
                                            <option value="">QTY.No of Discounted PTE Voucher</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{--<input type="text" placeholder="GST No If any" id="client_gstn" class="form-control"
                                               value="{{$client_gstn or ''}}" pattern="[a-zA-Z0-9-]+" maxlength="15" minlength="15" name="client_gstn">--}}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="{{$user_id or ''}}">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <p id="hide_mobile" style="margin-bottom: 0px;margin-top: -7px">PTE Voucher Price : 10593 + 1907 (18%
                                        GST) = {{$rate or ''}} INR</p>
                                    <div class="submit-button text-center">
                                        <button class="btn btn-common1" id="submit" type="submit"
                                                style="cursor: pointer">Buy Now
                                        </button>
                                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Features Section Start -->


    <section id="features" class="section" data-stellar-background-ratio="0.2">
        <div class="container-fluid">
            <div class="section-header">
                <h2 class="section-title">HOW TO BOOK <span>PTE EXAM DATE ? </span></h2>
                <hr class="lines">
                <p class="section-subtitle">Book your exam with purchased voucher code</p>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-6">
                    <div class="screenshot_leptop">
                        <img class="img-responsive" src="{{url('css/front/img/lappy3.png')}}" alt="PTE Exam Platform">
                    </div>
                    <div id="demo" class="carousel slide" data-ride="carousel">

                        <!-- Indicators -->
                        <ul class="carousel-indicators">
                            <li data-target="#demo" data-slide-to="0" class="active"></li>
                            <li data-target="#demo" data-slide-to="1"></li>
                            <li data-target="#demo" data-slide-to="2"></li>
                            <li data-target="#demo" data-slide-to="3"></li>
                            <li data-target="#demo" data-slide-to="4"></li>
                            <li data-target="#demo" data-slide-to="5"></li>
                            <li data-target="#demo" data-slide-to="6"></li>
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{url('css/front/img/step1.png')}}" alt="PTE login" width="1100" height="500">
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('css/front/img/step2.png')}}" alt="Pearson Registration" width="1100" height="500">
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('css/front/img/step3.png')}}" alt="Pearson fill detail" width="1100" height="500">
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('css/front/img/step4.png')}}" alt="Pearson confirm details" width="1100" height="500">
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('css/front/img/step5.png')}}" alt="Pearson Schedule dates" width="1100" height="500">
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('css/front/img/step6.png')}}" alt="Pearson Confirm mail" width="1100" height="500">
                            </div>
                            <div class="carousel-item">
                                <img src="{{url('css/front/img/step7.png')}}" alt="Pearson Thank you" width="1100" height="500">
                            </div>
                        </div>


                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#demo" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#demo" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-xs-6 stepscontainer">
                    <h2>
                        STEPS TO BOOK PTE EXAM DATE
                    </h2>
                    <ul>
                        <li>Buy a Voucher Code from ptevouchercode.com.</li>
                        <li>Create your ACCOUNT on <a href="https://www.pearsonpte.com/book"rel="nofollow">
                                pearsonpte.com/book</a></li>
                        <li>Login to pearsonpte.com using your Username and Password</li>
                        <li>Enter your preferred date, location and other details</li>
                        <li>Enter PTE Voucher Code on the payment page.</li>
                        <li>Receive CONFIRMATION email from PTE about your test booking.</li>
                    </ul>
                    <a href="#" class="btn btn-common wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="400ms"
                       style="border: 1px solid red;background: darkred;">BOOK NOW</a>
                </div>

            </div>
        </div>
        <!--  </div> -->
    </section>
    <!-- Features Section End -->

    <!-- Why us Section Start -->
    <section id="featuresnew" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Why
                    <span>Us</span></h2>
                <hr class="lines wow zoomIn" data-wow-delay="0.3s">
                <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">100 % customer
                    satisfaction with  Customer support.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="content-left text-right wow fadeInLeft animated" data-wow-offset="10">
                        <div class="box-item left">
                <span class="icon">
                  <i class="lnr lnr-rocket"></i>
                </span>
                            <div class="text">
                                <h4>Customer Care</h4>
                                <p>We are available to assist you on call & Whats App  on +91-9099-500-925</p>
                            </div>
                        </div>
                        <div class="box-item left">
                <span class="icon">
                  <i class="lnr lnr-laptop-phone"></i>
                </span>
                            <div class="text">
                                <h4>Lowest Price</h4>
                                <p>PTE Academic Cost Rs. 13,300 in India. Purchase Voucher Code & Save
                                    Rs. {{$saved_prize or ''}}</p>
                            </div>
                        </div>
                        <div class="box-item left">
                <span class="icon">
                  <i class="lnr lnr-cog"></i>
                </span>
                            <div class="text">
                                <h4>High Success Rate</h4>
                                <p> Featured due to high success rate.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="show-box wow fadeInDown animated" data-wow-offset="10">

                        <img src="{{url('css/front/img/features/feature.jpg')}}" alt="PTE Feature">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="content-right text-left wow fadeInRight animated" data-wow-offset="10">
                        <div class="box-item right">
                <span class="icon">
                  <i class="lnr lnr-camera-video"></i>
                </span>
                            <div class="text">
                                <h4>Secure Payment</h4>
                                <p>To make your payment more secure we have made this website SSL SECURED.</p>
                            </div>
                        </div>
                        <div class="box-item right">
                <span class="icon">
                  <i class="lnr lnr-magic-wand"></i>
                </span>
                            <div class="text">
                                <h4>25000+ No. of Students</h4>
                                <p>Thousands of Student trust on us. We also help to book your exam.</p>
                            </div>
                        </div>
                        <div class="box-item right">
                <span class="icon">
                  <i class="lnr lnr-spell-check"></i>
                </span>
                            <div class="text">
                                <h4>Customer Satisfaction</h4>
                                <p>100% Customer Satisfaction</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Why us Section End -->


    <!-- Start Intro promo Section -->
    <section class="video-promo section">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="video-promo-content text-center">

                    </div>
                </div>
                <div class="col-lg-8 video-promotext-right">
                    <div class="video-promo-content">
                        <h2 class="wow zoomIn" data-wow-duration="1000ms" data-wow-delay="100ms">Buy PTE Voucher Code ₹ {{$rate or ''}} Get ₹ {{$saved_prize or ''}} Discount</h2>
                        <p class="wow zoomIn" data-wow-duration="1000ms" data-wow-delay="100ms">For Your Successful
                            Academic and Career Endeavors, We Partner You at The Least Costs! We provide you a reliable
                            and pocket-friendly option to take one of the best English proficiency Pearson Test i.e. PTE
                            Exam. We Provide Complete Assistance, Guidance, And Numerous Handbooks and Supporting
                            Material to Help You Clear the Exam in The First Attempt Itself. </p>
                        <p class="wow zoomIn" data-wow-duration="1000ms" data-wow-delay="100ms">We Are Known for Giving
                            Our Best and Unmatched Services to Students Who Dream High. With Us, You Will Get the Best
                            Value for Your Money and That Too at Reasonable Price as We Are Authorized Sellers of PTE
                            Exam Vouchers. </p>
                        <p class="wow zoomIn" data-wow-duration="1000ms" data-wow-delay="100ms">On Booking Your PTE Exam
                            Through Us, You Will Be Getting the Highest Discount on Your Application Fee in India. For
                            Further Countenance, We Are Providing 15 Scored Mock Test Papers And Experts Counseling for
                            Beginners. If You Are Looking for An Opportunity to Study in Foreign, Then Look Further
                            Because PteVoucherCode Is the Best Destination to Make Your Offshore Study Dreams Come
                            True.</p>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Video Promo Section -->

    <!-- Services Section Start -->
    <section id="services" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">About
                    <span>Us</span></h2>
                <hr class="lines wow zoomIn" data-wow-delay="0.3s">
                <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">PTE Voucher Code
                    is an e-commerce venture by Compass Overseas that has been serving the Education sector and providing PTE voucher at lowest price.</p>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="item-boxes wow fadeInDown" data-wow-delay="0.2s">
                        <p>Since the inauguration of Compass Overseas, the company has continually
                            worked for students and professional by helping, assisting, guiding and letting them achieve
                            their goals of staying or studying in foreign Countries. Compass Migration and Education
                            Consultant is acting as a ladder of success for thousands of students who are thriving
                            towards their goals of better educational facilities and thus, the better career. Candidates
                            of PTE Academic are given special discounts and guidance for study material so that they can
                            stay in developed countries like USA, UK, Canada and New Zealand for success Endeavor of
                            gaining skills, knowledge and a better life!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Counter Section Start -->
    <div class="counters section" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3 col-lg-3">
                    <div class="facts-item">
                        <div class="icon">
                            <i class="lnr lnr-user"></i>

                        </div>
                        <div class="fact-count">
                            <h3><span class="counter">25,000</span>+</h3>
                            <h4>Students</h4>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3">
                    <div class="facts-item">
                        <div class="icon">
                            <i class="lnr lnr-briefcase"></i>
                        </div>
                        <div class="fact-count">
                            <h3><span class="counter">100</span>%</h3>
                            <h4>Customer Satisfaction</h4>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3">
                    <div class="facts-item">
                        <div class="icon">
                            <i class="lnr lnr-clock"></i>
                        </div>
                        <div class="fact-count">
                            <h3><span class="counter">100</span>%</h3>
                            <h4>High success rate.</h4>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3">
                    <div class="facts-item">
                        <div class="icon">
                            <i class="lnr lnr-heart"></i>
                        </div>
                        <div class="fact-count">
                            <h3><span class="counter">100</span>%</h3>
                            <h4>Lowest Prices in India.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Counter Section End -->



    <!-- testimonial Section Start -->
    <div id="testimonial" class="section" data-stellar-background-ratio="0.1">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-12">
                    <div class="touch-slider owl-carousel owl-theme">
                        <div class="testimonial-item">
                            <img src="https://www.ptevouchercode.com/css/front/images/c4.png" alt="Client Testimonial"/>
                            <div class="testimonial-text">
                                <p>Nice deal,got good discount on PTE exam voucher + awesome customer service.</p>
                                <h3>SYED IMAD</h3>
                                <!-- <span>Fondor of Jalmori</span> -->
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <img src="https://www.ptevouchercode.com/css/front/images/c3.png" alt="Client Testimonial"/>
                            <div class="testimonial-text">
                                <p>Many many thanks to Compass Overseas to help me a lot by <br>providing a large no. of
                                    tests that were very beneficial for me.</p>
                                <h3>RAVI CHAND GADE</h3>
                                <!-- <span>President Lexo Inc</span> -->
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <img src="https://www.ptevouchercode.com/css/front/images/c2.png" alt="Client Testimonial"/>
                            <div class="testimonial-text">
                                <p>Thanks Compass Overseas for the great help.The executive staff is <br>really good and
                                    proactive at helping the students to<br> purchase the PTE exam vouchers".</p>
                                <h3>PAANI VERMA</h3>
                                <!--  <span>CEO Optima Inc</span> -->
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <img src="https://www.ptevouchercode.com/css/front/images/c5.png" alt="Client Testimonoal"/>
                            <div class="testimonial-text">
                                <p>Thanks to CompassOverseas for giving me such a wonderful opportunity<br> to purchase
                                    at very lower rates , and indeed the test papers <br>are really good and helpful.
                                </p>
                                <h3>THATTALA RAJYALAKSHMI</h3>
                                <!-- <span>CEO & Founder</span> -->
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <img src="https://www.ptevouchercode.com/css/front/images/c1.png" alt="Client Testimonial"/>
                            <div class="testimonial-text">
                                <p>Excellent service from Compass Overseas and I have taken PTE voucher<br> and
                                    immediately they have mailed me free <br>PTE preparation material.</p>
                                <h3>Jyotika</h3>
                                <!-- <span>CEO & Founder</span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section id="faq" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">FAQ
                    <span>'S</span></h2>
                <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Nav tabs category -->
                    <ul class="nav nav-tabs faq-cat-tabs">
                        <li class="active"><a href="#faq-cat-1" data-toggle="tab"><i class="glyphicon glyphicon-user"></i>General FAQs</a></li>
                        <li><a href="#faq-cat-2" data-toggle="tab"><i class="glyphicon glyphicon-plus"></i>Voucher Processing and Booking</a></li>
                        <li><a href="#faq-cat-3" data-toggle="tab"><i class="glyphicon glyphicon-plus"></i>PTE Refund Policy & other details</a></li>
                        <li><a href="#faq-cat-4" data-toggle="tab"><i class="glyphicon glyphicon-plus"></i>Rescheduling process & charges</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content faq-cat-content">

                        <div class="tab-pane active in fade" id="faq-cat-1">
                            <div class="panel-group" id="accordion-cat-1">
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#faq-cat-1-sub-1">
                                            <h4 class="panel-title">
                                               How and where to buy the PTE Voucher?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-1-sub-1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Go to the website: https://www.ptevouchercode.com/ in order to avail the voucher code on discounted rates. All you need to fill your required details along with payment on this website to grab the PTE voucher.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#faq-cat-1-sub-2">
                                            <h4 class="panel-title">
                                                Why should I buy PTE Voucher from PTEVoucherCode?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-1-sub-2" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            PTEVoucherCode offers highest discounts and easy way to schedule PTE Academic Exam. To avoid hassles, you should buy from the best PTE Exam Voucher website i.e. PTEVoucherCode
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#faq-cat-1-sub-3">
                                            <h4 class="panel-title">
                                               What will be the validity of PTE voucher to redeem on its website?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-1-sub-3" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            You can redeem it anytime till 10 months from the date of purchase at any center across India.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#faq-cat-1-sub-4">
                                            <h4 class="panel-title">
                                                What will be the validity of PTE voucher to redeem on its website?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-1-sub-4" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            •	For education purpose, all countries accept PTE.<br>
                                            •	For Migration and PR, only Australia and NZ accept the PTE Academic.<br>
                                            •	For more details, you can check on this link: http://pearsonpte.com/test-takers/accepts/
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="faq-cat-2">
                            <div class="panel-group" id="accordion-cat-2">
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-2-sub-1">
                                            <h4 class="panel-title">
                                               How to apply the purchased voucher on to the Pearson website?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-2-sub-1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Very first, you need to go on www.pearsonpte.com. Then, you need to click on the “BOOK NOW” and you will find the “SIGN IN” button so just click on that.
                                            After that, you will see a column with the option of “Schedule Exam” and you need to click on that.
                                            Now click on search button to select your preferred centers. You need to select at least 5 exam centers along with your suitable date and time slot. Now click on “SELECT APPOINTMENT”.
                                            After clicking on the select appointment, you will see some registration questions. The next step goes to scroll down your page to view the voucher tab where you need to apply your voucher code. So, click on “APPLY VOUCHER”.
                                            As soon as you click on “APPLY VOUCHER”, you will see that you have redeemed the discounted voucher code. Now it’s time to click on the checkbox to be agreed for terms and conditions and then finally click on “CONFIRM ORDER”.
                                            After confirmation, you will receive a confirmation email immediately from Pearson on your given email id. Now, it’s done.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-2-sub-2">
                                            <h4 class="panel-title">
                                                Is PTE test genuine?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-2-sub-2" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Yes, it is. Various individuals are taking benefit through this English language test and making their dream come true to study and work abroad.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-2-sub-3">
                                            <h4 class="panel-title">
                                                What is the actual fee for PTE in India?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-2-sub-3" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            The actual cost what you have to pay is Rs. 13,300 but you can avail the discounts through the voucher code in order to save on your test fee.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-2-sub-4">
                                            <h4 class="panel-title">
                                               Is this voucher valid across India?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-2-sub-4" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Yes, it is valid for all the centers in all over the India.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="faq-cat-3">
                            <div class="panel-group" id="accordion-cat-3">
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-3" href="#faq-cat-3-sub-1">
                                            <h4 class="panel-title">
                                                What is the refund policy in case of cancellation?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-3-sub-1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            PTE refunds 50% of the amount if you cancel before 7 days of your test date. Check more details <a href="{{url('/refund-policy')}}">here</a> .
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-3" href="#faq-cat-3-sub-2">
                                            <h4 class="panel-title">
                                                Is there any difference between PTE general and PTE exam?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-3-sub-2" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Well, only PTE Academic is valid for all the Visa applications and PTE General doesn’t work for visa applications.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-3-sub-3">
                                            <h4 class="panel-title">
                                                Can anyone use this voucher which is bought on my name?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-3-sub-3" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Yes, anyone can use it. It is kind of general voucher that anyone can buy and anyone can use.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-3-sub-4">
                                            <h4 class="panel-title">
                                                What if I gift this voucher to my friend or relative?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-3-sub-4" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Yes, you can gift your voucher to anyone to be redeemed.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="faq-cat-4">
                            <div class="panel-group" id="accordion-cat-4">
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-3" href="#faq-cat-4-sub-1">
                                            <h4 class="panel-title">
                                                What is the process and charge if I reschedule my PTE exam?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-4-sub-1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            You can directly visit the PTE website to reschedule your exam but before seven days of your previously scheduled exam date. The charges will be informed by PTE directly on the PTE website as there is no voucher provided for rescheduling your exam.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-3" href="#faq-cat-4-sub-2">
                                            <h4 class="panel-title">
                                                Can I change my exam center later after buying the PTE voucher code?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-4-sub-2" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Yes, you can easily change your exam center later as per your availability.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-4-sub-3">
                                            <h4 class="panel-title">
                                                After my PTE exam, when and where can I get my result?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-4-sub-3" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Your result will be declared within 5 official days. In case, if you don’t get your result in 5 days, you can call on the toll-free number. 0008004402020
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-4-sub-4">
                                            <h4 class="panel-title">
                                                What if I’m traveling to another country and want to exchange the voucher with that country and agreed to pay a difference amount?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-4-sub-4" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Well, vouchers are country specific so, you can only use the voucher in the same country where you bought from.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-4-sub-5">
                                            <h4 class="panel-title">
                                                What if I bought a PTE voucher and it will not work?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-4-sub-5" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            Well, it never happened previously and if it happens then, certainly there will be a technical issue and will be resolved with the team of PTE.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-faq">
                                    <div class="panel-heading">
                                        <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-4-sub-6">
                                            <h4 class="panel-title">
                                                Where to call for any assistance?
                                                <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div id="faq-cat-4-sub-6" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            You can call the customer care no. 0008004402020 during official days from Monday to Friday between 9 a.m. to 5 p.m. in India.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

{{--@section('extra')
    <div class="bf">
        <h5 class="fh">Save up to Rs. {{$saved_prize or ''}} on each exam booking</h5>
        <p>If you are a PTE Academic aspirant and preparing hard to clear the exam, PTEVoucherCode.com can help you in
            reducing the amount you are going to spend on it. At our website, PTE vouchers are available at considerably low
            prices (Lowest in India).</p>
        <h5 class="fh">Why Youth Prefers PTE Academic at Discounted Price?</h5>
        <p>Gone are the times when youngsters were satisfied with where they are. Now-a-day everyone seeks for flourishing
            career in UK or US based university or companies. Studying or working in other countries requires your ability
            to converse in their language and understand what they say. For this purpose, Pearson PTE exam had been
            designed. But the trouble is with the sky-high of Pearson PTE exam which acts as a barrier between you and your
            dreams. So, to give you chances of improvement PTE exam online has come up with us. Get you PTE exam voucher now
            to practice in the best possible way.</p>
        <h5 class="fh">PTE Academic exam and Benefits of Booking it with us</h5>
        <p>To test your command on the English language as a non-native English speaker Pearson comes up with PTE Academic
            exam. You are tested on various parameters like reading, speaking, writing and speaking. To check, if you can
            converse in English speaking countries in English or not, PTE online has designed an exam so that you can
            practice more to improve your score in Pearson PTE score. With us, you will get 11 free mock tests and reference
            material and saving of Rs.  on Booking PTE Exam India Wide.</p>
        <h5 class="fh">100% Purchase Assistance and Payment Security</h5>
        <p>We are available 24/7 interested students can book their test anytime anywhere. To avail the best of our
            services, you can reach our website and get the PTE exam voucher at the lowest price in India. Your improvement
            will motivate us to serve more. We are the final destination of PTE practice aspirants. PTE exam voucher will
            help you to save your money and practice more.</p>
        <h5 class="fh">Advantages of PTE Academic Exam </h5>
        <ul>
            <li>You can prove your command over the English language.</li>
            <li>It is acceptable worldwide.</li>
        </ul>
        <h5 class="fh">Why us?</h5>
        <p>We help you to save you Rs. {{$saved_prize or ''}} on Booking PTE Exam India </p>


        <h5 class="fh">How to use voucher code</h5>
        <p>Visit Pearson’s website and sign up. Schedule your test by filling all the details. At the time of payment, apply
            the PTE Academic Voucher Code that you received from us and you will need not pay anything but the price you’ve
            already paid for discounted voucher will be considered there.</p>
    </div>
@endsection--}}

{{--
@push('script')
<script>
    $('#pay-form').submit(function(ev) {
        if ($('#error-list li').length == 0) {
            $("#save").attr("disabled", true);
        }
    });
</script>
@endpush--}}
@push('script')
<script>
    $("#ptevouForm").submit(function(){0==$("#error-list li").length&&$("#loader").css("display","block")});
</script>
@endpush