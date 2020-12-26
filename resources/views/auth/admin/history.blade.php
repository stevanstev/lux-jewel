@extends('template/container', ['show' => false])

@section('title')
    History
@endsection

@section('section')
    @if(count($results) == 0 && $toggle == false) 
        @include('template/empty_page', 
            [
                'target' => 'order', 
                'button_text' => 'Order List',
                'leading' => 'No History for now',
                'image' => 'order.png',
                'sub_leading' => ''
            ]
        )
    @else
        <div class="row" style="margin-top: 20px">
            <div class="col-md-1"></div>
            <div class="col-md-2">
                <h3>Riwayat</h3>
            </div>
            <div class="col-md-6" align="right">
                <form method="post" action="{{ url('/search-history') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="isCustom" value="true" />
                    <input autocomplete="off" data-provide="datepicker" type="text" data-date-format="yyyy-mm-dd" value="{{ old('from') }}" class="datepicker" id="datepicker1" name="custom[]" placeholder="Dari">
                    <input autocomplete="off" data-provide="datepicker" value="{{ old('to') }}" type="text" data-date-format="yyyy-mm-dd" class="datepicker" id="datepicker2" name="custom[]" placeholder="Sampai">
                    <button class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-2" align="right">
                <a class="btn btn-success" href="{{ url('/laporan-penjualan') }}">Buat Laporan</a>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-12" style="margin-top: 20px">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Total Transaksi</th>
                            <th scope="col">Nomor Resi</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->nama_penerima }}</td>
                                <td>{{ $item->total_transaksi }}</td>
                                <td>{{ $item->no_resi }}</td>
                                <td>{{ ($item->status_pesanan == 5) ? 'Success' : 'Failed'}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" align="center">
                {{ $results->render() }}
            </div>
            <div class="col-md-4"></div>
        </div>
    @endif

    <script>
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
        });


    </script>
@endsection