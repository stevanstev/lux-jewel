<section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">    
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__content">
                        <div class="contact__address">
                            <h5>Sign In</h5>
                        </div>
                        <div class="contact__form">
                            <form action="{{ url('/login-action') }}" method="post" enctype="multipart/form-data">
                                <input type="text" hidden name="_token" value="{{ csrf_token() }}"/>

                                <input value="{{ old('email') }}" type="text" placeholder="Email" name="email">
                                <p style="color:red;">{{ $errors->first('email') }}</p>
                                
                                <input type="password" placeholder="Password" name="password">
                                <p style="color:red;">{{ $errors->first('password') }}</p>

                                <button type="submit" class="site-btn">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                </div>
            </div>
        </div>
    </div>
</section>