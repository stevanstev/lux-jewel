@extends('template/container', ['show' => false])

@section('title')
    Variations
@endsection

@section('section')
<section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Tambah Warna</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/add-color') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="color" placeholder="Color">
                                        <p style="color:red;">{{ $errors->first('color') }}</p>
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

    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Tambah Kategori</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/add-kategori') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="kategori" placeholder="Kategori Produk">
                                        <p style="color:red;">{{ $errors->first('kategori') }}</p>
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

    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Tambah Pengirim</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/add-sender') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="sender" placeholder="Nama Pengirim">
                                        <p style="color:red;">{{ $errors->first('sender') }}</p>
                                    </div>

                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Add</button>
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
@endsection