@extends('template/container', ['show' => false])

@section('title')
    Laporan Prediksi
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Generate Report For Prediction</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/pdf-predict-generate') }}" method="get" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <select name="nama_produk" class="form-control">
                                    @foreach($products as $p)
                                        <option value="{{ $p->nama_produk }}">{{ $p->nama_produk }}</option>
                                    @endforeach
                                </select>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="site-btn">Generate</button>
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