@extends('layouts.front.app')

@section('content')

    <section id="refundbannerContainer" class="section">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-10">
                    <div class="contents text-center">
                        <h1 class="wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="0.3s">About us</h1>

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
                        href="http://compassoverseas.com" target="_blank">Compass Overseas</a>.The
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
                                                                         href='https://www.instamojo.com/blog/pte-voucher-code-making-pte-exams-cheaper-india/'rel="nofollow">Instamojo.com</a>
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
