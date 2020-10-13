@extends('template/container', ['show' => true])

@section('title')
    Home
@endsection

@section('section')
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="section-title">
                        <h4>Our product</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="section-title">
                        <form method="post" action="{{ url('/search-items') }}">
                            <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                            <input type="text" name="search" placeholder="Search Product"/>
                            <button type="submit" class="btn btn-success">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row property__gallery">
                @foreach($results as $p) 
                    <div class="col-lg-3 col-md-4 col-sm-6 mix">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="{{ url('img/product') }}/{{ str_replace(' ', '%20', $p->foto) }}">
                                <ul class="product__hover">
                                    <li><a href="{{ url('img/product') }}/{{ str_replace(' ', '%20', $p->foto) }}" class="image-popup"><span class="arrow_expand"></span></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <a href="{{ url('/item-details') }}/{{ $p->id }}" class="btn btn-success"/>Beli Produk</a>
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
                {{ $results->render() }}
            </div>
            <div class="col-md-4"></div>
        </div>
    </section>
@endsection