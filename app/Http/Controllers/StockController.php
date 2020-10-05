<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Color;
use App\Sender;
use App\Categorie;

use App\Notif;

use Auth;


use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    //
    public function getNotif() {   
        $isNotif = Notif::where('user_id', Auth::user()->id)->where('notif_active', 1)->first();

        if($isNotif) {
            return true;
        }
        
        return false;
    }

    public function index() {
        $isNotif = $this->getNotif();
        $products = Product::paginate(10);

        return view('auth/admin/stock', ['isNotif' => $isNotif, 'products' => $products]);
    }

    public function add() {
        $isNotif = $this->getNotif();
        $colors = Color::all();
        $categories = Categorie::all();
        $senders = Sender::all();

        return view('auth/admin/add_stock', ['isNotif' => $isNotif, 'colors' => $colors, 'categories' => $categories, 'senders' => $senders]);
    }

    public function addAction(Request $request) {
        Validator::make($request->all(), 
            [
                'nama_produk'=>'required',
                'berat_produk'=>'required|numeric',
                'foto'=>'required',
                'stok'=>'required|numeric',
                'harga_produk'=>'required',
                'deskripsi'=>'required',
                'color'=>'required',
                'kategori'=>'required',
            ], 
            [
                'nama_produk.required' => 'nama produk tidak boleh kosong',
                'berat_produk.required' => 'berat produk tidak boleh kosong',
                'berat_produk.numeric' => 'format berat harus benar',
                'foto.required' => 'foto harus diisi',
                'stok.required' => 'stok harus diisi',
                'stok.numeric' => 'stok harus angka',
                'harga_produk.required' => 'harga produk harus diisi',
                'deskripsi.required' => 'deskripsi harus diisi',
                'color.required' => 'color tidak boleh kosong',
                'kategori.numeric' => 'kategori harus diisi',
            ]
        )->validate();

        $nama_produk = $request->input('nama_produk');
        $berat_produk = $request->input('berat_produk');
        $foto = $request->file('foto'); 
        $stok = $request->input('stok');
        $harga_produk = $request->input('harga_produk');
        $deskripsi = $request->input('deskripsi');
        $color = $request->input('color');
        $kategori = $request->input('kategori');

        $foto->move("img/product/",$foto->getClientOriginalName());

        $model = new Product;
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        $model->foto = $foto->getClientOriginalName();
        $model->stok = $stok;
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = $color;
        $model->kategori = $kategori;
        $model->save();

        return redirect('/stock');
    }

    public function update($id) {
        $isNotif = $this->getNotif();
        $product = Product::find($id);
        $colors = Color::all();
        $categories = Categorie::all();

        return view('auth/admin/update_stock', ['isNotif' => $isNotif,'products' => $product, 'colors' => $colors, 'categories' => $categories]);
    }

    public function updateAction(Request $request) {
        Validator::make($request->all(), 
        [
            'nama_produk'=>'required',
            'berat_produk'=>'required|numeric',
            'stok'=>'required|numeric',
            'harga_produk'=>'required',
            'deskripsi'=>'required',
            'color'=>'required',
            'kategori'=>'required',
        ], 
        [
            'nama_produk.required' => 'nama produk tidak boleh kosong',
            'berat_produk.required' => 'berat produk tidak boleh kosong',
            'berat_produk.numeric' => 'format berat harus benar',
            'stok.required' => 'stok harus diisi',
            'stok.numeric' => 'stok harus angka',
            'harga_produk.required' => 'harga produk harus diisi',
            'deskripsi.required' => 'deskripsi harus diisi',
            'color.required' => 'color tidak boleh kosong',
            'kategori.required' => 'kategori harus diisi',
        ])->validate();

        $id = $request->input('id');
        $nama_produk = $request->input('nama_produk');
        $berat_produk = $request->input('berat_produk');
        $foto = $request->file('foto'); 
        $stok = $request->input('stok');
        $harga_produk = $request->input('harga_produk');
        $deskripsi = $request->input('deskripsi');
        $color = $request->input('color');
        $kategori = $request->input('kategori');

        $model = Product::find($id);
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        if($foto != NULL){
    		$model->foto = $foto->getClientOriginalName();
    		$foto->move('img/product/',$foto->getClientOriginalName());
    	}
        $model->stok = $stok;
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = $color;
        $model->kategori = $kategori;
        $model->save();

        return redirect('/stock');
    }

    public function delete(Request $request) {
        $id = $request->input('id');
        $product = Product::find($id);
        $product->delete();

        return redirect('/stock');
    }

    public function search(Request $request) {
        $isNotif = $this->getNotif();
        $item = $request->input('item');

        $products = Product::where('nama_produk', $item)->orWhere('nama_produk', 'like', '%' . $item . '%')->paginate(10);

        return view('auth/admin/stock', ['products' => $products, ['isNotif' => $isNotif]]);
    }
}
