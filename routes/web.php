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
        Route::get($g[1][0], 'CustomController@search')->name($g[0]);
        Route::get($g[1][1], 'CustomController@search')->name($g[0]);
        Route::post($p[1], 'CustomController@search')->name($p[0]);
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

            Route::get('/tambah-stock', 'StockController@add');
            Route::post('/tambah-action', 'StockController@addAction');
            Route::get('/update-stock/{id}', 'StockController@update');
            Route::post('/update-stock-action', 'StockController@updateAction');
            Route::post('/delete-stock', 'StockController@delete');

            Route::get('/tambah-detail/{id}', 'AdminController@tambahDetail');
            Route::post('/update-transaksi', 'AdminController@updateTransaksi');

            Route::get('/konfirmasi-bayar/{id}', 'AdminController@konfirmasiBayar');
            Route::post('/konfirmasi-bayar-action', 'AdminController@konfirmasiBayarAction');

            Route::post('/selesai-transaksi', 'AdminController@selesaiTransaksi');

            Route::get('/prediction', 'PredictionController@index');
            Route::post('/predict-action', 'PredictionController@predict');

            Route::get('/variations', 'AdminController@variations');
            Route::post('/add-color', 'AdminController@addColor');
            Route::post('/add-kategori', 'AdminController@addCategorie');
            Route::post('/add-sender', 'AdminController@addSender');

            Route::get('/delete-notifs/{id}', 'GeneralController@deleteNotif');
            Route::get('/mark-notifs/{id}', 'GeneralController@markNotif');

            Route::get('/pdf-predict-generate', 'AdminController@pdfGeneratePredict');
            Route::get('/pdf-reporting-generate', 'AdminController@pdfGenerateReport');

            Route::get('/laporan-penjualan', 'AdminController@laporanPenjualan');
            Route::get('/laporan-prediksi', 'AdminController@laporanPrediksi');

            Route::get('/p-not-found', 'AdminController@pNotFound');

            searchRouting(array('a-stock', array('/stock', '/search-stock')), array('a-search-stock', '/search-stock'));
            searchRouting(array('a-history', array('/history', '/search-history')), array('a-search-history', '/search-history'));
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