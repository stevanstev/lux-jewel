@extends('template/container', ['show' => true])

@section('title')
    Detail Product
@endsection

@section('section')
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__left product__thumb nice-scroll">
                            <a class="pt active" href="#product-1">
                                <img src="{{ url('img/product') }}/{{ str_replace(' ', '%20', $products->foto) }}" alt="">
                            </a>
                        </div>
                        <div class="product__details__slider__content">
                            <div class="product__details__pic__slider owl-carousel">
                                <img data-hash="product-1" class="product__big__img" src="{{ url('img/product') }}/{{ str_replace(' ', '%20', $products->foto) }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <h3>{{ $products->nama_produk }}</h3>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span>( {{ rand(0, 1000) }} reviews )</span>
                        </div>
                        <div class="product__details__price">Rp {{ number_format($products->harga_produk, 2) }}</div>
                        @php 
                            if($products->stok != 0){
                        @endphp
                        <div class="product__details__button">
                            <a class="cart-btn" href="{{ url('/login') }}"><span class="icon_bag_alt"></span> Add to Cart</button>
                        </div>
                        @php
                            } 
                        @endphp
                        <div class="product__details__widget">
                            <ul>
                                <li>
                                    <span>Stock:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin">
                                            @php 
                                                if($products->stok != 0) {
                                            @endphp 
                                                Stok Ada ({{ $products->stok }})
                                                <input type="checkbox"  id="stockin" checked>
                                                <span class="checkmark"></span>
                                            @php 
                                                } else {
                                            @endphp
                                                Stok Habis, <a href="{{ url('/login') }}">Kembali</a>
                                            @php 
                                                }
                                            @endphp
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Available color:</span>
                                    <div class="color__checkbox">
                                        <label for="red">
                                            <input type="radio" name="color__radio" id="{{ $products->color }}" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label for="black"></label>
                                        <label for="grey"></label>
                                    </div>
                                </li>
                                <li>
                                    <span>Berat:</span>
                                    <div class="size__btn">
                                        <label for="xs-btn" class="active">
                                            {{ $products->berat_produk }} G
                                        </label>
                                        <label for="s-btn">
                                            
                                        </label>
                                        <label for="m-btn">
                                           
                                        </label>
                                        <label for="l-btn">
                                            
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Description</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h6>Description</h6>
                                <p> {{ $products->deskripsi }} <p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="related__title">
                        <h5>RELATED PRODUCTS</h5>
                    </div>
                </div>
                @foreach($related as $r)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="{{ url('/img/product') }}/{{ str_replace(' ', '%20', $r->foto) }}">
                                <ul class="product__hover">
                                    <li><a href="{{ url('/img/product') }}/{{ str_replace(' ', '%20', $r->foto) }}" class="image-popup"><span class="arrow_expand"></span></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6><a href="#">{{ ucfirst($r->nama_produk) }}</a></h6>
                                <div class="product__price">Rp. {{ number_format($r->harga_produk, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection