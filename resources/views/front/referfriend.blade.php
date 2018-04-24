@extends('layouts.front.app')

@section('content')
    <div class="about-banner-section">
        <div class="refer-friend" style="width: 100%;height: 700px">

        </div>

    </div>

    <div class="banner-text-agileinfo hidden-xs" style="position:absolute;top:250px;left: -70px;">

        <form action="{{url('/refer/store')}}" method="POST">

            <h2 style="text-align: center">Refer Friend</h2>
            <div class="frm-grp">
                <div class="frm-grp"><input style="margin-bottom: 1em;color:#fff;" type="email" name="email"
                                            placeholder="Email" required="" class="form-control2"></div>
                <div class="frm-grp"><input type="submit" value="Refer" class="btn"
                                            style="text-align:center;background-color:#df4914;margin-bottom: 1em;color:#fff;front-size:1.2em;width:94%"
                                            type="submit"></div>
            </div>
            {{ csrf_field() }}
        </form>

    </div>


    <div class="banner-text-agileinfo visible-xs">

        <form action="{{url('send-query')}}" method="POST">


            <div class="frm-grp">
                <div class="frm-grp"><input style="margin-bottom: 1em;color:#fff;" type="email" name="email"
                                            placeholder="Email" required="" class="form-control2"></div>
                <div class="frm-grp"><input type="submit" value="Refer" class="btn"
                                            style="text-align:center;background-color:#df4914;margin-bottom: 1em;color:#fff;front-size:1.2em;width:94%"
                                            type="submit"></div>
            </div>
            {{ csrf_field() }}
        </form>

    </div>


    <div class="clearfix"></div>
    <div style="background-color:#df4914;padding:5px;">
        <div class="container">
            <div class="" style="float:right;padding-top:25px;padding-left:10px;color:#fff;"><h4>Our Desk executive will
                    direct contact ypur friend in one working day</h4></div>
            <div class="" style="float:right;">
                <img style="width:45px;" src={{url('css/front/images/letecoller.png')}}>
            </div>

        </div>
    </div>
@endsection
