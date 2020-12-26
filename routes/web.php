<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'web'], function() {

    //Map the searching Routes Function
    function searchRouting($g, $p) {
        ///$g means GET ROUTE
        /// index 0 for guest route
        /// index 1 for auth route
        ///example:
        ///array('a-stock', array('/stock', '/search-stock'))

        ///$p means POST ROUTE
        /// index 0 
        ///example:
        ///array('a-search-stock', '/search-stock')

        $indexGetPath = $g[1][0];
        $searchGetPath = $g[1][1];
        $routeGetName = $g[0];

        $routePostName = $p[0];
        $routePostAction = $p[1];
        Route::get($indexGetPath, 'CustomController@search')->name($routeGetName);
        Route::get($searchGetPath, 'CustomController@search')->name($routeGetName);
        Route::post($routePostAction, 'CustomController@search')->name($routePostName);
    }    
    
    Route::get('/contact', 'OthersController@contact');
    
    Route::get('/about', 'OthersController@about');
    //Guest
    Route::group(['middleware' => 'guest'], function() {
        Route::get('/', 'OthersController@index');
    
        Route::get('/login', 'VerificationController@login')->name('login');
        Route::post('/login-action', 'VerificationController@loginAction');
    
        Route::get('/register', 'VerificationController@register');
        Route::post('/register-action', 'VerificationController@registerAction');

        Route::get('/product-details/{id}', 'ProductController@details');   

        searchRouting(array('g-product', array('/product', '/search-prod')), array('g-product', '/search-prod'));
    });
    
    //Auth
    Route::group(['middleware' => 'auth'], function() {
        //Admin
        Route::group(['middleware' => 'admin'], function() {
            Route::get('/admin-dash', 'AdminController@index');

            Route::get('/fetch-items-details/{id}', 'AdminController@fetchItemDetails');

            searchRouting(array('a-stuff', array('/stuff', '/search-stuff')), array('a-search-stuff', '/search-stuff'));
            Route::get('/tambah-stuff', 'StuffController@add');
            Route::post('/tambah-action', 'StuffController@addAction');
            Route::get('/update-stuff/{id}', 'StuffController@update');
            Route::post('/update-stuff-action', 'StuffController@updateAction');
            Route::post('/delete-stuff', 'StuffController@delete');

            Route::get('/stock', 'StockController@index');
            Route::post('/tambah-stock', 'StockController@add');

            Route::get('/tambah-detail/{id}', 'AdminController@tambahDetail');
            Route::post('/update-transaksi', 'AdminController@updateTransaksi');

            Route::get('/konfirmasi-bayar/{id}', 'AdminController@konfirmasiBayar');
            Route::post('/konfirmasi-bayar-action', 'AdminController@konfirmasiBayarAction');

            Route::get('/kirim-resi/{id}', 'AdminController@kirimResi');
            Route::post('/kirim-resi-action', 'AdminController@kirimResiAction');

            Route::post('/selesai-transaksi', 'AdminController@selesaiTransaksi');

            searchRouting(array('a-prediction', array('/prediction', '/search-prediction')), array('a-search-prediction', '/search-prediction'));
            Route::get('/prediction', 'PredictionController@index');
            Route::post('/predict-action', 'PredictionController@predict');
            Route::post('/predict-by-periods', 'PredictionController@predictByPeriods');
            Route::post('/predict-by-months', 'PredictionController@predictByMonths');

            Route::get('/colors', 'AdminController@colors');
            Route::post('/color-delete', 'AdminController@deleteColor');
            Route::post('/add-color', 'AdminController@addColor');

            Route::get('/bahans','AdminController@bahans');
            Route::post('/bahan-delete', 'AdminController@deleteBahan');
            Route::post('/add-bahan', 'AdminController@addBahan');

            Route::get('/categories', 'AdminController@categories');
            Route::post('/category-delete', 'AdminController@deleteCategory');
            Route::post('/add-kategori', 'AdminController@addCategorie');

            Route::get('/senders', 'AdminController@senders');
            Route::post('/sender-delete', 'AdminController@deleteSender');
            Route::post('/add-sender', 'AdminController@addSender');

            Route::get('/delete-notifs/{id}', 'GeneralController@deleteNotif');
            Route::get('/mark-notifs/{id}', 'GeneralController@markNotif');

            Route::get('/pdf-predict-generate', 'AdminController@pdfGeneratePredict');
            Route::get('/pdf-reporting-generate', 'AdminController@pdfGenerateReport');

            Route::get('/laporan-penjualan', 'AdminController@laporanPenjualan');
            Route::get('/laporan-prediksi', 'AdminController@laporanPrediksi');

            Route::get('/p-not-found', 'AdminController@pNotFound');

            Route::get('/periods/{id}', 'PredictionController@periods');

            searchRouting(array('a-history', array('/history', '/search-history')), array('a-search-history', '/search-history'));
            Route::get('/history', 'AdminController@history');

            searchRouting(array('a-order', array('/order', '/search-order')), array('a-search-order', '/search-order'));
        });
        
        //Customer
        Route::group(['middleware' => 'member'], function() {
            Route::get('/dashboard', 'CustomerController@index');

            Route::post('/item-to-cart', 'CustomerController@itemsToCart');
            Route::post('/delete-cart-item', 'CustomerController@deleteCartItem');

            Route::get('/shop', 'CustomerController@shopCart');
            Route::post('/item-checkout', 'CustomerController@itemCheckout');

            Route::get('/transactions', 'CustomerController@transactions');
            searchRouting(array('c-transactions', array('/transactions', '/search-transactions')), array('c-search-transactions', '/search-transactions'));

            Route::get('/upload-bukti/{id}', 'CustomerController@uploadBukti');
            Route::post('/upload-bukti-action', 'CustomerController@uploadBuktiAction');

            Route::post('/konfirmasi-sampai', 'CustomerController@konfirmasiSampai');

            Route::get('/item-details/{id}', 'CustomerController@itemDetails');

            Route::get('/delete-notif/{id}', 'GeneralController@deleteNotif');
            Route::get('/mark-notif/{id}', 'GeneralController@markNotif');

            searchRouting(array('c-items', array('/items', '/search-items')), array('c-search-items', '/search-items'));
        });
    
        //Other
        Route::get('/notifikasi', 'GeneralController@notifikasi');
        Route::get('/user-profile', 'GeneralController@userProfile');
        Route::post('/update-user-action', 'GeneralController@updateUser');
        Route::get('/home','GeneralController@redirection');
        Route::get('/logout', 'GeneralController@logout');
    });
});