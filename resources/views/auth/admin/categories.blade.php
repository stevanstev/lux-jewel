@extends('template/container', ['show' => true])

@section('title')
    Categories
@endsection

@section('section')
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-1 col-md-1"></div>
            <div class="col-lg-5 col-md-5">
                <table class="table"> 
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->nama_kategori }}</td>
                                <td>{{ $d->created_at }}</td>
                                <td>
                                    <form method="post" action="{{ url('/category-delete') }}">
                                        @csrf
                                        <input type="hidden" value="{{ $d->id }}" name="id" /> 
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>    
                        @endforeach
                    <tbody>
                </table>
                {{ $data->render() }}
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="contact__content">
                    <div class="contact__address">
                        <h5>Tambah Kategori</h5>
                    </div>
                    <div class="contact__form">
                        <form action="{{ url('/add-kategori') }}" method="post" enctype="multipart/form-data">
                            <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="kategori" placeholder="Kategori">
                                    <p style="color:red;">{{ $errors->first('kategori') }}</p>
                                </div>

                                <div class="col-md-12" align="center">
                                    <button type="submit" class="site-btn">Tambah</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2"></div>
    </div>
</section>
<hr />
@endsection