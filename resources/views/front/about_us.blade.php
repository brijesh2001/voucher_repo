@extends('layouts.front.app')

@section('content')

    {{--<div class="hidden-xs hidden-sm skills-w3ls" style="padding:0;">
        <div style="background-color:rgba(0,0,0,0.3);padding: 50px 0;">
            <div class="container">
                <h1 style="color:#fff;">About Us</h1>
            </div>
        </div>

    </div>--}}


    {{--<div class="hidden-md hidden-lg skills-w3ls"
         style="padding:0;background: url(../image/a2.jpg) no-repeat 0px 0;background-size:cover;">
        <div style="background-color:rgba(0,0,0,0.3);padding: 50px 0;">
            <div class="container">
                <h1 style="color:#fff;">About Us</h1>
            </div>
        </div>

    </div>--}}

    <section id="refundbannerContainer" class="section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-10">
                    <div class="contents text-center">
                        <h1 class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="0.3s">About us</h1>
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
            <h3>PTEVoucherCode is an e-commerce venture by Compass Overseas that has been serving the Education sector
                for more than 4 years.</h3>
            <label class="line"></label>
            <p style="text-align: justify;width:90%;margin:auto;">Since the inauguration of <a
                        href="http://compassoverseas.com" target="_new">Compass Overseas</a> on December 15, 2013, the
                company has continually worked for students and professional by helping, assisting, guiding and letting
                them achieve their goals of staying or studying in foreign Countries. Compass Migration and Education
                Consultant is acting as a ladder of success for thousands of students who are thriving towards their
                goals of better educational facilities and thus, the better career. Candidates of PTE Academic are given
                special discounts and guidance for study material so that they can stay in developed countries like USA,
                UK, Canada and New Zealand for success Endeavor of gaining skills, knowledge and a better life!
            </p>
        </div>
    </div>

    <div class="even" style="padding: 10px;">
        <div class="container">
            <h3>Our Achievements</h3>
            <label class="line"></label>
                <div class="row">
                        <div class="col-md-3 .services-grids-w3l p">
                            <img style="max-height:200px;width:initial;border-radius: 180px;margin-left:30px;" src={{url('css/front/images/ab1.jpg')}}>
                            <p style="text-align:center;">More than 25,000 students have yet used our services.</p>
                        </div>
                        <div class="col-md-3 .services-grids-w3l p">
                            <img style="max-height:200px;width:initial;border-radius: 180px;margin-left:30px;" src={{url('css/front/images/int.jpg')}}>

                            <p style="text-align:center;">Featured at <a target="_blank"
                                                                         href='https://www.instamojo.com/blog/pte-voucher-code-making-pte-exams-cheaper-india/'>Instamojo.com</a>
                                due to high success rate.</p>
                        </div>
                        <div class="col-md-3 .services-grids-w3l p">

                            <img style="max-height:200px;width:initial;border-radius: 180px;margin-left:30px;" src={{url('css/front/images/abt2.jpg')}}>
                            <p style="text-align:center;">100% Customer Satisfaction.</p>
                        </div>
                        <div class="col-md-3 services-grids-w3l p">

                        <img style="height:200px;width:200px;border-radius: 180px;margin-left:30px;" src={{url('css/front/images/ab3.jpg')}}>
                        <p style="text-align:center;">Lowest Prices in India.</p>
                    </div>
                </div>
    </div>

    </div>

@endsection
