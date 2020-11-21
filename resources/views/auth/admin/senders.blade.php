@extends('template/container', ['show' => true])

@section('title')
    Senders
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
                            <th>Pengirim</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->nama_pengirim }}</td>
                                <td>{{ $d->created_at }}</td>
                                <td>
                                    <form method="post" action="{{ url('/sender-delete') }}">
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
                        <h5>Tambah Pengirim</h5>
                    </div>
                    <div class="contact__form">
                        <form action="{{ url('/add-sender') }}" method="post" enctype="multipart/form-data">
                            <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="sender" placeholder="Pengirim">
                                    <p style="color:red;">{{ $errors->first('sender') }}</p>
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