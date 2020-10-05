@extends('template/container', ['show' => false])

@section('title')
    User Profile
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Ubah Profile</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/update-user-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <input type="text" hidden name="id" value="{{ Auth::user()->id }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input value="{{ Auth::user()->nama_lengkap }}" type="text" name="nama_lengkap" placeholder="Nama lengkap">
                                        <p style="color:red;">{{ $errors->first('nama_lengkap') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <input value="{{ Auth::user()->kode_pos }}" type="text" placeholder="Kode Pos" name="kode_pos">
                                        <p style="color:red;">{{ $errors->first('kode_pos') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <input value="{{ Auth::user()->no_hp }}" type="text" placeholder="Nomor Hp" name="no_hp">
                                        <p style="color:red;">{{ $errors->first('no_hp') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" value="{{ Auth::user()->kota }}" placeholder="Kota" name="kota">
                                        <p style="color:red;">{{ $errors->first('kota') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" value="{{ Auth::user()->provinsi }}" placeholder="Provinsi" name="provinsi">
                                        <p style="color:red;">{{ $errors->first('provinsi') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" value="{{ Auth::user()->kelurahan }}" placeholder="Kelurahan" name="kelurahan">
                                        <p style="color:red;">{{ $errors->first('kelurahan') }}</p>
                                    </div>

                                    <div class="col-md-12">
                                        <input placeholder="Alamat" value="{{ Auth::user()->alamat }}" name="alamat">
                                        <p style="color:red;">{{ $errors->first('alamat') }}</p>
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
@endsection