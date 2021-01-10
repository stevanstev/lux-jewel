@extends('template/container', ['show' => false])

@section('title')
    Upload Bukti
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Upload Bukti Transaksi</h5>
                            <span><i>Bukti Transaksi Wajib diisi, data penerima tidak wajib, jika tidak diisi, maka akan diisi dengan data default</i></span>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/upload-bukti-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <input type="text" hidden name="id" value="{{ $data->id }}"/>
                                <input type="text" hidden name="items" value="{{ $data->items }}" />
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <input type="file" name="bukti">
                                        <p style="color:red;">{{ $errors->first('bukti') }}</p>
                                    </div>
                                    <div class="col-md-1"></div>

                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Submit</button>
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