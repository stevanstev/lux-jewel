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

class StuffController extends GeneralController
{
    public function index() {
        $products = Produk::paginate(10);

        return view('auth/admin/stuff', ['isNotif' => $isNotif, 'products' => $products]);
    }

    public function add() {
        $colors = Warna::all();
        $categories = Kategori::all();
        $senders = Pengirim::all();
        $bahans = Bahan::all();

        return view('auth/admin/add_stuff', ['isNotif' => parent::getNotif(), 'colors' => $colors, 'categories' => $categories, 'senders' => $senders, 'bahans' => $bahans]);
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
                'bahan'=>'required',
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
                'bahan.required' => 'bahan harus diisi',
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
        $bahan = $request->input('bahan');

        $foto->move("img/product/",$foto->getClientOriginalName());

        $model = new Produk;
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        $model->foto = $foto->getClientOriginalName();
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = $colors;
        $model->kategori = $kategori;
        $model->bahan = $bahan;
        $model->save();

        $stock = new Stock;
        $stock->product_id = $model->id;
        $stock->total_stok = $stok;
        $stock->save();

        return redirect('/stuff');
    }

    public function update($id) {
        $product = Produk::find($id);
        $colors = Warna::all();
        $categories = Kategori::all();
        $checkedColor = $product->color;
        $checkedBahan = $product->bahan;
        $bahans = Bahan::all();
        $stocks = Stock::where('product_id', '=', $id)->first();

        $props = array(
            'isNotif' => parent::getNotif(),
            'products' => $product, 
            'stocks' => $stocks,
            'colors' => $colors, 
            'categories' => $categories, 
            'checkedColor' => $checkedColor,
            'checkedBahan' => $checkedBahan,
            'bahans' => $bahans,
        );

        return view('auth/admin/update_stuff', $props);
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
            'bahan'=>'required',
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
            'bahan.required'=> 'bahan harus diisi',
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
        $bahan = $request->input('bahan');

        $model = Produk::find($id);
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        if($foto != NULL){
    		$model->foto = $foto->getClientOriginalName();
    		$foto->move('img/product/',$foto->getClientOriginalName());
    	}
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = $colors;
        $model->kategori = $kategori;
        $model->bahan = $bahan;
        $model->save();

        $stock = Stock::where('product_id', '=', $id)->first();
        $stock->total_stok = $stok;
        $stock->save();

        return redirect('/stuff');
    }

    public function delete(Request $request) {
        $id = $request->input('id');
        $product = Produk::find($id);
        $product->delete();

        return redirect('/stuff');
    }
}
