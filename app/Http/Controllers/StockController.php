<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Produk;
use App\Warna;
use App\Pengirim;
use App\Kategori;
use App\Bahan;
use App\Stock;

use App\Notif;

use Auth;


use Illuminate\Support\Facades\Validator;

class StockController extends GeneralController
{
    public function index() {
        $products = Produk::paginate(10);
        $isNotif = parent::getNotif();

        return view('auth/admin/stock', ['isNotif' => $isNotif, 'products' => $products]);
    }

    public function add(Request $request) {
        Validator::make($request->all(), 
        [
            'total_stock'=>'required',
        ], 
        [
            'total_stock.required' => 'Total stock tidak boleh kosong',
        ])->validate();

        $total_stock = $request->input('total_stock');
        $id_produk = $request->input('id_produk');

        $stock = Stock::where('product_id', '=', $id_produk)->first();
        $stock->total_stok = $total_stock;
        $stock->save();
        
        return redirect('/stock');
    }

    public function fetchItemDetails($id) {
        $details = Stock::where('product_id','=',$id)->first();
        
        return response()->json(array(
            'statusCode' => 200,
            'totalStock' => $details->total_stok,
            'lastUpdate' => $details->updated_at,
        ));
    }
}
