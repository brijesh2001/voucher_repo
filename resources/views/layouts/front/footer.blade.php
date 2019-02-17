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
                            <p class="email">E-mail: <span>info@compassoverseas.com</span></p>
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
