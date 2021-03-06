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
                        
                        <div class="product__details__price">Rp {{ number_format($products->harga_produk, 2) }}</div>
                        @php 
                            if($products->total_stok != 0){
                        @endphp
                        <div class="product__details__button">
                            <div class="quantity">
                                <span>Quantity:</span>
                                <div class="pro-qty">
                                    <form method="post" action="{{ url('/item-to-cart') }}">
                                        <input type="text" hidden value="{{ csrf_token() }}" name="_token"/>
                                        <input type="text" hidden value="{{ $products->id }}" name="id"/>
                                        <input type="text" hidden value="{{ $products->total_stok }}" name="total_stok"/>
                                        <input type="text" hidden value="{{ $products->nama_produk }}" name="nama_produk"/>
                                        <input type="text" hidden value="{{ $products->berat_produk }}" name="berat_produk"/>
                                        <input type="text" hidden value="{{ $products->harga_produk }}" name="harga_produk"/>
                                        <input type="text" hidden value="{{ $products->deskripsi }}" name="deskripsi"/>
                                        <input type="text" hidden value="{{ $products->kategori }}" name="kategori"/>
                                        <input type="text" hidden value="{{ $products->foto }}" name="foto"/>
                                        <input type="text" hidden value="{{ $products->bahan }}" name="bahan"/>
                                        <input type="text" hidden value="{{ $products->color }}" name="color"/>
                                        <input class="form-control" placeholder="jumlah" value="1" type="number" name="jumlah{{ $products->id }}" />
                                        <br/>
                                        <p style="color:red;">{{ $errors->first('jumlah'.$products->id) }}</p>
                                        <br/>
                                </div>
                            </div>
                                <button class="cart-btn"><span class="icon_bag_alt"></span> Add to cart</button>
                            </form>
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
                                                if($products->total_stok != 0) {
                                            @endphp 
                                                Stok Ada ({{ $products->total_stok }})
                                                <input type="checkbox"  id="stockin" checked>
                                                <span class="checkmark"></span>
                                            @php 
                                                } else {
                                            @endphp
                                                Stok Habis, <a href="{{ url('/items') }}">Kembali</a>
                                            @php 
                                                }
                                            @endphp
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Bahan:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin">{{ $products->bahan }}</label>
                                    </div>
                                </li>
                                <li>
                                    <span>Available color:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin">{{ $products->color }}</label>
                                    </div>
                                </li>
                                <li>
                                    <span>Berat:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin" class="active">
                                            {{ $products->berat_produk }} G
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