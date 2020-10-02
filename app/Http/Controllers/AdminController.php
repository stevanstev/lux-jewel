<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ttransaction;

use App\Transaction;

use App\Product;

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

    public function updatePengiriman(Request $request) {
        Validator::make($request->all(), 
        [
            'biaya_pengiriman' => 'required|numeric',
            'kurir' => 'required'
        ], 
        [
            'biaya_pengiriman.required' => 'Biaya pengiriman harus diisi',
            'biaya_pengiriman.numeric' => 'Format biaya pengiriman salah',
            'kurir.required' => 'Kurir harus diisi'
        ])->validate();

        $biaya_pengiriman = $request->input('biaya_pengiriman');
        $kurir = $request->input('kurir');
        $id = $request->input('id');

        $model = Ttransaction::find($id);
        $model->biaya_kirim = $biaya_pengiriman;
        $model->status_pesanan = 1;
        $model->kurir = $kurir;
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
        return view('/auth/admin/history');
    }
}
