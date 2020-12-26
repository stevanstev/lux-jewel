<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Produk;

use DB;

class ProductController extends Controller
{
    public function authIndex() {
        return view('auth/product');
    }

    public function details($id) {
        $findProduk = Produk::find($id);
        $products = DB::table('produks')
        ->join('stocks', 'produks.id', '=', 'stocks.product_id')
        ->select('produks.*', 'stocks.total_stok')
        ->where('produks.id','=',$id)
        ->first();
        $related = Produk::where('kategori', $findProduk->kategori)->where('id', 'not like', $id)->take(4)->get();

        return view('/guest/detail_page', ['products' => $products, 'related' => $related]);
    }

    public function add() {
        return view('/auth/admin/add_product');
    }

    public function index() {
        return view('/auth/admin/add_product');
    }
}
