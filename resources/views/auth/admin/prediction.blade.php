@extends('template/container', ['show' => false])

@section('title')
    Prediction
@endsection

@section('section')
<div class="row" style="margin-top: 20px">
        <div class="col-md-1"></div>
        <div class="col-md-3">
            <h3>Barang</h3>
        </div>
        <div class="col-md-6" align="right">
            <form method="post" action="{{ url('/search-stock') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="text" name="item" placeholder="Cari Barang"/>
                <button class="btn btn-primary">Cari</button>
            </form>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-12" style="margin-top: 20px">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Berat</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Prediksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->berat_produk }}</td>
                            <td>
                                <img src="/img/product/{{ $item->foto }}" width="80"/>
                            </td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->harga_produk }}</td>
                            <td>
                                <form method="post" action="{{ url('/predict-action') }}">
                                    <input type="text" hidden name="_token" value="{{ csrf_token() }}" />      
                                    <input type="text" hidden name="id" value="{{ $item->id }}" />
                                    <button class="btn btn-success">Prediksi</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" align="center">
            {{ $products->render() }}
        </div>
        <div class="col-md-4"></div>
    </div>
@endsection