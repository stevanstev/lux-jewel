@extends('template/container', ['show' => false])

@section('title')
    Ubah Stok
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Ubah Produk</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/update-stock-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <input type="text" hidden name="id" value="{{ $products->id }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Nama Produk</span>
                                        <p></p>
                                        <input value="{{ $products->nama_produk }}" type="text" name="nama_produk" placeholder="Nama produk">
                                        <p style="color:red;">{{ $errors->first('nama_produk') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <span>Berat Produk</span>
                                        <p></p>
                                        <input value="{{ $products->berat_produk }}" type="text" placeholder="Berat produk" name="berat_produk">
                                        <p style="color:red;">{{ $errors->first('berat_produk') }}</p>
                                    </div>     

                                    <div class="col-md-6">
                                        <span>Stok Produk</span>
                                        <p></p>
                                        <input value="{{ $products->stok }}" type="text" placeholder="Stok" name="stok">
                                        <p style="color:red;">{{ $errors->first('stok') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <span>Harga Produk</span>
                                        <p></p>
                                        <input type="text" value="{{ $products->harga_produk }}" placeholder="Harga produk" name="harga_produk">
                                        <p style="color:red;">{{ $errors->first('harga_produk') }}</p>
                                    </div>


                                    <div class="col-md-6">
                                        <span>Kategori Produk</span>
                                        <p></p>
                                        <select name="kategori" class="form-control">
                                            @foreach($categories as $ct)
                                                <option value="{{ $ct->nama_kategori }}" selected="{{ ($ct->nama_kategori == $products->kategori) ? 'true' : '' }}">{{ $ct->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        <p style="color:red;">{{ $errors->first('kategori') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <span>Warna Produk</span>
                                        <p></p>
                                        <select name="colors" class="form-control">
                                            @foreach($colors as $c)
                                                <option value="{{ $c->nama_warna }}" selected="{{ ($c->nama_warna == $checkedColor) ? 'true' : '' }}">{{ $c->nama_warna }}</option>
                                            @endforeach
                                        </select>
                                        <p style="color:red;">{{ $errors->first('colors') }}</p>
                                    </div>

                                    <div class="col-md-12">
                                        <span>Bahan Produk</span>
                                        <p></p>
                                        <select name="bahan" class="form-control">
                                            @foreach($bahans as $b)
                                                <option value="{{ $b->nama_bahan }}" selected="{{ ($b->nama_bahan == $checkedBahan) ? 'true' : '' }}">{{ $b->nama_bahan }}</option>
                                            @endforeach
                                        </select>
                                        <p style="color:red;">{{ $errors->first('bahan') }}</p>
                                    </div>

                                    <div class="col-md-12">
                                        <span>Deskripsi Produk</span>
                                        <p></p>
                                        <input type="text" value="{{ $products->deskripsi }}" placeholder="Deskripsi" name="deskripsi">
                                        <p style="color:red;">{{ $errors->first('deskripsi') }}</p>
                                    </div>

                                    <div class="col-md-12">
                                        <span>Foto Produk</span>
                                        <p></p>
                                        <img src="{{ url('img/product') }}/{{$products->foto}}" class="img-thumbnail" style="width:100"/>
                                        <p></p>
                                        <input type="file" placeholder="Foto" id="new-photo" name="foto">
                                        <input type="text" readonly placeholder="{{ $products->foto }}" onclick="setFieldVisible()" id="exist-photo"/>
                                    </div>       
                                    
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Ubah</button>
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

    <script>
        $(function() {
            $('#exist-photo').attr('hidden', false);
            $('#new-photo').attr('hidden', true);
        });

        function setFieldVisible() {
            $('#exist-photo').attr('hidden', true);
            $('#new-photo').attr('hidden', false);
        }
    </script>
@endsection