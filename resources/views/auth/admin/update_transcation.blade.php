@extends('template/container', ['show' => false])

@section('title')
    Update Transaction
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Update Transaction</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/update-pengiriman') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <input type="text" hidden name="id" value="{{ $data->id }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="biaya_pengiriman" placeholder="Biaya Pengiriman">
                                        <p style="color:red;">{{ $errors->first('biaya_pengiriman') }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" placeholder="Kurir" name="kurir">
                                        <p style="color:red;">{{ $errors->first('kurir') }}</p>
                                    </div>

                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Update</button>
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