<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas__close">+</div>
    <div class="offcanvas__logo">
        <a href="{{ url('/') }}">Lux Jewelry</a>
    </div>
    <ul class="offcanvas__widget">
        <li><span class="icon_search search-switch"></span></li>
    </ul>
    <div id="mobile-menu-wrap"></div>
    <div class="offcanvas__auth">
        @guest
            <a href="{{ url('/login') }}">Login</a>
            <a href="{{ url('/register') }}">Register</a>
        @endguest
        @auth
            <a href="{{ url('/user-profile') }}">{{ Auth::user()->nama_lengkap }}</a>
            <a href="{{ url('/notifikasi') }}">
                @if($isNotif) 
                    <span class="bg-danger"><i class="fa fa-send"></i> Notification</span>
                @else 
                    Notification
                @endif
            </a>
            <a href="{{ url('/logout') }}">Logout</a>
        @endauth
    </div>
</div>

<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-2">
                <div class="header__logo">
                    <a href="{{ url('/') }}"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7">
                <nav class="header__menu">
                    <ul>
                        @guest
                        <li class="{{ Request::path() == '/' ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>
                        <li class="{{ Request::path() == 'product' ? 'active' : '' }}"><a href="{{ url('/product') }}">Products</a></li>
                        <li class="{{ Request::path() == 'about' ? 'active' : '' }}"><a href="{{ url('/about') }}">About Us</a></li>
                        <li class="{{ Request::path() == 'contact' ? 'active' : '' }}"><a href="{{ url('/contact') }}">Contact</a></li>
                        @endguest
                        @auth
                            @if(Auth::user()->role == 1)
                                <li class="{{ Request::path() == 'admin-dash' ? 'active' : '' }}"><a href="{{ url('/admin-dash') }}">Home</a></li>
                                <li class="{{ Request::path() == 'order' ? 'active' : '' }}"><a href="{{ url('/order') }}">Order List</a></li>
                                <li class="{{ Request::path() == 'stock' ? 'active' : '' }}"><a href="{{ url('/stock') }}">Stock</a></li>
                                <li class="{{ Request::path() == 'variations' ? 'active' : '' }}"><a href="{{ url('/variations') }}">Variations</a></li>
                                <li class="{{ Request::path() == 'prediction' ? 'active' : '' }}"><a href="{{ url('/prediction') }}">Prediction</a></li>
                                <li class="{{ Request::path() == 'history' ? 'active' : '' }}"><a href="{{ url('/history') }}">History</a></li>
                            @else
                                <li class="{{ Request::path() == 'dashboard' ? 'active' : '' }}"><a href="{{ url('/dashboard') }}">Home</a></li>
                                <li class="{{ Request::path() == 'items' ? 'active' : '' }}"><a href="{{ url('/items') }}">Products</a></li>
                                <li class="{{ Request::path() == 'shop' ? 'active' : '' }}"><a href="{{ url('/shop') }}">Shop Cart</a></li>
                                <li class="{{ Request::path() == 'transactions' ? 'active' : '' }}"><a href="{{ url('/transactions') }}">Transactions</a></li>
                            @endif
                        @endauth
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="header__right">
                    <div class="header__right__auth">
                        @guest
                            <a href="{{ url('/login') }}">Login</a>
                            <a href="{{ url('/register') }}">Register</a>
                        @endguest
                        @auth
                            <a href="{{ url('/user-profile') }}">{{ Auth::user()->nama_lengkap }}</a>
                            <a href="{{ url('/notifikasi') }}">
                                @if($isNotif) 
                                    <span class="bg-danger"><i class="fa fa-send"></i> Notification</span>
                                @else 
                                    Notification
                                @endif
                            </a>
                            <a href="{{ url('/logout') }}">Logout</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>