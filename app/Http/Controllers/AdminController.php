<?php
//to do, waktu konfirmasi bukti, harus ada tombol cancel
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Pembayaran;

use App\Order;

use DB;

use App\Bahan;

use App\Produk;

use Auth;

use PDF;

use App\Stock;

use App\Warna;

use App\Detailorder;

use App\Prediksi;

use App\Pengirim;

use App\Kategori;

use App\Notif;

use Illuminate\Support\Facades\Validator;

class AdminController extends GeneralController
{
    public function index() {
        $model = DB::select('select * from pembayarans limit 5');
        $data = new \StdClass;

        $notSend = 0;
        $alreadySend = 0;
        $finish = 0;

        //3 -> belum dikirim
        //4 -> sudah dikirim
        //6 -> sudah selesai

        foreach($model as $key => $value) {
            switch($value->status_pesanan){ 
                case 3:
                    $notSend++;
                    break;
                case 4:
                    $alreadySend++;
                    break;
                case 6:
                    $finish++;
                    break;
                default:
                    break;
            }
        }

        $data->notSend = $notSend;
        $data->alreadySend = $alreadySend;
        $data->finish = $finish;

        return view('/auth/admin/home', ['isNotif' => parent::getNotif(), 'data' => $data]);
    }

    public function tambahDetail($id) {
        $data = Pembayaran::find($id);

        return view('/auth/admin/update_transcation', ['data' => $data, 'isNotif' => parent::getNotif()]);
    }

    public function updateTransaksi(Request $request) {
        $id = $request->input('id');
        $choose = $request->input('konfirmasi');
        $reason = $request->input('alasan');
        $finalRes = ($reason != "") ? "dengan alasan $reason" : "";

        if ($choose == "batal") {
            $model = Pembayaran::find($id);

            $notif = new Notif();
            $notif->message = "Transaksi Dibatalkan oleh admin $finalRes";
            $notif->user_id = $model->id_user;
            $notif->notif_active = 1;
            $notif->save();

            $model->delete();

        } else {
            $model = Pembayaran::find($id);
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
        $data = Pembayaran::find($id);

        return view('/auth/admin/konfirmasi_bayar', ['data' => $data, 'isNotif' => parent::getNotif()]);
    }

    public function kirimResi($id) {
        $data = Pembayaran::find($id);

        return view('/auth/admin/kirim_resi', ['data' => $data, 'isNotif' => parent::getNotif()]);
    }

    public function kirimResiAction(Request $request) {
        Validator::make($request->all(),
        [
            'nomor_resi' => 'required'
        ],
        [
            'nomor_resi.required' => 'Nomor resi tidak boleh kosong'
        ])->validate();

        $id = $request->input('id');
        $nomor_resi = $request->input('nomor_resi');

        $model = Pembayaran::find($id);
        $model->status_pesanan = 4;
        $model->no_resi = $nomor_resi;
        $model->save();

        $notif = new Notif();
        $notif->message = "Nomor resi untuk order dengan ID $id : $nomor_resi";
        $notif->user_id = $model->id_user;
        $notif->notif_active = 1;
        $notif->save();

        return redirect('/order');
    }

    public function konfirmasiBayarAction(Request $request) {
        $id = $request->input('id');
        $konfirmasi = $request->input('konfirmasi');

        if ($konfirmasi == "true") {
            $model = Pembayaran::find($id);
            $model->status_pesanan = 3;
            $model->expired_date = "#";
            $model->save();

            $notif = new Notif();
            $notif->message = "Bukti Pembayaran diterima, mohon menunggu nomor resi";
            $notif->user_id = $model->id_user;
            $notif->notif_active = 1;
            $notif->save();
        } else {
            $model = Pembayaran::find($id);
            $items = json_decode($model->items);

            foreach($items as $key => $value) {
                //to be fixing
                $product_model = Stock::where('product_id','=',$value->id_produk)->first();
                $product_model->total_stok = $product_model->total_stok + $value->jumlah;
                $product_model->save();
            }

            $model->status_pesanan = 1;
            $model->expired_date = date('Y-m-d H:i:s', strtotime('+2 day', strtotime(date('Y-m-d H:i:s'))));
            $model->save();

            $notif = new Notif();
            $notif->message = "Bukti Pembayaran ditolak, mohon unggah bukti pembayaran yang valid";
            $notif->user_id = $model->id_user;
            $notif->notif_active = 1;
            $notif->save();
        }

        return redirect('/order');
    }

    public function selesaiTransaksi(Request $request) {
        $id = $request->input('id');
        $tempTransaction = Pembayaran::find($id);
        $tempTransaction->status_pesanan = 6;
        $tempTransaction->save();

        $items = json_decode($tempTransaction->items);
        foreach($items as $key => $value) {
            $p = Stock::where('product_id', '=', $value->id_produk)->first();
            $currentStock = $p->total_stok;
            $p->total_stok = $currentStock - $value->jumlah;
            $p->save();

            $d = new Detailorder();
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

        $model = new Order;
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

    public function bahans() {
        $data = Bahan::paginate(5);

        return view('/auth/admin/bahans', ['isNotif' => parent::getNotif(), 'data' => $data]);
    }

    public function addBahan(Request $request) {
        Validator::make($request->all(), 
        [
            'bahan' => 'required'
        ], 
        [
            'bahan.required' => 'Bahan harus diisi'
        ])->validate();

        $bahan = $request->input('bahan');
        $model = new Bahan();
        $model->nama_bahan = $bahan;
        $model->save();

        return redirect()->back();
    }

    public function deleteBahan(Request $request) {
        $id = $request->input('id');
        $bahan = Bahan::find($id);
        $bahan->delete();

        return redirect('/bahans');
    }

    public function colors() {
        $data = Warna::paginate(5);

        return view('/auth/admin/colors', ['isNotif' => parent::getNotif(), 'data' => $data]);
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
        $model = new Warna();
        $model->nama_warna = $color;
        $model->save();

        return redirect()->back();
    }

    public function deleteColor(Request $request) {
        $id = $request->input('id');
        $color = Warna::find($id);
        $color->delete();

        return redirect('/colors');
    }

    public function categories() {
        $data = Kategori::paginate(5);

        return view('/auth/admin/categories', ['isNotif' => parent::getNotif(), 'data' => $data]);
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
        $model = new Kategori();
        $model->nama_kategori = $kategori;
        $model->save();

        return redirect()->back();
    }

    public function deleteCategory(Request $request) {
        $id = $request->input('id');
        $color = Kategori::find($id);
        $color->delete();

        return redirect('/categories');
    }

    public function senders() {
        $data = Pengirim::paginate(5);

        return view('/auth/admin/senders', ['isNotif' => parent::getNotif(), 'data' => $data]);
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
        $model = new Pengirim();
        $model->nama_pengirim = $sender;
        $model->save();

        return redirect()->back();
    }

    public function deleteSender(Request $request) {
        $id = $request->input('id');
        $color = Pengirim::find($id);
        $color->delete();

        return redirect('/senders');
    }

    public function prediction() {
        return view('/auth/admin/prediction', ['isNotif' => parent::getNotif()]);
    }

    public function pdfGeneratePredict(Request $request) {
        $getNama = $request->query('nama_produk');
        $getPredict = Prediksi::where('hasil', 'like', '%"'.$getNama.'"%')->get();

        if (count($getPredict) == 0) {
            return redirect('/p-not-found');
        } else {
            $pdf = PDF::loadView('template/pdf_predict_html',['predicts' => $getPredict, 'nama' => $getNama]);

            return $pdf->download('prediksi.pdft');
        }
    }

    public function pNotFound() {
        return view('/auth/admin/predict_empty', ['isNotif' => parent::getNotif()]);
    }

    public function pdfGenerateReport(Request $request) {
        $getPeriode = $request->query('periode');
        $periode = explode('-', $getPeriode);
        $p_month = ($periode[1] < 10) ? '0'.$periode[1] : $periode[1]; 
        $p_year = $periode[0];
        $concat_p = $p_year.'-'.$p_month;
        $getProduct = Produk::all();
        $obj = new \StdClass();
        $finalData = array();
        foreach ($getProduct as $p) {
            $nama_produk = $p->nama_produk;
            $getDetails = DB::select("SELECT SUM(qty) as qty FROM detailorders WHERE nama_produk='$nama_produk' and created_at like '$concat_p%'");
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
        $getPeriod = DB::select('SELECT DISTINCT YEAR(created_at) AS "year", MONTH(created_at) as "month" FROM orders');
        $enumDate = array();

        foreach($getPeriod as $gp) {
            array_push($enumDate, $gp->year.'-'.$gp->month);
        }

        return view('/auth/admin/laporan_penjualan', ['isNotif' => parent::getNotif(), 'periodic' => $enumDate]);
    }

    public function laporanPrediksi(){
        $getProduct = Produk::all();

        return view('/auth/admin/laporan_prediksi', ['isNotif' => parent::getNotif(), 'products' => $getProduct]);
    }

    public function history() {
        $results = Order::paginate(10);
        $toggle = sizeof($results) == 0 ? false : true;

        return view('/auth/admin/history', ['isNotif' => parent::getNotif(), 'results' => $results, 'toggle' => $toggle]);
    }
}
