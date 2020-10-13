@extends('template/container', ['show' => false])

@section('title')
    Transaction
@endsection

@section('section')
    @if(count($results) == 0 && $toggle == false)
        @include('template/empty_page', 
            [
                'target' => 'admin-dash', 
                'button_text' => 'Home',
                'leading' => 'No order for now',
                'image' => 'order.png',
                'sub_leading' => ''
            ]
        )
    @else 
        <div class="row" style="margin-top: 20px">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <h3>Order List</h3>
            </div>
            <div class="col-md-7" align="right">
                <form method="post" action="{{ url('/search-order') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="text" name="search" placeholder="Cari Transaksi"/>
                    <button class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-12" style="margin-top: 20px">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID Transaksi</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Kurir</th>
                            <th scope="col">Status</th>
                            <th scope="col">#</th>
                            <th scope="col">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $d)
                            @php
                                $details = json_decode($d->items);
                            @endphp
                            <tr>
                                <th scope="row">{{ $d->id }}</th>
                                <td>Rp. {{ number_format($d->total_transaksi, 2) }}</td>
                                <td>{{ ($d->kurir != null) ? $d->kurir.' '.'Rp.'.number_format($d->biaya_kirim,2) : '#' }}</td>
                                <td>
                                    @php
                                        if($d->status_pesanan == 0) {
                                            echo "Menunggu Update Transaksi";
                                        } else if($d->status_pesanan == 1) {
                                            echo "Menunggu Pembayaran";
                                        } else if($d->status_pesanan == 2) {
                                            echo "Menunggu Konfirmasi";
                                        } else if($d->status_pesanan == 3){
                                            echo "Menunggu Penerimaan";
                                        } else {
                                            echo "Pesanan Selesai";
                                        }
                                    @endphp
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{ $d->id }}">
                                        Details
                                    </button>
                                </td>
                                <td>    
                                    @php 
                                        if($d->status_pesanan == 0) {
                                    @endphp
                                        <a href="{{ url('/tambah-detail') }}/{{ $d->id }}" type="button" class="btn btn-success">
                                            Update Transaksi
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 1) {
                                    @endphp 
                                        <a href="#" type="button" class="btn btn-danger">
                                            Menunggu Bukti Pembayaran
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 2) {
                                    @endphp 
                                        <a href="{{ url('/konfirmasi-bayar') }}/{{ $d->id }}" type="button" class="btn btn-warning">
                                            Konfirmasi Pembayaran
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 3) {
                                    @endphp 
                                        <a href="#" type="button" class="btn btn-success">
                                            Menunggu Penerimaan
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 4){
                                    @endphp
                                        <form method="post" action="{{ url('/selesai-transaksi') }}">
                                            <input type="hidden" name="id" value="{{ $d->id }}" />
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <button type="submit" class="btn btn-warning">Selesai Transaksi</button>
                                        <form>
                                    @php
                                        } else {
                                    @endphp 
                                        -
                                    @php
                                        }
                                    @endphp
                                </td>
                            </tr>
                            <div class="modal fade" id="exampleModal{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Nama: <b>{{ $d->nama_penerima }}</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Alamat: <b>{{ $d->alamat_penerima }}</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Nomor Telepon: <b>{{ $d->no_telepon }}</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Tanggal Transaksi: <b>{{ $d->tgl_transaksi }}</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Kota: <b>{{ $d->kota_penerima }}</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Provinsi: <b>{{ $d->provinsi_penerima }}</b></p>
                                        </div>
                                    </div>
                                    <h4>Pesanan</h4>
                                    @php 
                                        foreach($details as $key => $value) {
                                    @endphp
                                        <div class="row">
                                            <div class="col-md-6"><img width="250" src="{{ url('img/product') }}/{{ str_replace(' ', '%20', $value->foto) }}"></div>
                                            <div class="col-md-6">
                                                <p>Nama: {{ $value->nama_produk }}</p>
                                                <p>Berat: {{ $value->berat_produk }}</p>
                                                <p>Harga: Rp.{{ number_format($value->harga_produk,2) }}</p>
                                                <p>Jumlah: {{ $value->jumlah }}</p>
                                                <p>Total Harga: Rp.{{ number_format($value->total_harga,2) }}</p>
                                            </div>
                                        </div>
                                        <br/>
                                    @php 
                                        }
                                    @endphp
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                {{ $results->render() }}
            </div>
        </div>
    @endif
@endsection