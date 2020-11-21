<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Produk;

class ProductController extends Controller
{
    public function authIndex() {
        return view('auth/product');
    }

    public function details($id) {
        $products = Produk::find($id);
        $related = Produk::where('kategori', $products->kategori)->where('id', 'not like', $id)->take(4)->get();

        return view('/guest/detail_page', ['products' => $products, 'related' => $related]);
    }
}
