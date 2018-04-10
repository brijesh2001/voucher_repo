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
                <h1 style="text-align:center;font-weight:bold;color:#fff;">Buy PTE Voucher Online for
                    ₹ {{$rate or ''}} </h1>
            </div>


            <div class="txt-blink hidden-xs hidden-sm" style="float: left;min-width:560px;">

                <h2 class="blink">Get 11 Free Mock Test</h2>
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
                    {{ csrf_field() }}
                </form>


            </div>


        </div>
    </div>
    <!-- /banner-->

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