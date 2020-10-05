<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

class IndexController extends Controller
{
    public function index() {
        return view('guest/index');
    }

    public function searchProd(Request $request) {
        $query = $request->input('search');

        $products = Product::where('nama_produk', $query)->orWhere('nama_produk', 'like', '%'.$query.'%')->paginate(10);

        return view('guest/product', ['products' => $products]);
    }
}
