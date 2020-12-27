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
            <div class="col-md-5">
                <h3>Riwayat</h3>
            </div>
            <div class="col-md-5" align="right">
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
                            <th scope="col">Nomor Telepon</th>
                            <th scope="col">Tanggal Transaksi</th>
                            <th scope="col">
                                Status
                                &nbsp;
                                <a href="#" style="color: lightblue;" data-toggle="modal" data-target="#filterModal"><span class="fa fa-filter"></span> Filter</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->nama_penerima }}</td>
                                <td>Rp. {{ number_format($item->total_transaksi, 2) }}</td>
                                <td>{{ $item->no_resi }}</td>
                                <td>{{ $item->no_telepon }}</td>
                                <td>{{ $item->tgl_transaksi }}</td>
                                <td>
                                    @php
                                        if($item->status_pesanan == 5) {
                                            echo '<span style="color: green;">Success</span>';
                                        } else if ($item->status_pesanan == 6) {
                                            echo '<span style="color: red;">Failed</span>';
                                        }
                                    @endphp
                                </td>
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

    <div class="modal" tabindex="-1" role="dialog" id="filterModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="fa fa-filter"></span> Filter Riwayat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><h5>Periode</h5></p>
                    <form method="post" action="{{ url('/search-history') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="isCustom" value="true" />
                        <div class="row">
                            <div class="col-md-6">
                                <input autocomplete="off" data-provide="datepicker" type="text" data-date-format="yyyy-mm-dd" value="{{ old('from') }}" class="datepicker form-control" id="datepicker1" name="custom[]" placeholder="Dari">
                            </div>
                            <!-- <div class="col-md-2"></div> -->
                            <div class="col-md-6">
                                <input autocomplete="off" data-provide="datepicker" value="{{ old('to') }}" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control" id="datepicker2" name="custom[]" placeholder="Sampai">
                            </div>
                        </div>
                        <p><h5>Status Transaksi</h5></p>
                        <div class="row">
                            <div class="col-md-12">
                                <select name="custom[]" class="form-control">
                                    <option value="5">Success</option>
                                    <option value="6">Failed</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><span class="fa fa-filter"></span> Filter</button>
                </div>
                </form>
            </div>
        </div>
    </div>

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