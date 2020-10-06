<div class="container-fluid" id="wrapper">
    <div class="container-fluid mt-100">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body cart">
                        <div class="col-sm-12 empty-cart-cls text-center"> <img src="{{ url('img/others') }}/{{ str_replace(' ','%20',$image) }}" width="100" height="100" class="img-fluid mb-4 mr-3">
                            <h3><strong>{{ $leading }}</strong></h3>
                            <h4>{{ $sub_leading }}</h4> <a href='{{ url("/$target") }}' class="btn btn-primary cart-btn-transform m-3" data-abc="true">{{ $button_text }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

<style>
    @import url(http://fonts.googleapis.com/css?family=Calibri:400,300,700);

    #wrapper {
        background-color: #eee;
        font-family: 'Calibri', sans-serif !important
    }

    .mt-100 {
        margin-top: 20px;
        padding-top: 20px;
    }

    .card {
        margin-bottom: 30px;
        border: 0;
        -webkit-transition: all .3s ease;
        transition: all .3s ease;
        letter-spacing: .5px;
        border-radius: 8px;
        -webkit-box-shadow: 1px 5px 24px 0 rgba(68, 102, 242, .05);
        box-shadow: 1px 5px 24px 0 rgba(68, 102, 242, .05)
    }

    .card .card-header {
        background-color: #fff;
        border-bottom: none;
        padding: 24px;
        border-bottom: 1px solid #f6f7fb;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px
    }

    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0
    }

    .card .card-body {
        padding: 30px;
        background-color: transparent
    }

    .btn-primary,
    .btn-primary.disabled,
    .btn-primary:disabled {
        background-color: #4466f2 !important;
        border-color: #4466f2 !important
    }
</style>