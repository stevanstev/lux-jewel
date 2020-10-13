<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Product;

use App\Transaction;

use App\Ttransaction;

class CustomController extends GeneralController
{
    public function searchMap($page, $search) {
        $data = function($v, $s, $n = null, $t = false) {
            $d = new \StdClass();
            $d->view = $v;
            $d->search = $s;
            $d->notif = $n;
            $d->toggle = $t;

            return $d;
        };
        
        switch($page){
            // g for guest, a for auth, c for customer
            case 'g-search-prod':
            case 'g-product':
                $data = $data('/guest/product', Product::where('nama_produk', $search)->orWhere('nama_produk', 'like', '%'.$search.'%')->paginate(10));   
                break;
            case 'a-search-stock':
            case 'a-stock':
                // check if product table is not empty
                $toggle = ($page == 'a-search-stock') ? true : false;
                $data = $data('/auth/admin/stock', Product::where('nama_produk', $search)->orWhere('nama_produk', 'like', '%' . $search . '%')->paginate(10), parent::getNotif(), $toggle);   
                break;
            case 'a-search-history':
            case 'a-history':
                // check if product table is not empty
                $toggle = ($page == 'a-search-history') ? true : false;
                $data = $data('/auth/admin/history', Transaction::where('kota_penerima', $search)->orWhere('kota_penerima', 'like', '%' . $search . '%')->paginate(10), parent::getNotif(), $toggle);   
                break;
            case 'a-search-order':
            case 'a-order':
                // check if product table is not empty
                $toggle = ($page == 'a-search-order') ? true : false;
                $data = $data('/auth/admin/order', Ttransaction::where('nama_penerima', $search)->orWhere('nama_penerima', 'like', '%' . $search . '%')->paginate(10), parent::getNotif(), $toggle);   
                break;
            case 'c-search-items':
            case 'c-items':
                // check if product table is not empty
                $toggle = ($page == 'c-search-items') ? true : false;
                $data = $data('/auth/customer/products', Product::where('nama_produk', $search)->orWhere('nama_produk', 'like', '%'.$search.'%')->paginate(10), parent::getNotif(), $toggle);   
                break;
            default:
                break;
        }

        return $data;
    }

    public function search(Request $request) {
        $search = $request->input('search');
        $path = Route::currentRouteName();
        $result = $this->searchMap($path, $search);

        return view($result->view, ['results' => $result->search, 'isNotif' => $result->notif, 'toggle' => $result->toggle]);
    }
}
