<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ttransaction;

use App\Transaction;

use App\Product;

use App\Color;
use App\Detail;

use App\Sender;

use App\Categorie;

use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index() {
        return view('/auth/admin/home');
    }

    public function order() {
        $data = Ttransaction::paginate(10);

        return view('/auth/admin/order',['data' => $data]);
    }

    public function tambahDetail($id) {
        $data = Ttransaction::find($id);

        return view('/auth/admin/update_transcation', ['data' => $data]);
    }

    public function updateTransaksi(Request $request) {
        $id = $request->input('id');

        $model = Ttransaction::find($id);
        $model->status_pesanan = 1;
        $model->save();

        return redirect('/order');
    }

    public function konfirmasiBayar($id) {
        $data = Ttransaction::find($id);

        return view('/auth/admin/konfirmasi_bayar', ['data' => $data]);
    }

    public function konfirmasiBayarAction(Request $request) {
        Validator::make($request->all(),
        [
            'nomor_resi' => 'required'
        ],
        [
            'nomor_resi.required' => 'Nomor resi tidak boleh kosong'
        ])->validate();

        $id = $request->input('id');
        $nomor_resi = $request->input('nomor_resi');

        $model = Ttransaction::find($id);
        $model->status_pesanan = 3;
        $model->no_resi = $nomor_resi;
        $model->save();

        return redirect('/order');
    }

    public function selesaiTransaksi(Request $request) {
        $id = $request->input('id');
        $tempTransaction = Ttransaction::find($id);
        $tempTransaction->status_pesanan = 5;
        $tempTransaction->save();

        $items = json_decode($tempTransaction->items);
        foreach($items as $key => $value) {
            $p = Product::find($value->id_produk);
            $currentStock = $p->stok;
            $p->stok = $currentStock - $value->jumlah;
            $p->save();

            $d = new Detail();
            $d->nama_produk = $value->nama_produk;
            $d->qty = $value->jumlah;
            $d->berat_produk = $value->berat_produk;
            $d->detail_harga = $value->total_harga;
            $d->predict_dt_m = Date('n');
            $d->predict_dt_y = Date('y');
            $d->save();
        }

        $kota_penerima = $tempTransaction->kota_penerima;
        $provinsi_penerima = $tempTransaction->provinsi_penerima;
        $total_transaksi = $tempTransaction->total_transaksi;
        $tgl_transaksi = $tempTransaction->tgl_transaksi;
        $kode_pos_p = $tempTransaction->kode_pos_p;
        $kelurahan_p = $tempTransaction->kelurahan_p;
        $nama_penerima = $tempTransaction->nama_penerima;
        $no_telepon = $tempTransaction->no_telepon;
        $alamat_penerima = $tempTransaction->alamat_penerima;
        $bukti_pembayaran = $tempTransaction->bukti_pembayaran;
        $no_resi = $tempTransaction->no_resi;
        $status_pesanan = 5;
        $biaya_kirim = $tempTransaction->biaya_kirim;

        $model = new Transaction;
        $model->kota_penerima = $kota_penerima;
        $model->provinsi_penerima = $provinsi_penerima;
        $model->total_transaksi = $total_transaksi;
        $model->tgl_transaksi = $tgl_transaksi;
        $model->kode_pos_p = $kode_pos_p;
        $model->kelurahan_p = $kelurahan_p;
        $model->nama_penerima = $nama_penerima;
        $model->no_telepon = $no_telepon;
        $model->alamat_penerima = $alamat_penerima;
        $model->bukti_pembayaran = $bukti_pembayaran;
        $model->no_resi = $no_resi;
        $model->status_pesanan = $status_pesanan;
        $model->biaya_kirim = $biaya_kirim;
        $model->save();

        return redirect('/history');

    }

    public function history(){
        $t = Transaction::paginate(10);

        return view('/auth/admin/history', ['t' => $t]);
    }

    public function variations(){
        return view('/auth/admin/variations');
    }

    public function addColor(Request $request) {
        Validator::make($request->all(), 
        [
            'color' => 'required'
        ], 
        [
            'color.required' => 'Warna harus diisi'
        ])->validate();

        $color = $request->input('color');
        $model = new Color();
        $model->nama_warna = $color;
        $model->save();

        return redirect()->back();
    }

    public function addCategorie(Request $request) {
        Validator::make($request->all(), 
        [
            'kategori' => 'required'
        ], 
        [
            'kategori.required' => 'Kategori harus diisi'
        ])->validate();

        $kategori = $request->input('kategori');
        $model = new Categorie();
        $model->nama_kategori = $kategori;
        $model->save();

        return redirect()->back();
    }

    public function addSender(Request $request) {
        Validator::make($request->all(), 
        [
            'sender' => 'required'
        ], 
        [
            'sender.required' => 'Pengirim harus diisi'
        ])->validate();

        $sender = $request->input('sender');
        $model = new Sender();
        $model->nama_pengirim = $sender;
        $model->save();

        return redirect()->back();
    }

    public function searchHistory(Request $request) {
        $search = $request->input('history');

        $t = Transaction::where('kota_penerima', $search)->orWhere('kota_penerima', 'like', '%' . $search . '%')->paginate(10);

        return view('/auth/admin/history', ['t' => $t]);
    }

    public function prediction() {
        return view('/auth/admin/prediction');
    }

    public function searchOrder(Request $request) {
        $search = $request->input('item');

        $data = Ttransaction::where('nama_penerima', $search)->orWhere('nama_penerima', 'like', '%' . $search . '%')->paginate(10);

        return view('/auth/admin/order', ['data' => $data]);
    }
}
