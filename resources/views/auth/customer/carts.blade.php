@extends('template/container', ['show' => true])

@section('title')
    Carts
@endsection

@section('section')
    @php 
        $sub_total = 0;
    @endphp
    <section class="shop-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $c)
                                    @php
                                        $sub_total += $c->total_harga
                                    @endphp
                                <tr>
                                    <td class="cart__product__item">
                                        <img src="{{ url('img/product') }}/{{ $c->foto }}" alt="" width="100">
                                        <div class="cart__product__item__title">
                                            <h6>{{ $c->nama_produk }}</h6>
                                        </div>
                                    </td>
                                    <td class="cart__price">Rp.{{ number_format($c->harga_produk, 2) }}</td>
                                    <td class="cart__total">{{ $c->jumlah }}</td>
                                    <td class="cart__total">Rp.{{ number_format($c->total_harga, 2) }}</td>
                                    <td class="cart__close">
                                        <form method="post" action="{{ url('/delete-cart-item') }}">
                                            <input type="hidden" value="{{ $c->id }}" name="item_id"/>
                                            <input type="hidden" value="{{ csrf_token() }}" name="_token"/>
                                            <button type="submit" style="border:none; background-color: white;">
                                                <span class="icon_close"></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="cart__btn">
                        
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="cart__btn update__btn">
                        <a href="{{ url('/items') }}">Continue Shopping</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="discount__content">
                        
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-2">
                    <div class="cart__total__procced">
                        <h6>Total Pembayaran</h6>
                        <ul>
                            <li>Subtotal <span>Rp {{ number_format($sub_total, 2) }}</span></li>
                            <li>Total <span>Rp {{ number_format($sub_total, 2) }}</span></li>
                        </ul>
                        <form action="{{ url('/item-checkout') }}" method="post">
                            <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                            <input type="hidden" value="{{ $jsonItems }}" name="jsonItems" />
                            <input type="hidden" value="{{ $sub_total }}" name="total_transaksi" />
                            <button class="btn btn-primary">Proceed to checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection