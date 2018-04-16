@extends('layouts.front.app')

@section('content')

    <!-- banner-->
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul id = 'error-list'>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="banner-agile" style="padding:0;">
        <div style="background: rgba(0,0,0,0.6);padding-top:30px;min-height: 632px;">
            <div class="moto">
                <h1 style="text-align:center;font-weight:bold;color:#fff;">Buy PTE Voucher Online at
                    ₹ {{$rate or ''}} </h1>
            </div>


            <div class="txt-blink hidden-xs hidden-sm" style="float: left;min-width:560px;">

                <h2 class="blink">11 Free Scored Mock Test</h2>
                <h3 class="blink">Free Reference Material</h3>
                <h3 class="blink">24 x 7 Support</h3>


            </div>
            <div class="txt-blink visible-xs visible-sm" style="width:100%;">

                <h2 class="blink" style="margin-left:0;">11 Free Scored Mock Test</h2>
                <h3 class="blink" style="margin-left:0;">Free Reference Material</h3>
                <h3 class="blink" style="margin-left:0;">24 x 7 Support</h3>
            </div>

            <div class="banner-text-agileinfo">
                <form class="pay-form" method="post" id="pay-form" action="{{url('pte/payment-request')}}">
                    <div class="frm-grp">
                        <select class="form-control2" name="state" id="state" style="margin-bottom: 1em;color:#fff;" required>
                            <option value="">State</option>
                            @if(count($state) > 0)
                                @foreach($state as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="frm-grp">
                        <input type="text" name="name" id="name" placeholder="Name"
                               class="form-control2" value="{{$name or ''}}" style="margin-bottom: 1em;color:#fff;" required/>
                    </div>
                    <div class="frm-grp">
                        <input type="text" name="mobile" id="mobile" placeholder="Mobile" maxlength="10" minlength="10"
                               class="form-control2" value="{{$mobile or ''}}" style="margin-bottom: 1em;color:#fff;" required/>
                   </div>
                    <div class="frm-grp">
                        <input type="email" name="email" id="email" placeholder="Email"
                               class="form-control2" value="{{$email or ''}}" style="margin-bottom: 1em;color:#fff;" required/>

                    </div>
                    <div class="frm-grp">
                        <select class="form-control2" name="number_of_voucher" id="number_of_voucher"
                                value="{{old('number_of_voucher')}}" style="margin-bottom: 1em;color:#fff;" required>
                                                <option value= "">QTY.No of Discounted PTE Voucher</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>

                    </div>
                    <div class="frm-grp"><input type="submit" name="save" id="save" value="Buy Now" class="btn"
                                                style="text-align:center;background-color:#0080aa;margin-bottom: 1em;color:#fff;front-size:1.2em;width:94%">
                    </div>
                    <div class="">
                        <h5 style="color:#fff;"><b>Note:</b> Code will be sent to your email immediately.</h5>
                    </div>
                    <input type="hidden" name="user_id" value="{{$user_id or ''}}">
                    {{ csrf_field() }}
                </form>


            </div>


        </div>
    </div>
    <!-- /banner-->
    <div class="services-w3" id="services">
        <div class="container"><h3>Services</h3><label class="line"></label>
            <p class="top-p">We Provide Following Services With PTE Exam Voucher.</p>
            <div class="col-md-4 services-grids-w3l"><img style="width:initial;height: 109px;" src={{url('css/front/images/ci1.jpg')}}><h4>Free 11 Scored Mock
                    Test</h4>
                <p>Get 11 free complete Scored Mock Test along with the Voucher</p></div>



            <div class="col-md-4 services-grids-w3l"><img style="width:initial;height: 109px;" src={{url('css/front/images/ci2.jpg')}}><h4>Free Reference
                    Material</h4>

                <p>Important Reference Material for the Practice Purpose </p></div>
            <div class="col-md-4 services-grids-w3l"><img style="width:initial;height: 109px;" src={{url('css/front/images/ci3.jpg')}}><h4>Save</h4>

                <p>Save Rs.{{$saved_prize}} on Booking PTE Exam India Wide</p></div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="services-w3 even" id="services">
        <div class="container"><h3>How To Book PTE Exam Date ?</h3><label class="line"></label>
            <div class="col-md-3">
                <div style="width:90%;float:left;" class="services-grids-w3l">
                    <div style="min-height:121px;"><img style="height:90px;width:101px;" src={{url('css/front/images/service1.png')}}></div>


                    <h4>Create Account</h4>
                    <p>Visit <a target="_new" href="http://pearsonpte.com/book/" rel="nofollow">pearsonpte.com/book</a>
                        and
                        create account.</p></div>
                <div style="width:10%;float:right;padding-top:20px;font-size:38px;"><span
                            class="glyphicon glyphicon-chevron-right visible-md visible-lg"
                            aria-hidden="true"></span><span
                            class="glyphicon glyphicon-chevron-down visible-sm visible-xs" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div style="width:90%;float:left;" class="services-grids-w3l">
                    <div style="min-height:121px;"><img style="height:110px;width:95px;" src={{url('css/front/images/service2.png')}}></div>

                    <h4>Date &amp; Location</h4>
                    <p>Book your preferred Exam date and Location.</p></div>
                <div style="width:10%;float:right;padding-top:20px;font-size:38px;"><span
                            class="glyphicon glyphicon-chevron-right visible-md visible-lg"
                            aria-hidden="true"></span><span
                            class="glyphicon glyphicon-chevron-down visible-sm visible-xs" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div style="width:90%;float:left;" class="services-grids-w3l">
                    <div style="min-height:121px;"><img style="height:91px;width:110px;" src={{url('css/front/images/service3.png')}}></div>

                    <h4>Enter Voucher Code</h4>
                    <p>You need to enter valid PTE exam voucher code in payment section.</p></div>
                <div style="width:10%;float:right;padding-top:20px;font-size:38px;"><span
                            class="glyphicon glyphicon-chevron-right visible-md visible-lg"
                            aria-hidden="true"></span><span
                            class="glyphicon glyphicon-chevron-down visible-sm visible-xs" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div style="width:90%;float:left;" class="services-grids-w3l">


                    <div style="min-height:121px;"><img style="height:97px;width:121px;" src={{url('css/front/images/service4.png')}}></div>
                    <h4>Confirmation Email</h4>
                    <p>Receive a confirmation email from PTE about your test booking.</p></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="services-w3" id="services">
        <div class="container"><h3>Why Us ?</h3><label class="line"></label>
            <div class="col-md-3 services-grids-w3l">

                <div style="height:115px;"><img style="height:105px;width:81px;" src={{url('css/front/images/why1.png')}}></div>
                <h4>24X7 Customer Care</h4>
                <p>We are available to assist you on call &amp; Whats App 24X7 on +91-9099-500-925</p></div>
            <div class="col-md-3 services-grids-w3l">


                <div style="height:115px;"><img style="height:79px;width:126px;" src={{url('css/front/images/why2.png')}}></div>
                <h4>Secure Payment</h4>
                <p>To make your payment more secure we have made this website SSL SECURED.</p></div>
            <div class="col-md-3 services-grids-w3l">


                <div style="height:115px;"><img style="height:109px;width:117px;" src={{url('css/front/images/why3.png')}}></div>
                <h4>Lowest Price
                    <p>PTE Academic Cost Rs. 13,101 in India. Purchase Voucher Code from us &amp; Save
                        Rs. .</p></h4>
            </div>
            <div class="col-md-3 services-grids-w3l">

                <div style="height:115px;"><img style="height:109px;width:129px;" src={{url('css/front/images/why4.png')}}></div>

                <h4>25000+ No. of Students</h4>
                <p>Thousands of Student trust on us. We also help to book your exam.</p></div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="about-agileits" id="about" style="padding-bottom:0;">
        <div class="container"><!--h3>About us</h3><label class="line"></label><p class="top-p"></p--></div>
        <div class="col-md-6 about-left"></div>
        <div class="col-md-6 about-right"><h4>Buy PTE Academic Voucher Code ₹ {{$rate or ''}} Get
                ₹ {{$saved_prize or ''}} Discount</h4>

            <p>
                For Your Successful Academic and Career Endeavors, We Partner You at The Least Costs!<br>
                We provide you a reliable and pocket-friendly option to take one of the best English proficiency Pearson
                Test i.e. PTE Exam. We Provide Complete Assistance, Guidance, And Numerous Handbooks and Supporting
                Material
                to Help You Clear the Exam in The First Attempt Itself. <br><br>We Are Known for Giving Our Best and
                Unmatched Services to Students Who Dream High. With Us, You Will Get the Best Value for Your Money and
                That
                Too at Reasonable Price as We Are Authorized Sellers of PTE Exam Vouchers. <br><br>On Booking Your PTE
                Exam
                Through Us, You Will Be Getting the Highest Discount on Your Application Fee in India. For Further
                Countenance, We Are Providing 11 Scored Mock Test Papers And Experts Counseling for Beginners. If You Are
                Looking
                for An Opportunity to Study in Foreign, Then Look Further Because PteVoucherCode Is the Best Destination
                to
                Make Your Offshore Study Dreams Come True.</p>


        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clients" style="padding :0;">
        <div style="background-color:rgba(0,0,0,0.4);padding:50px 0 50px 0;">
            <div class="container"><h3>Testimonials</h3><label class="line"></label><h4>What Our Clients Say</h4>
                <section class="slider">
                    <div class="flexslider">
                        <ul class="slides">
                            <li><img src={{url('css/front/images/c1.png')}}>
                                <p>Excellent service from Compass Overseas and I have taken PTE test voucher and
                                    immediately
                                    they have mailed me free PTE preparation material.</p>
                                <div class="client"><h5>Jyotika</h5></div>
                            </li>
                            <li><img src={{url('css/front/images/c2.png')}}>
                                <p>Thanks Compass Overseas for the great help.The executive staff is really good and
                                    proactive at helping the students to purchase the PTE exam vouchers".</p>
                                <div class="client"><h5>PAANI VERMA</h5></div>
                            </li>
                            <li><img src={{url('css/front/images/c3.png')}}>
                                <p>Many many thanks to Compass Overseas to help me a lot by providing a large no. of
                                    tests
                                    that were very beneficial for me.</p>
                                <div class="client"><h5>RAVI CHAND GADE</h5></div>
                            </li>
                            <li><img src={{url('css/front/images/c4.png')}}>
                                <p>Nice deal,got good discount on PTE exam voucher + also got 5 test for practice +
                                    awesome
                                    customer service.</p>
                                <div class="client"><h5>SYED IMAD</h5></div>
                            </li>
                            <li><img src={{url('css/front/images/c5.png')}}>
                                <p>Thanks to CompassOverseas for giving me such a wonderful opportunity to purchase at
                                    very
                                    lower rates , and indeed the test papers are really good and helpful.</p>
                                <div class="client"><h5>THATTALA RAJYALAKSHMI</h5></div>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="services-w3" id="services" style="padding-bottom: 10px;">

        <div class="container"><h3>PTE FAQs</h3>
            <label class="line"></label>
        </div>

    </div>

    <div class=container>
        {{--<div class=row>
            <div class=col-md-6>
                <div id=accordion2>
                    <h3 style=color:#fff style=color:#fff>How and where to buy the PTE Exam Voucher?</h3>
                    <div>
                        <p style=text-align:justify>Go to the website: <a href="https://www.ptevouchercode.com/">https://www.ptevouchercode.com/</a>
                            in order to avail the voucher code on discounted rates. All you need to fill your required
                            details along with payment on this website to grab the PTE academic voucher.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>Why should I buy Voucher from PTEVoucherCode?</h3>
                    <div>
                        <p style=text-align:justify><a href="https://www.ptevouchercode.com/">PTEVoucherCode</a> offers
                            highest discounts and easy way to schedule PTE Academic Exam. To avoid hassles, you should
                            buy
                            from the best PTE Exam Voucher website i.e. PTEVoucherCode.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What will be the validity of voucher to redeem on PTE
                        website?</h3>
                    <div>
                        <p style=text-align:justify>You can redeem it anytime till 11 months from the date of purchase
                            at
                            any center across India.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>Do PTE academic work for PR (Migration) and Student Visa? Is
                        PTE
                        Exam universally accepted or not?</h3>
                    <div>
                        <p style=text-align:justify>
                        <ul>
                            <li>For education purpose, all countries accept PTE.</li>
                            <li>For Migration and PR, only Australia and NZ accept the PTE Academic.</li>
                            <li>For more details, you can check on this link: <a
                                        href="http://pearsonpte.com/test-takers/accepts/" rel="nofollow">http://pearsonpte.com/test-takers/accepts/</a>
                            </li>
                        </ul>
                        </p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>How to apply the purchased voucher on to the Pearson
                        website?</h3>
                    <div>
                        <p style=text-align:justify>Very first, you need to go on <a href="http://www.pearsonpte.com"
                                                                                     rel="nofollow">www.pearsonpte.com</a>.
                            Then, you need to click on the “BOOK NOW” and you will find the “SIGN IN” button so just
                            click
                            on that.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>Is PTE test genuine?</h3>
                    <div>
                        <p style=text-align:justify>Yes, it is. Various individuals are taking benefit through this
                            English
                            language test and making their dream come true to study and work abroad.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What is the actual fee for PTE in India?</h3>
                    <div>
                        <p style=text-align:justify>The actual cost what you have to pay is Rs. 12,101 but you can avail
                            the
                            discounts through the PTE test voucher code in order to save on your test fee.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff> Is this voucher valid across India?</h3>
                    <div>
                        <p style=text-align:justify>Yes, it is valid for all the centers in all over the India.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What is the refund policy in case of cancellation?</h3>
                    <div>
                        <p style=text-align:justify>PTE refunds 50% of the amount if you cancel before 7 days of your
                            test
                            date. Check more details <a href={{url('refund-policy')}}>here</a>.</p>
                    </div>


                </div>
            </div>
            <div class=col-md-6>
                <div id=accordion>
                    <h3 style=color:#fff style=color:#fff>Is there any difference between PTE general and PTE exam?</h3>
                    <div>
                        <p style=text-align:justify>Well, only PTE Academic is valid for all the Visa applications and
                            PTE
                            General doesn’t work for visa applications.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff> Can anyone use this voucher which is bought on my name?</h3>
                    <div>
                        <p style=text-align:justify>Yes, anyone can use it. It is kind of general voucher that anyone
                            can
                            buy and anyone can use.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What if I gift this voucher to my friend or relative?</h3>
                    <div>
                        <p style=text-align:justify>Yes, you can gift your voucher to anyone to be redeemed.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What is the process and charge if I reschedule my PTE
                        exam?</h3>
                    <div>
                        <p style=text-align:justify>You can directly visit the PTE website to reschedule your exam but
                            before seven days of your previously scheduled exam date. You will be charged 25% of the
                            actual
                            cost of PTE directly on the PTE website as there is no voucher provided for rescheduling
                            your
                            exam.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff> Can I change my exam center later after buying the voucher
                        code?</h3>
                    <div>
                        <p style=text-align:justify>Yes, you can easily change your exam center later as per your
                            availability.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>After my PTE exam, when and where can I get my result?</h3>
                    <div>
                        <p style=text-align:justify>Your result will be declared within 5 official days. In case, if you
                            don’t get your result in 5 days, you can call on the toll-free number. 0008004402020.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What if I’m traveling to another country and want to exchange
                        the
                        voucher with that country and agreed to pay a difference amount?</h3>
                    <div>
                        <p style=text-align:justify>Well, vouchers are country specific so, you can only use the voucher
                            in
                            the same country where you bought from.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>What if I bought a voucher and it will not work?</h3>
                    <div>
                        <p style=text-align:justify>Well, it never happened previously and if it happens then, certainly
                            there will be a technical issue and will be resolved with the team of PTE.</p>
                    </div>

                    <h3 style=color:#fff style=color:#fff>Where to call for any assistance?</h3>
                    <div>
                        <p style=text-align:justify>You can call the customer care no. 0008004402020 during official
                            days
                            from Monday to Friday between 9 a.m. to 5 p.m. in India.</p>
                    </div>
                </div>
            </div>

        </div>--}}
    </div>

@endsection

@section('extra')
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
@endsection

@push('script')
<script>
    $('#pay-form').submit(function(ev) {
        if ($('#error-list li').length == 0) {
            $("#save").attr("disabled", true);
        }
    });
</script>
@endpush