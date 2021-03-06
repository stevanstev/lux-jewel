@extends('template/container', ['show' => false])

@section('title')
    Prediction
@endsection

@section('section')
    @if(count($results) == 0 && $toggle == false)
        @include('template/empty_page', 
            [
                'target' => 'stock', 
                'button_text' => 'Isi Stock',
                'leading' => 'Prediction is Empty',
                'image' => 'predict.png',
                'sub_leading' => 'Tambah Stock'
            ]
        )
    @else 
        <div class="row" style="margin-top: 20px">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <h3>Prediksi</h3>
            </div>
            <div class="col-md-4" align="right">
                <form method="post" action="{{ url('/search-prediction') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="text" name="search" placeholder="Cari Item"/>
                    <button class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-3" align="right">
                <a class="btn btn-success" href="{{ url('/laporan-prediksi') }}">Buat Laporan</a>
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
                        @foreach($results as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ $item->berat_produk }}</td>
                                <td>
                                    <img src="{{ url('/img/product/') }}/{{ str_replace(' ', '%20', $item->foto) }}" width="80"/>
                                </td>
                                <td>{{ $item->stok }}</td>
                                <td>{{ $item->harga_produk }}</td>
                                <td>
                                    <a class="btn btn-success" href="{{ url('/periods') }}/{{ $item->id }}">Prediksi</a>
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
                {{ $results->render() }}
            </div>
            <div class="col-md-4"></div>
        </div>
    @endif
@endsection