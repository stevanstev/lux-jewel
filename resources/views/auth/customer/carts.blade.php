@extends('template/container', ['show' => true])

@section('title')
    Carts
@endsection

@section('section')
    @if(count($carts) == 0)
        @include('template/empty_page', 
            [
                'target' => 'items', 
                'button_text' => 'Continue Shopping',
                'leading' => 'Your Cart is Empty',
                'image' => 'cart.png',
                'sub_leading' => 'Buy Something now'
            ]
        )
    @else 
        @php 
            $sub_total = 0;
            $kurir = "";
        @endphp
        <section class="shop-cart spad container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="shop__cart__table">
                            <table class="table">
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
                                            <img src="{{ url('img/product') }}/{{ str_replace(' ', '%20', $c->foto) }}" alt="" width="100">
                                            <div class="cart__product__item__title">
                                                <h6>{{ $c->nama_produk }}</h6>
                                            </div>
                                        </td>
                                        <td class="cart__price">Rp.{{ number_format($c->harga_produk, 2) }}</td>
                                        <td class="cart__total">x{{ $c->jumlah }}</td>
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
                            <h5><i class="fa fa-send"></i> Kurir Pengiriman</h5>
                            <br/>
                            <select class="form-control" onchange="getval(this)">
                                <option value="" selected="true">Pilih Kurir</option>
                                @foreach($sender as $s)
                                    <option value="{{ $s->nama_pengirim }}">{{ $s->nama_pengirim }}</option>
                                @endforeach
                            </select>
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
                                <input type="hidden" name="sub-total" id="sub-total" value="{{ $sub_total }}"/>
                                <li>Biaya Kurir <span id="tambahan">Rp 0</span></li>
                                <li>Total <span id="total-harga">Rp {{ number_format($sub_total, 2) }}</span></li>
                            </ul>
                            <form action="{{ url('/item-checkout') }}" method="post">
                                <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                                <input type="hidden" value="{{ $jsonItems }}" name="jsonItems" />
                                <input type="hidden" name="kurir-final" id="kurir-final"/>
                                <input type="hidden" name="nama-kurir" id="nama-kurir"/>
                                <input type="hidden" value="{{ $sub_total }}" name="total_transaksi" /> 
                                <button class="btn btn-primary" id="btn-checkout">Proceed to checkout</button>
                                <span>{{ $errors->first('kurir-final') }}</span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            $(document).ready(() => {
                $("#tambahan").html('Pilih Kurir Pengiriman');
                $("#btn-checkout").prop('disabled',true);
                $("#kurir-final").val('');
                $("#nama-kurir").val('');
            });

            function getval(sel){
                if (sel.value == "JNE") {
                    $("#tambahan").html('Rp 20,000.00');
                    $("#kurir-final").val('20000');
                    $("#nama-kurir").val(sel.value);
                    let current = $("#sub-total").val();
                    $("#total-harga").html('Rp ' + (parseInt(current) + 20000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00');
                    $("#btn-checkout").prop('disabled',false);
                } else if(sel.value == "JNT") {
                    $("#tambahan").html('Rp 15,000.00');
                    $("#kurir-final").val('15000');
                    $("#nama-kurir").val(sel.value);
                    let current = $("#sub-total").val();
                    $("#total-harga").html('Rp ' + (parseInt(current) + 15000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00');
                    $("#btn-checkout").prop('disabled',false);
                } else {
                    if (sel.value != "") {
                        $("#tambahan").html('Rp. 18.000.00');
                        $("#kurir-final").val('18000');
                        $("#nama-kurir").val(sel.value);
                        let current = $("#sub-total").val();
                        $("#total-harga").html('Rp ' + (parseInt(current) + 18000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00');
                        $("#btn-checkout").prop('disabled',false);
                    } else {
                        $("#tambahan").html('Pilih Kurir Pengiriman');
                        $("#kurir-final").val('');
                        $("#nama-kurir").val('');
                        let current = $("#sub-total").val();
                        $("#total-harga").html('Rp ' + (parseInt(current) + 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00');
                        $("#btn-checkout").prop('disabled',true);
                    }
                }
            }
        </script>
    @endif
@endsection