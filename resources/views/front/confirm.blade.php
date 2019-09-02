@extends('layouts.front.app')

@section('content')


        <!--<div class="row">
            <div class="col-md-12">
                <div class="page-header">

                </div>
            </div>
        </div>-->
        <section id="refundbannerContainer" class="section">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-10">
                        <div class="contents text-center">
                            <h1 class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="0.3s">Confirm Details</h1>
                            <!-- <h2 class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="0.3s">11 Free Scored Mock Test</h2>
                            <h2 class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="0.3s">Free Reference Material</h2>
                           <h3 class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="0.3s">24 x 7 Support</h3> -->
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <div id = "about-us-section" style="margin: 50px 0 30px 0;">
            <div class="container">
                <div class="confirm_icon">
                    <h3 class="lmtdoffertext_confr">Please confirm the Details</h3>
                </div>

                <form action="{{url('pte/pay')}}" name="razorpayform" id="razorpayform"
                      style="border-radius: 0px;" method="post"
                      class="form-horizontal group-border-dashed pte-frm">

                    <div class="col-md-12 col-xs-12">
                        <p class="nmlable">Name<span class="error">*</span></p>
                        <input type="text" name="name" id="name" placeholder="Name"
                               class="form-control" value="{{$requestData['name'] or ''}}" readonly required/>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <p class="nmlable">Email<span class="error">*</span></p>
                        <input type="email" name="email" id="email" placeholder="Email"
                               class="form-control" value="{{$requestData['email'] or ''}}" readonly required/>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <p class="nmlable">Mobile<span class="error">*</span></p>
                        <input type="text" name="mobile" id="mobile" placeholder="Mobile"  maxlength="10"
                               minlength="10"
                               class="form-control" value="{{$requestData['mobile'] or ''}}" readonly required/>
                    </div>

                    <div class="col-md-12 col-xs-12">
                        <p class="nmlable">Number Of Voucher<span class="error">*</span></p>
                        <select class="form-control" name="number_of_voucher" id="number_of_voucher"
                                value="{{old('number_of_voucher')}}" required disabled>
                            <option value="">QTY.No of Discounted Voucher</option>
                            <option value="1" @if('1' == $requestData['number_of_voucher']){{"selected"}}@endif>1
                            </option>
                            <option value="2" @if('2' == $requestData['number_of_voucher']){{"selected"}}@endif>2
                            </option>
                            <option value="3" @if('3' == $requestData['number_of_voucher']){{"selected"}}@endif>3
                            </option>
                            <option value="4" @if('4' == $requestData['number_of_voucher']){{"selected"}}@endif>4
                            </option>
                            <option value="5" @if('5' == $requestData['number_of_voucher']){{"selected"}}@endif>5
                            </option>
                        </select>
                    </div>
                    <input type="hidden" name="user_id" value="{{$requestData['user_id'] or ''}}">

                    {{ csrf_field() }}
                    <div class="col-md-12 flex_center">
                        <button id="rzp-button1" class="btn btn-success pybtnnew" style="border: 1px solid #FFF; background-color: #8dbd35;padding: 8px 40px;float: left">
                            PAY NOW
                        </button>


                        <div class="back_button" style="padding-left: 20px;float: left">
                            <a class="btn btn-info" href="{!! URL::previous() !!}">GO BACK</a>
                        </div>

                    </div>

                    <div class="col-md-12 flex_center backbtn_mrgup">


                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
                        <input type="hidden" name="enquiry_id"  value="{{$enquiry_id}}">

                        <p style="color:red;padding-top:10px;padding-bottom: 10px;">Note: Please verify the email
                            and number before doing the payment</p>
                    </div>
                </form>

            </div>
        </div>


@endsection
@push('script')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    /*$('#app_form').submit(function (ev) {
     if ($('#error-list li').length == 0) {
     $("#save").attr("disabled", true);
     }
     });*/

    // For Razorpay
    // Checkout details as a json
    <?php $data = json_encode($data) ?>
    var options = <?php echo $data?>;
    /**
     * The entire list of Checkout fields is available at
     * https://docs.razorpay.com/docs/checkout-form#checkout-fields
     */
    options.handler = function (response){
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.razorpayform.submit();
    };
    // Boolean whether to show image inside a white frame. (default: true)
    options.theme.image_padding = false;
    options.modal = {
        ondismiss: function() {
            console.log("This code runs when the popup is closed");
        },
        // Boolean indicating whether pressing escape key
        // should close the checkout form. (default: true)
        escape: true,
        // Boolean indicating whether clicking translucent blank
        // space outside checkout form should close the form. (default: false)
        backdropclose: false
    };
    var rzp = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){
        rzp.open();
        e.preventDefault();
    }
</script>
@endpush