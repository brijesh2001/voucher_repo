@extends('layouts.front.app')

@section('content')

    <div class="about-banner-section">
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14683.964412797408!2d72.65193958675643!3d23.06078775199788!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e8719642aefa3%3A0x71e55c2c905d16b8!2sPTE+Voucher+Code!5e0!3m2!1sen!2sin!4v1508244761935"
                    width="100%" height="700" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>

    </div>

    {{--<div class="banner-text-agileinfo hidden-xs" style="position:absolute;top:250px;">

        <form action="{{url('send-query')}}" id = "ptevouForm" method="POST">



            <div class="form-group"><input style="margin-bottom: 1em;color:#fff;" type="text" name="Name"
                                            placeholder="Name" required="" class="form-control"></div>
            <div class="form-group"><input style="margin-bottom: 1em;color:#fff;" type="text" name="Mobile"
                                            placeholder="Mobile" required="" class="form-control"></div>
            <div class="form-group"><input style="margin-bottom: 1em;color:#fff;" type="email" name="Email"
                                            placeholder="Email" required="" class="form-control"></div>
            <div class="form-group"><textarea class="form-control" name="Message" placeholder="Message"
                                               style="margin-bottom: 1em;color:#fff;"></textarea></div>
                <input type="hidden" name="type" value="send_query">
            <div class="form-group"><input type="submit" value="Send" class="btn"
                                            style="text-align:center;background-color:#df4914;margin-bottom: 1em;color:#fff;front-size:1.2em;width:94%"
                                            type="submit"></div>

            {{ csrf_field() }}
        </form>

    </div>--}}


    <div class="clearfix"></div>

@endsection
