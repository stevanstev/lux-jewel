<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Produk;

use App\Order;

use App\Pembayaran;

use App\Notif;

use DB;

use Auth;

class CustomController extends GeneralController
{   
    ///$page -> page to render
    ///$search-> search query
    public function searchMap($page, $search) {
        $data = function($v, $s, $n = null, $t = false) {
            ///[$v] means view to render 
            ///[$s] means search query results to show into it's view
            ///[$n] means params for sending notification value to view
            ///[$t] means toggle to avoid logic when the query is empty and show action button
            /// -> example: when we search transaction then query result return 0, it will show 
            /// go to transaction button
            $d = new \StdClass();
            $d->view = $v;
            $d->search = $s;
            $d->notif = $n;
            $d->toggle = $t;

            return $d;
        };
        
        switch($page){
            // g for guest, a for auth, c for customer
            case 'g-search-prod':
            case 'g-product':
                $query = Produk::where('nama_produk', $search)->orWhere('nama_produk', 'like', '%'.$search.'%')->paginate(10);
                $data = $data('/guest/product', $query);   
                break;
            case 'a-search-stuff':
            case 'a-stuff':
                // check if product table is not empty
                $toggle = ($page == 'a-search-stuff') ? true : false;
                $query = DB::table('produks')
                ->join('stocks', 'produks.id', '=', 'stocks.product_id')
                ->select('produks.*', 'stocks.total_stok')
                ->where('produks.nama_produk','like','%' . $search . '%')
                ->paginate(10);
                $data = $data('/auth/admin/stuff', $query, parent::getNotif(), $toggle);   
                break;
            case 'a-search-history':
            case 'a-history':
                // check if product table is not empty
                $toggle = ($page == 'a-search-history') ? true : false;
                $changeDateFormat = function($date) {
                    $newFormat = str_replace('/','-', $date);
                    $newFormat = substr($newFormat, 6, strlen($date) - 1).'-'.substr($newFormat, 0, 5);
                    return $newFormat;
                };
                $from = $search[0] ? $changeDateFormat($search[0]) : '';
                $to = $search[1] ? $changeDateFormat($search[1]) : '';
                $status = $search[2] ?? '';

                $query =  DB::table('orders')
                    ->whereBetween('tgl_transaksi', [$from, $to])
                    ->where('status_pesanan', $status)
                    ->paginate(10);

                if($from == '' || $to == '') {
                    $query =  DB::table('orders')
                    ->where('status_pesanan', $status)
                    ->paginate(10);
                }
                
                $data = $data('/auth/admin/history', $query, parent::getNotif(), $toggle);   
                break;
            case 'a-search-order':
            case 'a-order':
                // check if product table is not empty
                $toggle = ($page == 'a-search-order') ? true : false;
                $query = Pembayaran::where('nama_penerima', $search)->orWhere('nama_penerima', 'like', '%' . $search . '%')->paginate(10);
                $data = $data('/auth/admin/order', $query, parent::getNotif(), $toggle);   
                break;
            case 'c-search-items':
            case 'c-items':
                // check if product table is not empty
                $toggle = ($page == 'c-search-items') ? true : false;
                $query = Produk::where('nama_produk', $search)->orWhere('nama_produk', 'like', '%'.$search.'%')->paginate(10);
                $data = $data('/auth/customer/products', $query, parent::getNotif(), $toggle);   
                break;
            case 'c-search-transactions':
            case 'c-transactions':
                // check if product table is not empty
                $toggle = ($page == 'c-search-transactions') ? true : false;
                $query = DB::table('pembayarans')
                ->where('id_user', '=', Auth::user()->id)
                ->where('items', 'like', '%'.$search.'%')
                ->paginate(10);
                $data = $data('/auth/customer/transactions', $query, parent::getNotif(), $toggle);   
                break;
            //flag
            case 'a-search-prediction':
            case 'a-prediction':
                // check if product table is not empty
                $toggle = ($page == 'a-search-prediction') ? true : false;
                $query = Produk::where('nama_produk', $search)->orWhere('nama_produk', 'like', '%'.$search.'%')->paginate(10);
                $data = $data('/auth/admin/prediction', $query, parent::getNotif(), $toggle);   
                break;
            default:
                break;
        }

        return $data;
    }

    public function search(Request $request) {
        // Get Input search query
        // If search query is custom (not just an search input)
        $search = $request->input('isCustom') == true ? $request->input('custom') : $request->input('search');
        // Get cannonical route name from searchRouting Function
        $path = Route::currentRouteName();
        // Results invoking
        $result = $this->searchMap($path, $search);

        return view($result->view, ['results' => $result->search, 'isNotif' => $result->notif, 'toggle' => $result->toggle]);
    }

    public function checkExpiredScheduler() {
        $date = date('Y-m-d H:i:s');
        $pembayaran = DB::table('pembayarans')
        ->where('expired_date', '=', $date)
        ->orWhere('expired_date', '<', $date)
        ->where('expired_date', '!=', '#')
        ->get();
        $totalData = sizeof($pembayaran);
        
        foreach($pembayaran as $k) {
            $order = new Order();
            $order->kota_penerima = $k->kota_penerima;
            $order->provinsi_penerima = $k->provinsi_penerima;
            $order->total_transaksi = $k->total_transaksi;
            $order->tgl_transaksi = $k->tgl_transaksi;
            $order->kode_pos_p = $k->kode_pos_p;
            $order->kelurahan_p = $k->kelurahan_p;
            $order->nama_penerima = $k->nama_penerima;
            $order->no_telepon = $k->no_telepon;
            $order->alamat_penerima = $k->alamat_penerima;
            $order->bukti_pembayaran = '';
            $order->no_resi = '';
            $order->status_pesanan = 6;
            $order->biaya_kirim = $k->biaya_kirim;
            $order->save();

            $notif = new Notif();
            $notif->message = "Transaksi dengan id $k->id gagal, karena pembayaran melewati batas waktu";
            $notif->user_id = $k->id_user;
            $notif->notif_active = 1;
            $notif->save();
        }

        $delete = DB::table('pembayarans')
        ->where('expired_date', '=', $date)
        ->orWhere('expired_date', '<', $date)
        ->where('expired_date', '!=', '#')
        ->delete();

        return response(
            view('/auth/admin/scheduler_status',
            array('result'=> array(
                'schedulerStatus' => 200,
                'dataDeleted' => $totalData))
            ),200, ['Content-Type' => 'application/json']);
    }
}
