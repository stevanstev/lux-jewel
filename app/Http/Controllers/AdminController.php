<?php
//to do, waktu konfirmasi bukti, harus ada tombol cancel
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ttransaction;

use App\Transaction;

use DB;

use App\Product;

use Auth;

use PDF;

use App\Color;

use App\Detail;

use App\Prediction;

use App\Sender;

use App\Categorie;

use App\Notif;

use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function getNotif() {   
        $isNotif = Notif::where('user_id', Auth::user()->id)->where('notif_active', 1)->first();

        if($isNotif) {
            return true;
        }
        
        return false;
    }

    public function deleteNotif($id) {
        $model = Notif::find($id);
        if ($model->user_id == Auth::user()->id) {
            $model->delete();
            return redirect('/notifikasi');
        } else {
            return redirect('/notifikasi');
        }
    }

    public function markNotif($id) {
        $model = Notif::find($id);
        if ($model->user_id == Auth::user()->id) {
            $model->notif_active = 0;
            $model->save();

            return redirect('/notifikasi');
        } else {
            return redirect('/notifikasi');
        }
    }

    public function index() {
        $isNotif = $this->getNotif();
        return view('/auth/admin/home', ['isNotif' =>$isNotif]);
    }

    public function order() {
        $data = Ttransaction::paginate(10);
        $isNotif = $this->getNotif();

        return view('/auth/admin/order',['data' => $data, 'isNotif' =>$isNotif]);
    }

    public function tambahDetail($id) {
        $data = Ttransaction::find($id);
        $isNotif = $this->getNotif();

        return view('/auth/admin/update_transcation', ['data' => $data, 'isNotif' =>$isNotif]);
    }

    public function updateTransaksi(Request $request) {
        $id = $request->input('id');
        $choose = $request->input('konfirmasi');
        $reason = $request->input('alasan');
        $finalRes = ($reason != "") ? "dengan alasan $reason" : "";

        if ($choose == "batal") {
            $model = Ttransaction::find($id);
            $notif = new Notif();
            $notif->message = "Transaksi Dibatalkan oleh admin $finalRes";
            $notif->user_id = $model->id_user;
            $notif->notif_active = 1;
            $notif->save();

            $model->delete();

        } else {
            $model = Ttransaction::find($id);
            $model->status_pesanan = 1;
            $model->save();

            $notif = new Notif();
            $notif->message = "Silahkan Upload Bukti Pembayaran untuk transaksi dengan ID: $model->id";
            $notif->user_id = $model->id_user;
            $notif->notif_active = 1;
            $notif->save();
        }

        return redirect('/order');
    }

    public function konfirmasiBayar($id) {
        $data = Ttransaction::find($id);
        $isNotif = $this->getNotif();

        return view('/auth/admin/konfirmasi_bayar', ['data' => $data, 'isNotif' =>$isNotif]);
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

        $notif = new Notif();
        $notif->message = "Transaksi Diterima, Nomor resi anda adalah $nomor_resi";
        $notif->user_id = $model->id_user;
        $notif->notif_active = 1;
        $notif->save();

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
        $isNotif = $this->getNotif();

        return view('/auth/admin/history', ['t' => $t, 'isNotif' =>$isNotif]);
    }

    public function variations(){
        $isNotif = $this->getNotif();
        return view('/auth/admin/variations', ['isNotif' =>$isNotif]);
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
        $isNotif = $this->getNotif();
        $t = Transaction::where('kota_penerima', $search)->orWhere('kota_penerima', 'like', '%' . $search . '%')->paginate(10);

        return view('/auth/admin/history', ['t' => $t, 'isNotif' =>$isNotif]);
    }

    public function prediction() {
        $isNotif = $this->getNotif();
        return view('/auth/admin/prediction', ['isNotif' =>$isNotif]);
    }

    public function pdfGeneratePredict(Request $request) {
        $getNama = $request->query('nama_produk');
        $getPredict = Prediction::where('hasil', 'like', $getNama.'%')->get();

        if (count($getPredict) == 0) {
            return redirect('/p-not-found');
        } else {
            $pdf = PDF::loadView('template/pdf_predict_html',['predicts' => $getPredict, 'nama' => $getNama]);

            return $pdf->download('prediksi.pdft');
        }
    }

    public function pNotFound() {
        $isNotif = $this->getNotif();

        return view('/auth/admin/predict_empty', ['isNotif' => $isNotif]);
    }

    public function pdfGenerateReport(Request $request) {
        $getPeriode = $request->query('periode');
        $periode = explode('-', $getPeriode);
        $p_month = ($periode[1] < 10) ? '0'.$periode[1] : $periode[1]; 
        $p_year = $periode[0];
        $concat_p = $p_year.'-'.$p_month;
        $getProduct = Product::all();
        $obj = new \StdClass();
        $finalData = array();
        foreach ($getProduct as $p) {
            $nama_produk = $p->nama_produk;
            $getDetails = DB::select("SELECT SUM(qty) as qty FROM details WHERE nama_produk='$nama_produk' and created_at like '$concat_p%'");
            $obj->id = $p->id;
            $obj->nama_produk = $nama_produk;
            $obj->qty = (empty($getDetails[0]->qty) ? 0 : $getDetails[0]->qty);
            $obj->berat_produk = $p->berat_produk;
            $obj->harga_produk = $p->harga_produk;
            $obj->color = $p->color;
            $obj->kategori = $p->kategori;

            array_push($finalData, $obj);
            $obj = new \StdClass();
            $nama_produk = "";
        }

        $pdf = PDF::loadView('template/pdf_reporting_html', ['data' => $finalData, 'datetime' => $getPeriode]);

        return $pdf->download('penjualan.pdft');
    }

    public function laporanPenjualan(){
        $isNotif = $this->getNotif();
        $getPeriod = DB::select('SELECT DISTINCT YEAR(created_at) AS "year", MONTH(created_at) as "month" FROM ttransactions');
        $enumDate = array();

        foreach($getPeriod as $gp) {
            array_push($enumDate, $gp->year.'-'.$gp->month);
        }

        return view('/auth/admin/laporan_penjualan', ['isNotif' =>$isNotif, 'periodic' => $enumDate]);
    }

    public function laporanPrediksi(){
        $isNotif = $this->getNotif();
        $getProduct = Product::all();

        return view('/auth/admin/laporan_prediksi', ['isNotif' => $isNotif, 'products' => $getProduct]);
    }

    public function searchOrder(Request $request) {
        $search = $request->input('item');
        $isNotif = $this->getNotif();
        $data = Ttransaction::where('nama_penerima', $search)->orWhere('nama_penerima', 'like', '%' . $search . '%')->paginate(10);

        return view('/auth/admin/order', ['data' => $data, 'isNotif' =>$isNotif]);
    }
}
