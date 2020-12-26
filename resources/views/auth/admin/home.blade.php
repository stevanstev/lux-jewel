@extends('template/container', ['show' => true])

@section('title')
    Home
@endsection

<style>
    .services{
        margin-top: 0px;
        background-color: #ebedea;
    }

    .status-section{
        background-color: black;
        border-radius: 17px;
        padding: 20px;
    }

    .total {
        font-weight: bold;
        font-size: 19px;
        color: white;
    }

    .status-section-header{
        color: white;
    }
</style>

@section('section')
    <section class="services spad">
        <div class="container">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10" align="center">
                    <h3 style="color:black">- Status Transaksi -</h3>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="row status-section">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="services__item">
                                <i class="icon_pause_alt2"></i>
                                <h5 class="status-section-header">Belum Dikirim</h5>
                                <p class="total">[ {{ $data->notSend }} Transaksi ]</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="services__item">
                                <i class="fa fa-send"></i>
                                <h5 class="status-section-header">Sudah Dikirim</h5>
                                <p class="total">[ {{ $data->alreadySend }} Transaksi ]</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="services__item">
                                <i class="icon_check"></i>
                                <h5 class="status-section-header">Selesai</h5>
                                <p class="total">[ {{ $data->finish }} Transaksi ]</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </section>
@endsection