@extends('template/container', ['show' => false])

@section('title')
    Stock
@endsection

@section('section')
    @php 
        // if no data in stock, then tell user to insert one
    @endphp
    @if(count($products) == 0)
        @include('template/empty_page', 
            [
                'target' => 'tambah-stuff', 
                'button_text' => 'Isi Product',
                'leading' => 'Product is Empty',
                'image' => 'variation.png',
                'sub_leading' => 'Tambah Product'
            ]
        )
    @else 
    <section class="contact spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8">
                        <div class="contact__content">
                            <div class="contact__address">
                                <h5>Tambah Stock</h5>
                            </div>
                            <div class="contact__form">
                                <form action="{{ url('/tambah-stock') }}" method="post" enctype="multipart/form-data">
                                    <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Pilih Produk</p>
                                            <select name="id_produk" class="form-control" id="id_produk" onchange="getDetails(this.value)">
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                                                @endforeach
                                            </select>
                                            <p style="color:red;">{{ $errors->first('nama_produk') }}</p>
                                        </div>

                                        <div class="col-md-12">
                                            <p>Total Stock</p>
                                            <input type="number" min="0" value="{{ old('total_stock') }}" id="total_stock" placeholder="Total Stock" name="total_stock">
                                            <p style="color:red;">{{ $errors->first('total_stock') }}</p>
                                        </div>
                                        
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
    @endif

    <script>
        $(function() {
            let getUrl = window.location;
            let baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
            $.ajax({
                url: baseUrl + 'fetch-items-details/' + {{ $products[0]->id }},
            }).done((details) => {
                $("#total_stock").val(details.totalStock);
            });
        });

        function getDetails(id) {
            let getUrl = window.location;
            let baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
            $.ajax({
                url: baseUrl + 'fetch-items-details/' + id,
            }).done((details) => {
                $("#total_stock").val(details.totalStock);
            });
        }
    </script>
@endsection