@extends('template/container', ['show' => false])

@section('title')
    Transaction
@endsection

@section('section')
    @if(count($results) == 0 && $toggle == false)
        @include('template/empty_page', 
            [
                'target' => 'items', 
                'button_text' => 'Continue Shopping',
                'leading' => 'Your Transaction is Empty',
                'image' => 'cart.png',
                'sub_leading' => 'Buy Something now'
            ]
        )
    @else 
        <div class="row" style="margin-top: 20px">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <h3>Status Transaksi</h3>
            </div>
            <div class="col-md-7" align="right">
                <form method="post" action="{{ url('/search-transactions') }}">
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
                            <th scope="col">No Resi</th>
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
                                <td>{{ ($d->no_resi != null) ? $d->no_resi : '#' }}</td>
                                <td>
                                    @php
                                        if($d->status_pesanan == 0) {
                                            echo "Menunggu Update Admin";
                                        } else if($d->status_pesanan == 1) {
                                            echo "Menunggu Pembayaran";
                                        } else if($d->status_pesanan == 2) {
                                            echo "Menunggu Konfirmasi";
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
                                        <button type="button" class="btn btn-danger">
                                            Menunggu Update Admin
                                        </button>
                                    @php
                                        } else if($d->status_pesanan == 1) {
                                    @endphp 
                                        <a href="{{ url('/upload-bukti') }}/{{ $d->id }}" class="btn btn-success">
                                            Upload Pembayaran
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 2) {
                                    @endphp 
                                        <a href="#" type="button" class="btn btn-danger">
                                            Menunggu Konfirmasi Admin
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 3) {
                                    @endphp 
                                        <a href="#" type="button" class="btn btn-danger">
                                            Menunggu Nomor Resi
                                        </a>
                                    @php
                                        } else if($d->status_pesanan == 4){
                                    @endphp 
                                        <form method="post" action="{{ url('/konfirmasi-sampai') }}">
                                            <input type="hidden" name="id" value="{{ $d->id }}" />
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <button type="submit" class="btn btn-warning">Konfirmasi Sampai</button>
                                        <form>
                                    @php   
                                        } else {
                                    @endphp
                                        <a href="#" type="button" class="btn btn-success">
                                            Transaksi Selesai
                                        </a>
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
                                                <p>Bahan: {{ $value->bahan }}</p>
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