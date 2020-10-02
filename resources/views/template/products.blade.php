<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="section-title">
                    <h4>Our product</h4>
                </div>
            </div>
        </div>
        <div class="row property__gallery">
            @foreach($products as $p) 
                <div class="col-lg-3 col-md-4 col-sm-6 mix">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ url('img/product') }}/{{ $p->foto }}">
                            <ul class="product__hover">
                                <li><a href="{{ url('img/product') }}/{{ $p->foto }}" class="image-popup"><span class="arrow_expand"></span></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <a href="{{ url('/product-details') }}/{{ $p->id }}" class="btn btn-success"/>Beli Produk</a>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <br/>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" align="center">
            {{ $products->render() }}
        </div>
        <div class="col-md-4"></div>
    </div>
</section>