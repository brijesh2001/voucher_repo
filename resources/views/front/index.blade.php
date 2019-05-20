@extends('layouts.front.app')
@section('content')

    <!-- banner-->


    <section id="bannerContainer" class="section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-10">
                    <div class="contents text-center">
                        <h1 class="wow fadeInDown headline" data-wow-duration="1000ms" data-wow-delay="0.3s">Buy PTE
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
                        <form class="pay-form" method="post" id="ptevouForm" action="{{url('pte/payment-request')}}">
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
                                        <input type="text" placeholder="GST No If any" id="client_gstn" class="form-control"
                                               value="{{$client_gstn or ''}}" pattern="[a-zA-Z0-9-]+" maxlength="15" minlength="15" name="client_gstn">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="{{$user_id or ''}}">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <p id="hide_mobile" style="margin-bottom: 0px;margin-top: -7px">PTE Voucher Price : 10381 + 1869 (18%
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
                        <li>Create your ACCOUNT on <a href="https://www.pearsonpte.com/book" target="_blank">
                                pearsonpte.com/book</a></li>
                        <li>Login to pearsonpte.com using your Username and Password</li>
                        <li>Enter your preferred date, location and other details</li>
                        <li>Enter Voucher Code on the payment page.</li>
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
                    satisfaction with 24 x 7 Customer support.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="content-left text-right wow fadeInLeft animated" data-wow-offset="10">
                        <div class="box-item left">
                <span class="icon">
                  <i class="lnr lnr-rocket"></i>
                </span>
                            <div class="text">
                                <h4>24X7 Customer Care</h4>
                                <p>We are available to assist you on call & Whats App 24X7 on +91-9099-500-925</p>
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
                                <p>Featured at <a
                                            href="https://www.instamojo.com/blog/pte-voucher-code-making-pte-exams-cheaper-india/"
                                            target="_blank"> Instamojo.com </a> due to high success rate.</p>
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
                        <h2 class="wow zoomIn" data-wow-duration="1000ms" data-wow-delay="100ms">Buy PTE Academic
                            Voucher Code ₹ {{$rate or ''}} Get ₹ {{$saved_prize or ''}} Discount</h2>
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
                <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">PTEVoucherCode
                    is an e-commerce venture by Compass Overseas that has been serving the Education sector for more
                    than 4 years.</p>
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
                                <p>Excellent service from Compass Overseas and I have taken PTE <br>test voucher and
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