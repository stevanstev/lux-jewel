<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Produk;
use App\Warna;
use App\Pengirim;
use App\Kategori;

use App\Notif;

use Auth;


use Illuminate\Support\Facades\Validator;

class StockController extends GeneralController
{
    public function index() {
        $products = Produk::paginate(10);

        return view('auth/admin/stock', ['isNotif' => $isNotif, 'products' => $products]);
    }

    public function add() {
        $colors = Warna::all();
        $categories = Kategori::all();
        $senders = Pengirim::all();

        return view('auth/admin/add_stock', ['isNotif' => parent::getNotif(), 'colors' => $colors, 'categories' => $categories, 'senders' => $senders]);
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
                'colors'=>'required',
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
        $colors = $request->input('colors');
        $kategori = $request->input('kategori');

        $foto->move("img/product/",$foto->getClientOriginalName());

        $model = new Produk;
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        $model->foto = $foto->getClientOriginalName();
        $model->stok = $stok;
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = json_encode($colors);
        $model->kategori = $kategori;
        $model->save();

        return redirect('/stock');
    }

    public function update($id) {
        $product = Produk::find($id);
        $colors = Warna::all();
        $categories = Kategori::all();
        $checkedColor = json_decode($product->color);

        return view('auth/admin/update_stock', ['isNotif' => parent::getNotif(),'products' => $product, 'colors' => $colors, 'categories' => $categories, 'checkedColor' => $checkedColor]);
    }

    public function updateAction(Request $request) {
        Validator::make($request->all(), 
        [
            'nama_produk'=>'required',
            'berat_produk'=>'required|numeric',
            'stok'=>'required|numeric',
            'harga_produk'=>'required',
            'deskripsi'=>'required',
            'colors'=>'required',
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
        $colors = $request->input('colors');
        $kategori = $request->input('kategori');

        $model = Produk::find($id);
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        if($foto != NULL){
    		$model->foto = $foto->getClientOriginalName();
    		$foto->move('img/product/',$foto->getClientOriginalName());
    	}
        $model->stok = $stok;
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = json_encode($colors);
        $model->kategori = $kategori;
        $model->save();

        return redirect('/stock');
    }

    public function delete(Request $request) {
        $id = $request->input('id');
        $product = Produk::find($id);
        $product->delete();

        return redirect('/stock');
    }
}
