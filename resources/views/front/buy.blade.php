@extends('layouts.front.app')

@section('content')

    <!-- banner-->
    <section id="bannerContainer" class="section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-10">
                    <div class="contents text-center">
                        <h1 class="wow fadeInDown headline" data-wow-duration="1000ms" data-wow-delay="0.3s">Buy PTE Voucher Online At ₹ {{$rate or ''}} & Get Free</h1>
                        <h4 class="wow fadeInDown top-class" data-wow-duration="1000ms" data-wow-delay="0.3s">15 scored Mock Test</h4>
                        <h4 class="wow fadeInDown top-class" data-wow-duration="1000ms" data-wow-delay="0.3s">Get the best real time platform with updated question banks</h4>
                    </div>
                </div>
                <div class="col-md-10 pteForm">
                    <div class="contents1 text-center">
                        <div class="help-block with-errors">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul id = 'error-list'>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="flash-message">
                                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                    @if(Session::has('alert-' . $msg))
                                        <p class="alert alert-{{ $msg }}" style="text-align:center;">{{ Session::get('alert-' . $msg) }} <a href="#"
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
                                        <input type="text" class="form-control" id="name" name="name" value="{{$name or ''}}" placeholder="Name" required data-error="Please enter your name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{$mobile or ''}}" placeholder="Mobile No." maxlength="10" minlength="10" required data-error="Please enter your mobile no.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="email" placeholder="Email" id="email" class="form-control" value="{{$email or ''}}" name="email" required data-error="Please enter your email">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="number_of_voucher" id="number_of_voucher"
                                                value="{{old('number_of_voucher')}}" required>
                                            <option value= "">QTY.No of Discounted PTE Voucher</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="{{$user_id or ''}}">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <p style="margin-bottom: 0px;margin-top: -7px">PTE Voucher Price : 10381 + 1869 (18%
                                        GST) = {{$rate or ''}} INR</p>
                                    <div class="submit-button text-center">
                                        <button class="btn btn-common1" id="submit" type="submit" style="cursor: pointer">Buy Now</button>
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
    $('#ptevouForm').submit(function() {
        if ($('#error-list li').length == 0) {
            $('#loader').css('display','block');
        }
    });
</script>
@endpush