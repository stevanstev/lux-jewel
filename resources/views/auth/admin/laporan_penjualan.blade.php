@extends('template/container', ['show' => false])

@section('title')
    Laporan Penjualan
@endsection

@section('section')
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Generate Report</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/pdf-reporting-generate') }}" method="get" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                <select name="periode" class="form-control">
                                    @php 
                                        function convertDate($monthNum) {
                                            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                            $monthName = $dateObj->format('F');

                                            return $monthName;
                                        }
                                    @endphp
                                    @foreach($periodic as $p)
                                        <option value="{{ $p }}">{{ convertDate(explode('-', $p)[1]) }} - {{ explode('-', $p)[0] }}</option>
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