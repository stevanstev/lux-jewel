<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Cart;

use DB;

use App\Pengirim;

use App\Produk;

use App\Pembayaran;

use App\Customer;

use App\Notif;

use App\Stock;

use Illuminate\Support\Facades\Validator;

class CustomerController extends GeneralController
{
    public function index() {
        return view('/auth/customer/home',['isNotif' => parent::getNotif()]);
    }

    public function itemsToCart(Request $request) {
        $id_produk = $request->input('id');
        $total_stok = (int)$request->input('total_stok');
        Validator::make($request->all(), 
        [
            "jumlah$id_produk" => "required|not_in:0|numeric|max:$total_stok"
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
        $bahan = $request->input('bahan');
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
        $model->bahan = $bahan;
        $model->save();

        return redirect('/shop');
    }

    public function shopCart() {
        $carts = Cart::where('id_user', Auth::user()->id)->get();
        $jsonItems = json_encode($carts);
        $sender = Pengirim::all();

        return view('/auth/customer/carts', ['isNotif' => parent::getNotif(),'carts' => $carts, 'jsonItems' => $jsonItems,'sender' => $sender]);
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
        $tgl_transaksi = Date('Y-m-d H:i:s');
        $kode_pos_p = Auth::user()->kode_pos;
        $kelurahan_p = Auth::user()->kelurahan;
        $nama_penerima = Auth::user()->nama_lengkap;
        $no_telepon = Auth::user()->no_hp;
        $alamat_penerima = Auth::user()->alamat;

        $model = new Pembayaran;
        $model->kota_penerima = $kota_penerima;
        $model->id_user = $user_id;
        $model->provinsi_penerima = $provinsi_penerima;
        $model->total_transaksi = $total_transaksi;
        $model->tgl_transaksi = $tgl_transaksi;
        $model->kode_pos_p = $kode_pos_p;
        $model->kelurahan_p = $kelurahan_p;
        $model->nama_penerima = $nama_penerima;
        $model->no_telepon = $no_telepon;
        $model->status_pesanan = 1;
        $model->alamat_penerima = $alamat_penerima;
        $model->items = $items;
        $model->kurir = $nama_kurir;
        $model->biaya_kirim = $harga_kurir;
        $model->save();

        $getAdmin = Customer::where('role', 1)->first();

        $notif = new Notif();
        $notif->message = "New Order from ". Auth::user()->nama_lengkap;
        $notif->user_id = $getAdmin->id;
        $notif->notif_active = 1;
        $notif->save();

        $deleteCart = Cart::where('id_user', $user_id);
        $deleteCart->delete();

        return redirect('/transactions');
    }

    public function transactions() {
        $data = Pembayaran::where('id_user', Auth::user()->id)->paginate(10);

        return view('/auth/customer/transactions', ['isNotif' => parent::getNotif(), 'results' => $data]);
    }

    public function uploadBukti($id) {
        $data = Pembayaran::find($id);

        return view('/auth/customer/upload_bukti', ['isNotif' => parent::getNotif(),'data' => $data]);
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

        $items = json_decode($request->input('items'));

        foreach($items as $key => $value) {
            //flag to be fixing
            $model = Stock::where('product_id','=',$value->id_produk)->first();
            $model->total_stok = $model->total_stok - $value->jumlah;
            $model->save();
        }

        $nama_penerima = $request->input('nama_penerima');
        $kota_penerima = $request->input('kota_penerima');
        $provinsi_penerima = $request->input('provinsi_penerima');
        $kode_pos_p = $request->input('kode_pos_p');
        $kelurahan_p = $request->input('kelurahan_p');
        $no_telepon = $request->input('no_telepon');
        $alamat_penerima = $request->input('alamat_penerima');

        $id = $request->input('id');

        $model = Pembayaran::find($id);
        $model->bukti_pembayaran = $bukti->getClientOriginalName();
        if(!empty($nama_penerima)) {
            $model->nama_penerima = $nama_penerima;
        }

        if(!empty($kota_penerima)) {
            $model->kota_penerima = $kota_penerima;
        }

        if(!empty($provinsi_penerima)) {
            $model->provinsi_penerima = $provinsi_penerima;
        }

        if(!empty($kode_pos_p)) {
            $model->kode_pos_p = $kode_pos_p;
        }

        if(!empty($kelurahan_p)) {
            $model->kelurahan_p = $kelurahan_p;
        }

        if(!empty($no_telepon)) {
            $model->no_telepon = $no_telepon;
        }

        if(!empty($alamat_penerima)) {
            $model->alamat_penerima = $alamat_penerima;
        }
        $model->status_pesanan = 2;
        $model->save();

        $getAdmin = Customer::where('role', 1)->first();

        $notif = new Notif();
        $notif->message = "Bukti Pembayaran untuk transaksi dengan ID: ".$model->id." sudah di upload";
        $notif->user_id = $getAdmin->id;
        $notif->notif_active = 1;
        $notif->save();

        return redirect('/transactions');
    }

    public function konfirmasiSampai(Request $request) {
        $id = $request->input('id');
        $model = Pembayaran::find($id);
        $model->status_pesanan = 5;
        $model->save();

        $getAdmin = Customer::where('role', 1)->first();

        $notif = new Notif();
        $notif->message = "Transaksi dengan ID: ".$model->id." Selesai";
        $notif->user_id = $getAdmin->id;
        $notif->notif_active = 1;
        $notif->save();

        return redirect('/transactions');
    }

    public function itemDetails($id) {
        $findProduct = Produk::find($id);
        $products = DB::table('produks')
        ->join('stocks', 'produks.id', '=', 'stocks.product_id')
        ->select('produks.*', 'stocks.total_stok')
        ->where('produks.id','=',$id)
        ->first();
        $colors = $findProduct->color;
        $related = Produk::where('kategori', $findProduct->kategori)->where('id', 'not like', $id)->take(4)->get();

        return view('/auth/customer/detail_page', ['isNotif' => parent::getNotif(), 'products' => $products, 'related' => $related, 'colors' => $colors]);
    }
}
