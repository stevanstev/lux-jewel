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
    Route::get('/contact', 'ContactController@index');
    
    Route::get('/about', 'AboutController@index');
    //Guest
    Route::group(['middleware' => 'guest'], function() {
        Route::get('/', 'IndexController@index');
    
        Route::get('/product', 'ProductController@index');
    
        Route::get('/login', 'LoginController@index')->name('login');
        Route::post('/login-action', 'LoginController@action');
    
        Route::get('/register', 'RegisterController@index');
        Route::post('/register-action', 'RegisterController@action');
        
        Route::get('/shop', 'ShopController@index');
        
        Route::get('/checkout', 'CheckoutController@index');

        Route::get('/product-details/{id}', 'ProductController@details');

        Route::post('/search-prod', 'IndexController@searchProd');
    });
    
    //Auth
    Route::group(['middleware' => 'auth'], function() {
        //Admin
        Route::group(['middleware' => 'admin'], function() {
            Route::get('/admin-dash', 'AdminController@index');

            Route::get('/stock', 'StockController@index');
            Route::get('/tambah-stock', 'StockController@add');
            Route::post('/tambah-action', 'StockController@addAction');
            Route::get('/update-stock/{id}', 'StockController@update');
            Route::post('/update-stock-action', 'StockController@updateAction');
            Route::post('/delete-stock', 'StockController@delete');
            Route::post('/search-stock', 'StockController@search');

            Route::get('/order', 'AdminController@order');
            Route::get('/tambah-detail/{id}', 'AdminController@tambahDetail');
            Route::post('/update-transaksi', 'AdminController@updateTransaksi');

            Route::get('/konfirmasi-bayar/{id}', 'AdminController@konfirmasiBayar');
            Route::post('/konfirmasi-bayar-action', 'AdminController@konfirmasiBayarAction');

            Route::post('/selesai-transaksi', 'AdminController@selesaiTransaksi');

            Route::get('/history', 'AdminController@history');
            Route::post('/search-history', 'AdminController@searchHistory');

            Route::post('/search-order', 'AdminController@searchOrder');

            Route::get('/prediction', 'PredictionController@index');
            Route::post('/predict-action', 'PredictionController@predict');

            Route::get('/variations', 'AdminController@variations');
            Route::post('/add-color', 'AdminController@addColor');
            Route::post('/add-kategori', 'AdminController@addCategorie');
            Route::post('/add-sender', 'AdminController@addSender');

            Route::get('/delete-notifs/{id}', 'AdminController@deleteNotif');
            Route::get('/mark-notifs/{id}', 'AdminController@markNotif');

            Route::get('/pdf-predict-generate', 'AdminController@pdfGeneratePredict');
            Route::get('/pdf-reporting-generate', 'AdminController@pdfGenerateReport');

            Route::get('/laporan-penjualan', 'AdminController@laporanPenjualan');
            Route::get('/laporan-prediksi', 'AdminController@laporanPrediksi');

            Route::get('/p-not-found', 'AdminController@pNotFound');
        });
        
        //Customer
        Route::group(['middleware' => 'member'], function() {
            Route::get('/dashboard', 'CustomerController@index');

            Route::get('/items', 'CustomerController@items');
            Route::post('/item-to-cart', 'CustomerController@itemsToCart');
            Route::post('/delete-cart-item', 'CustomerController@deleteCartItem');

            Route::get('/shop', 'CustomerController@shopCart');
            Route::post('/item-checkout', 'CustomerController@itemCheckout');

            Route::get('/transactions', 'CustomerController@transactions');
            Route::get('/upload-bukti/{id}', 'CustomerController@uploadBukti');
            Route::post('/upload-bukti-action', 'CustomerController@uploadBuktiAction');

            Route::post('/konfirmasi-sampai', 'CustomerController@konfirmasiSampai');

            Route::get('/item-details/{id}', 'CustomerController@itemDetails');

            Route::post('/search-product', 'CustomerController@searchProduct');

            Route::get('/delete-notif/{id}', 'CustomerController@deleteNotif');
            Route::get('/mark-notif/{id}', 'CustomerController@markNotif');
        });
    
        //Other
        Route::get('/notifikasi', 'GeneralController@notifikasi');
        Route::get('/user-profile', 'GeneralController@userProfile');
        Route::post('/update-user-action', 'GeneralController@updateUser');
        Route::get('/home','GeneralController@redirection');
        Route::get('/logout', 'GeneralController@logout');
    });
});