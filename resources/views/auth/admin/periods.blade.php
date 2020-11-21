@extends('template/container', ['show' => true])

@section('title')
    Prediction Periods
@endsection

@section('section')
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3"></div>
            <div class="col-lg-6 col-md-6">
                <div class="contact__content">
                    <div class="contact__address">
                        <h5>Masukkan Periode Untuk Prediksi</h5>
                    </div>
                    <div class="contact__form">
                        <form action="{{ url('/predict-by-periods') }}" method="post" enctype="multipart/form-data">
                            <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                            <input type="text" hidden name="id" value="{{ $id }}"/>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="nama_produk" disabled placeholder="{{ $data->nama_produk }}">
                                    <p style="color:red;">{{ $errors->first('nama_produk') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <input data-provide="datepicker" type="text" data-date-format="mm/dd/yyyy" value="{{ old('from') }}" class="datepicker" id="datepicker1" name="from" placeholder="Dari">
                                    <p style="color:red;">{{ $errors->first('from') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <input data-provide="datepicker" value="{{ old('to') }}" type="text" data-date-format="mm/dd/yyyy" class="datepicker" id="datepicker2" name="to" placeholder="Sampai">
                                    <p style="color:red;">{{ $errors->first('to') }}</p>
                                </div>

                                <div class="col-md-12" align="center">
                                    <button type="submit" class="site-btn">Prediksi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3"></div>
    </div>
</section>

<script type="text/javascript"> 
    $(function() {
        $('#datepicker1').datepicker({
            format: 'mm/dd/yyyy',
            changeYear: true,
            changeMonth: true,
            autoclose: true
        });

        $('#datepicker2').datepicker({
            format: 'mm/dd/yyyy',
            changeYear: true,
            changeMonth: true,
            autoclose: true
        });


        $('.prev i').removeClass();
        $('.prev i').addClass("fa fa-chevron-left");

        $('.next i').removeClass();
        $('.next i').addClass("fa fa-chevron-right");
    });
</script>
@endsection