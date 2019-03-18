<section id="contact" class="section" data-stellar-background-ratio="-0.2">
    <div class="contact-form">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-xs-12">
                    <div class="contact-us">
                        <h3>Contact <span>With us</span></h3>
                        <div class="contact-address">
                            <p><b>PTE Voucher Code</b><br>
                                415 - Vishala Supreme, <br>Opp. Torrent Power<br>S.P Ring Road - New Nikol <br>Ahmedabad, Gujarat 382350 </p>
                            <p class="phone">Phone: <span>+91-9099-500-925</span></p>
                            <p class="email">E-mail: <span>help@ptevouchercode.com</span></p>
                        </div>
                        <div class="social-icons">
                            <ul>
                                <li class="facebook"><a href="https://www.facebook.com/PTEVoucherCode" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li class="twitter"><a href="https://twitter.com/PteVoucherCode" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li class="google-plus"><a href="https://plus.google.com/107058175992570744375?hl=en" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-xs-12">
                    <div class="contact-block">
                            <form action="{{url('send-query')}}" id = "contactForm" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required data-error="Please enter your name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Your Email" id="email" class="form-control" name="email" required data-error="Please enter your email">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="message" name="message" placeholder="Your Message" rows="8" data-error="Write your message" required></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="submit-button text-center">
                                        <button class="btn btn-common" id="submit" type="submit">Send Message</button>
                                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                                <input type="hidden" name="type" value="send_query">
                                {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<!-- Footer Section Start -->
<footer>
    <div class="container">
        <div class="row">
            <!-- Footer Links -->
            <div class="col-lg-4 col-sm-4 col-xs-12">
                <ul class="footer-links">
                    <li>
                        <a href="{{url('/')}}">Homepage</a>
                    </li>
                    <li>
                        <a href="{{url('/about-us')}}">About Us</a>
                    </li>
                    <li>
                        <a href="{{url('/contact-us')}}">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-8 col-sm-8 col-xs-12">
                <div class="copyright">
                    <p>© 2018 ptevouchercode.com All Rights Reserved<a rel="nofollow" href="#"></a> || <a href="{{url('terms')}}"style=color:#fff>Terms & Condition</a> || <a href="{{url('privacy')}}"style=color:#fff>Privacy Policy</a></p>
                    <p><center>
                        {{--<div itemtype="http://data-vocabulary.org/Review" itemscope=""> <span itemtype="http://data-vocabulary.org/Organization" itemscope="" itemprop="itemreviewed">&nbsp;<a href="https://www.google.co.in/maps/dir/23.0465536,72.6409216/pte+voucher+code/@23.0450431,72.6428143,14z/data=!3m1!4b1!4m9!4m8!1m1!4e1!1m5!1m1!1s0x395e8719642aefa3:0x71e55c2c905d16b8!2m2!1d72.6802062!2d23.046148">Authorised PTE Voucher reseller</a><span>&nbsp;</span></span> <span style="color: #E7711B !important;"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span> <span itemprop="reviewer" style="color:#fff;">Reviewed by 56 Users</span> <span itemtype="http://data-vocabulary.org/Rating" itemscope="" itemprop="rating" style="color:#fff;"> <span itemprop="value">4.8</span>
/ <span itemprop="best">5</span>
</span></div>--}}
                            <div style="color:#fff;" itemscope="" itemtype="http://schema.org/Product"><span style="float:left;" itemprop="name">PTE Voucher Code </span><div style="float:left; font-weight:bold;" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">&nbsp;rated <span itemprop="ratingValue">4.8</span>/5 based on <span itemprop="reviewCount">53</span> user reviews</div>
                            </div>
                        </center></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->

<!-- Go To Top Link -->
<a href="#" class="back-to-top">
    <i class="lnr lnr-arrow-up"></i>
</a>

<div id="loader">
    <div class="spinner">
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
</div>
