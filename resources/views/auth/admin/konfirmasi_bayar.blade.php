@extends('template/container', ['show' => false])

@section('title')
    Konfirmasi Pembayaran
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Konfirmasi Pembayaran</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/konfirmasi-bayar-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <input type="text" hidden name="id" value="{{ $data->id }}"/>
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <img width="400" src="{{ url('img/proves') }}/{{ str_replace(' ', '%20', $data->bukti_pembayaran) }}" />
                                    </div>

                                    <div class="col-md-12">
                                        <br/>
                                    </div>

                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <select name="konfirmasi" class="form-control"> 
                                            <option value="true">Terima</option>
                                            <option value="false">Tolak</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2"></div>


                                    <div class="col-md-12">
                                        <br/>
                                    </div>
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Konfirmasi</button>
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