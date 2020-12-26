@extends('template/container', ['show' => false])

@section('title')
    Produk
@endsection

@section('section')
    @php 
        // if no data in stock, then tell user to insert one
    @endphp
    @if(count($results) == 0 && $toggle == false)
        @include('template/empty_page', 
            [
                'target' => 'tambah-stuff', 
                'button_text' => 'Isi Product',
                'leading' => 'Product is Empty',
                'image' => 'variation.png',
                'sub_leading' => 'Tambah Product'
            ]
        )
    @else 
        <div class="row" style="margin-top: 20px">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <h3>Produk</h3>
            </div>
            <div class="col-md-6" align="right">
                <form method="post" action="{{ url('/search-stuff') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="text" name="search" placeholder="Cari Barang"/>
                    <button class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-1" align="right">
                <a href="{{ url('/tambah-stuff') }}" class="btn btn-success">Tambah</a>
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
                            <th scope="col">Deskripsi</th>
                            <th scope="col">#</th>
                            <th scope="col">#</th>
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
                                <td>{{ $item->total_stok }}</td>
                                <td>{{ $item->harga_produk }}</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td>
                                    <a href="{{ url('/update-stuff') }}/{{ $item->id }}" class="btn btn-primary">Ubah</a>    
                                </td>
                                <td>
                                    <form method="post" action="{{ url('/delete-stuff') }}">
                                        <input type="text" hidden name="_token" value="{{ csrf_token() }}" />      
                                        <input type="text" hidden name="id" value="{{ $item->id }}" />
                                        <button class="btn btn-danger">Hapus</button>
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
                {{ $results->render() }}
            </div>
            <div class="col-md-4"></div>
        </div>
    @endif
@endsection