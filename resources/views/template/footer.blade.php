<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-7">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="{{ url('/') }}"><img src="img/logo_t.png" alt=""></a>
                    </div>
                    <p>Lux Jewelry merupakan online shop yang bergerak dibidang penjualan aksesories.</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-5">
                <div class="footer__widget">
                    <h6>Quick links</h6>
                    <ul>
                        <li><a href="{{ url('/about') }}">About</a></li>
                        <li><a href="{{ url('/contact') }}">Contact</a></li>
                        <li><a href="{{ url('/about') }}">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Social Media</h6>
                    <ul>
                        <li><a href="https://wa.me/081572710708" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                        <li><a href="https://www.instagram.com/lux.jewelryy"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="footer__newslatter">
                    <h6>Payments</h6>
                    <div class="footer__payment">
                        <a href="#"><img src="{{ URL::asset('img/payment/bca.png') }}" width="60" alt=""></a>
                        <a href="#"><img src="{{ URL::asset('img/payment/bri.png') }}" width="40" alt=""></a>
                        <a href="#"><img src="{{ URL::asset('img/payment/mandiri.png') }}" width="80" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>