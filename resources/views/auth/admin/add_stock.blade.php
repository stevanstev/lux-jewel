@extends('template/container', ['show' => false])

@section('title')
    Tambah Stok
@endsection

@section('section')
    @if(count($categories) == 0 || count($colors) == 0 || count($senders) == 0)
        @include('template/empty_page', 
            [
                'target' => 'colors', 
                'button_text' => 'Add Variations',
                'leading' => 'Please insert Variations First',
                'image' => 'variation.png',
                'sub_leading' => 'Go now'
            ]
        )
    @else 
        <section class="contact spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8">
                        <div class="contact__content">
                            <div class="contact__address">
                                <h5>Tambah Produk</h5>
                            </div>
                            <div class="contact__form">
                                <form action="{{ url('/tambah-action') }}" method="post" enctype="multipart/form-data">
                                    <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Nama Produk</p>
                                            <input value="{{ old('nama_produk') }}" type="text" name="nama_produk" placeholder="Nama produk">
                                            <p style="color:red;">{{ $errors->first('nama_produk') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Berat Produk</p>
                                            <input value="{{ old('berat_produk') }}" type="text" placeholder="Berat produk" name="berat_produk">
                                            <p style="color:red;">{{ $errors->first('berat_produk') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Foto</p>
                                            <input type="file" placeholder="Foto" name="foto">
                                            <p style="color:red;">{{ $errors->first('foto') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Stok Produk</p>
                                            <input value="{{ old('stok') }}" type="text" placeholder="Stok" name="stok">
                                            <p style="color:red;">{{ $errors->first('stok') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Harga Produk</p>
                                            <input type="text" value="{{ old('harga_produk') }}" placeholder="Harga produk" name="harga_produk">
                                            <p style="color:red;">{{ $errors->first('harga_produk') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Bahan Produk</p>
                                            <select name="bahan" class="form-control">
                                                @foreach($bahans as $b)
                                                    <option value="{{ $b->nama_bahan }}">{{ $b->nama_bahan }}</option>
                                                @endforeach
                                            </select>
                                            <p style="color:red;">{{ $errors->first('bahan') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Warna Produk</p>
                                            <select name="colors" class="form-control">
                                                @foreach($colors as $c)
                                                    <option value="{{ $c->nama_warna }}">{{ $c->nama_warna }}</option>
                                                @endforeach
                                            </select>
                                            <p style="color:red;">{{ $errors->first('colors') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p>Kategori</p>
                                            <select name="kategori" class="form-control">
                                                @foreach($categories as $ct)
                                                    <option value="{{ $ct->nama_kategori }}">{{ $ct->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                            <p style="color:red;">{{ $errors->first('kategori') }}</p>
                                        </div>

                                        <div class="col-md-12">
                                            <p>Deskripsi</p>
                                            <input type="text" value="{{ old('deskripsi') }}" placeholder="Deskripsi" name="deskripsi">
                                            <p style="color:red;">{{ $errors->first('deskripsi') }}</p>
                                        </div>

                                        
                                        <div class="col-md-12" align="center">
                                            <button type="submit" class="site-btn">Tambah</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2"></div>
                </div>
            </div>
            </div>
        </section>
    @endif
@endsection