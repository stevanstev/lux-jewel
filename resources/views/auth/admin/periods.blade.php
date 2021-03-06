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
                        <form id="predict_form" method="post" enctype="multipart/form-data">
                            <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                            <input type="text" hidden name="id" value="{{ $id }}"/>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" disabled placeholder="{{ $data->nama_produk }}">
                                    <p style="color:red;">{{ $errors->first('nama_produk') }}</p>
                                </div>

                                <div class="col-md-12">
                                    <label>Pilihan Prediksi</label>
                                    <select onchange="predictType(this.value)" id="pilihan_prediksi" class="form-control"> 
                                        <option value="per_bulan">Per-Bulan</option>
                                        <option value="per_hari">Per-Hari</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <br/>
                                    <p></p>
                                </div>
                                
                                <div class="col-md-6 harian">
                                    <label>Dari</label>
                                    <input autocomplete="off" data-provide="datepicker" type="text" data-date-format="yyyy-mm-dd" value="{{ old('from') }}" class="datepicker" id="datepicker1" name="from" placeholder="Dari">
                                    <p style="color:red;">{{ $errors->first('from') }}</p>
                                </div>
                                <div class="col-md-6 harian">
                                    <label>Sampai</label>
                                    <input autocomplete="off" data-provide="datepicker" value="{{ old('to') }}" type="text" data-date-format="yyyy-mm-dd" class="datepicker" id="datepicker2" name="to" placeholder="Sampai">
                                    <p style="color:red;">{{ $errors->first('to') }}</p>
                                </div>

                                <div class="col-md-6 bulanan">
                                    <label>Dari Bulan</label>
                                    <br/>
                                    <select name="from_m_m">
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                    <select name="from_m_y" id="from_m_y" onchange="validateGreater(this.value,1)">
                                        @php 
                                            for($i = $monthPredictionRange + 100; $i > $monthPredictionRange ; $i--){
                                                echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                            }
                                        @endphp
                                    </select>
                                </div>
                                <div class="col-md-6 bulanan">
                                    <label>Sampai Bulan</label>
                                    <br/>
                                    <select name="to_m_y">
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                    <select name="to_y_m" id="to_y_m" onchange="validateGreater(this.value, 2)">
                                        @php 
                                            for($i = $monthPredictionRange + 100; $i > $monthPredictionRange ; $i--){
                                                echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                            }
                                        @endphp
                                    </select>
                                </div>

                                <div class="col-md-12" align="center">
                                    <br/>
                                    <span id="errorGreater" style="color: red">From cannot greater than to</span>
                                </div>

                                <div class="col-md-12" align="center">
                                    <br/>
                                    <button type="submit" class="site-btn">Prediksi</button>
                                </div>

                                <div class="col-md-12" align="center">
                                    <p style="color:red;">{{ $errors->first('from_y') }}</p>
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
            format: 'yyyy-mm-dd',
            changeYear: true,
            changeMonth: true,
            autoclose: true,
            yearRange: '2000:2020',
        });

        $('#datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            changeYear: true,
            changeMonth: true,
            autoclose: true,
            yearRange: '2000:2020',
        });


        $('.prev i').removeClass();
        $('.prev i').addClass("fa fa-chevron-left");

        $('.next i').removeClass();
        $('.next i').addClass("fa fa-chevron-right");

        $("#errorGreater").hide();
        $('.harian').hide();
        $('#predict_form').attr('action', "{{ url('/predict-by-months') }}");
    });

    function predictType(v) {
        if(v == "per_bulan") {
            $('.harian').hide();
            $('.bulanan').show();
            $('#predict_form').attr('action', "{{ url('/predict-by-months') }}");
        } else {
            $('.bulanan').hide();
            $('.harian').show();
            $('#predict_form').attr('action', "{{ url('/predict-by-periods') }}");
        }
    }

    function validateGreater(v, type){
        let to = $("#to_y_m").val();
        let from = $("#from_m_y").val();
       
        if(type == 1) {
            if(v > to) {
                $("#errorGreater").show();
            } else {
                $("#errorGreater").hide();
            }
        } else {
            if(v < from) {
                $("#errorGreater").show();
            } else {
                $("#errorGreater").hide();
            }
        }
    }
</script>
@endsection