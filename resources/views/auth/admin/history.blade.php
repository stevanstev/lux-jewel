@extends('template/container', ['show' => false])

@section('title')
    History
@endsection

@section('section')
    @if(count($t) == 0) 
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
            <div class="col-md-3">
                <h3>Riwayat Transaksi</h3>
            </div>
            <div class="col-md-6" align="right">
                <form method="post" action="{{ url('/search-history') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="text" name="history" placeholder="Cari Riwayat"/>
                    <button class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-12" style="margin-top: 20px">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Total Transaksi</th>
                            <th scope="col">Nomor Resi</th>
                            <th scope="col">Status</th>
                            <th scope="col">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($t as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->nama_penerima }}</td>
                                <td>{{ $item->total_transaksi }}</td>
                                <td>{{ $item->no_resi }}</td>
                                <td>{{ ($item->status_pesanan == 5) ? 'Success' : 'Failed'}}</td>
                                <td>
                                    <!-- <form method="post" action="{{ url('/delete-stock') }}">
                                        <input type="text" hidden name="_token" value="{{ csrf_token() }}" />      
                                        <input type="text" hidden name="id" value="{{ $item->id }}" />
                                        <button class="btn btn-danger">Hapus</button>
                                    </form> -->
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
                {{ $t->render() }}
            </div>
            <div class="col-md-4"></div>
        </div>
    @endif
@endsection