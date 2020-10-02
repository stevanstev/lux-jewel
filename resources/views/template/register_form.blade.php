<section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2">    
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Register Form</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/register-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input value="{{ old('nama_lengkap') }}" type="text" name="nama_lengkap" placeholder="Nama lengkap">
                                        <p style="color:red;">{{ $errors->first('nama_lengkap') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input value="{{ old('email') }}" type="text" placeholder="Email" name="email">
                                        <p style="color:red;">{{ $errors->first('email') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="password" placeholder="Password" name="password">
                                        <p style="color:red;">{{ $errors->first('password') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input value="{{ old('kode_pos') }}" type="text" placeholder="Kode Pos" name="kode_pos">
                                        <p style="color:red;">{{ $errors->first('kode_pos') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ old('no_hp') }}" placeholder="Nomor HP" name="no_hp">
                                        <p style="color:red;">{{ $errors->first('no_hp') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ old('kota') }}" placeholder="Kota" name="kota">
                                        <p style="color:red;">{{ $errors->first('kota') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ old('provinsi') }}" placeholder="Provinsi" name="provinsi">
                                        <p style="color:red;">{{ $errors->first('provinsi') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" value="{{ old('kelurahan') }}" placeholder="Kelurahan" name="kelurahan">
                                        <p style="color:red;">{{ $errors->first('kelurahan') }}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea placeholder="Alamat" value="{{ old('alamat') }}" name="alamat"></textarea>
                                        <p style="color:red;">{{ $errors->first('alamat') }}</p>
                                    </div>
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                </div>
            </div>
        </div>
    </div>
</section>