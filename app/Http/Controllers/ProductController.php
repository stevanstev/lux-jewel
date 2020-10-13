<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

class ProductController extends Controller
{
    public function authIndex() {
        return view('auth/product');
    }

    public function details($id) {
        $products = Product::find($id);
        $related = Product::where('kategori', $products->kategori)->where('id', 'not like', $id)->take(4)->get();

        return view('/guest/detail_page', ['products' => $products, 'related' => $related]);
    }
}
