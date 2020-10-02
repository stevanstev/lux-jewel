<section class="banner set-bg" data-setbg="{{ URL::asset('img/banner/banner-1.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
                <div class="banner__slider owl-carousel">
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>Lux Jewel Collection</span>
                            <h1>The Pandora</h1>
                            @php
                                if(Auth::check()) {
                            @endphp
                                <a href="{{ url('/items') }}">Shop now</a>
                            @php
                                } else {
                            @endphp
                                <a href="{{ url('/login') }}">Shop now</a>
                            @php 
                                }
                            @endphp                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>