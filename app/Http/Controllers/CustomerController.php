<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Cart;

use App\Sender;

use App\Product;

use App\Ttransaction;

use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index() {
        return view('/auth/customer/home');
    }

    public function items() {
        $products = Product::paginate(10);

        return view('/auth/customer/products', ['products' => $products]);
    }

    public function searchProduct(Request $request) {
        $query = $request->input('search');

        $products = Product::where('nama_produk', $query)->orWhere('nama_produk', 'like', '%'.$query.'%')->paginate(10);

        return view('/auth/customer/products', ['products' => $products]);
    }

    public function itemsToCart(Request $request) {
        $id_produk = $request->input('id');
        $stok = (int)$request->input('stok');
        Validator::make($request->all(), 
        [
            "jumlah$id_produk" => "required|not_in:0|numeric|max:$stok"
        ], 
        [
            "jumlah$id_produk.required" => 'Jumlah tidak boleh kosong',
            "jumlah$id_produk.not_in" => 'Jumlah tidak boleh kosong',
            "jumlah$id_produk.numeric" => 'Format jumlah harus angka',
            "jumlah$id_produk.max" => 'Jumlah tidak bisa melebihi stok yang ada'
        ])->validate();

        $id_user = Auth::user()->id;
        
        $jumlah = $request->input("jumlah$id_produk");
        $foto = $request->input('foto');
        $nama_produk = $request->input('nama_produk');
        $berat_produk = $request->input('berat_produk');
        $harga_produk = $request->input('harga_produk');
        $deskripsi = $request->input('deskripsi');
        $color = $request->input('color');
        $kategori = $request->input('kategori');
        $total_harga = $jumlah * $harga_produk;

        $model = new Cart();
        $model->id_user = $id_user;
        $model->id_produk = $id_produk;
        $model->nama_produk = $nama_produk;
        $model->berat_produk = $berat_produk;
        $model->harga_produk = $harga_produk;
        $model->deskripsi = $deskripsi;
        $model->color = $color;
        $model->kategori = $kategori;
        $model->jumlah = $jumlah;
        $model->total_harga = $total_harga;
        $model->foto = $foto;
        $model->save();

        return redirect('/shop');
    }

    public function shopCart() {
        $carts = Cart::where('id_user', Auth::user()->id)->get();
        $jsonItems = json_encode($carts);
        $sender = Sender::all();

        return view('/auth/customer/carts', ['carts' => $carts, 'jsonItems' => $jsonItems,'sender' => $sender]);
    }

    public function deleteCartItem(Request $request) {
        $id = $request->input('item_id');
        $model = Cart::find($id);
        $model->delete();

        return redirect('/shop');
    }

    public function itemCheckout(Request $request) {
        Validator::make($request->all(), 
        [
            'kurir-final' => 'required',
            'nama-kurir' => 'required',
        ], 
        [
            'kurir-final.required' => 'Mohon isi Kurir',
            'nama-kurir.required' => 'Mohon isi Kurir',
        ])->validate();
        
        $harga_kurir = $request->input('kurir-final');
        $nama_kurir = $request->input('nama-kurir');
        $user_id = Auth::user()->id;
        $items = $request->input('jsonItems');
        $kota_penerima = Auth::user()->kota;
        $provinsi_penerima = Auth::user()->provinsi;
        $total_transaksi = $request->input('total_transaksi');
        $total_transaksi = $total_transaksi + $harga_kurir;
        $tgl_transaksi = Date('Y-i-s H:m:d');
        $kode_pos_p = Auth::user()->kode_pos;
        $kelurahan_p = Auth::user()->kelurahan;
        $nama_penerima = Auth::user()->nama_lengkap;
        $no_telepon = Auth::user()->no_hp;
        $alamat_penerima = Auth::user()->alamat;

        $model = new Ttransaction;
        $model->kota_penerima = $kota_penerima;
        $model->id_user = $user_id;
        $model->provinsi_penerima = $provinsi_penerima;
        $model->total_transaksi = $total_transaksi;
        $model->tgl_transaksi = $tgl_transaksi;
        $model->kode_pos_p = $kode_pos_p;
        $model->kelurahan_p = $kelurahan_p;
        $model->nama_penerima = $nama_penerima;
        $model->no_telepon = $no_telepon;
        $model->status_pesanan = 0;
        $model->alamat_penerima = $alamat_penerima;
        $model->items = $items;
        $model->kurir = $nama_kurir;
        $model->biaya_kirim = $harga_kurir;
        $model->save();

        $deleteCart = Cart::where('id_user', $user_id);
        $deleteCart->delete();

        return redirect('/transactions');
    }

    public function transactions() {
        $data = Ttransaction::paginate(10);

        return view('/auth/customer/transactions', ['data' => $data]);
    }

    public function uploadBukti($id) {
        $data = Ttransaction::find($id);

        return view('/auth/customer/upload_bukti', ['data' => $data]);
    }

    public function uploadBuktiAction(Request $request) {
        Validator::make($request->all() ,
        [
            'bukti' => 'required|image'
        ],
        [
            'bukti.required' => 'bukti harus diupload',
            'bukti.image' => 'format bukti harus berupa gambar'
        ])->validate();

        $bukti = $request->file('bukti');
        $bukti->move("img/proves/",$bukti->getClientOriginalName());

        $id = $request->input('id');

        $model = Ttransaction::find($id);
        $model->bukti_pembayaran = $bukti->getClientOriginalName();
        $model->status_pesanan = 2;
        $model->save();

        return redirect('/transactions');
    }

    public function konfirmasiSampai(Request $request) {
        $id = $request->input('id');
        $model = Ttransaction::find($id);
        $model->status_pesanan = 4;
        $model->save();

        return redirect('/transactions');
    }

    public function itemDetails($id) {
        $products = Product::find($id);
        $related = Product::where('kategori', $products->kategori)->where('id', 'not like', $id)->take(4)->get();

        return view('/auth/customer/detail_page', ['products' => $products, 'related' => $related]);
    }
}
