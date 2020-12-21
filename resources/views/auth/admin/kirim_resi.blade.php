@extends('template/container', ['show' => false])

@section('title')
    Kirim nomor resi
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Kirim Nomor Resi</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/kirim-resi-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <input type="text" hidden name="id" value="{{ $data->id }}"/>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <input type="text" name="nomor_resi" placeholder="Nomor Resi">
                                        <p style="color:red;">{{ $errors->first('nomor_resi') }}</p>
                                    </div>
                                    <div class="col-md-2"></div>


                                    <div class="col-md-12">
                                        <br/>
                                    </div>
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Kirim Nomor Resi</button>
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