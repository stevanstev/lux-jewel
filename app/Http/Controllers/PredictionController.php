<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Detail;

use Carbon\Carbon;

use App\Product;

use DB;

class PredictionController extends Controller
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
        $products = Product::paginate(10);
        $isNotif = $this->getNotif();

        return view('/auth/admin/prediction', ['products' => $products, 'isNotif' => $isNotif]);
    }

    public function predict(Request $request){
        $isNotif = $this->getNotif();
        $id = $request->input('id');
        
        $currentDate = Carbon::now();
        $currentDate = $currentDate->toDateTimeString();
        $getDTM = Date('n');
        $getDTY = Date('y');

        // perhitungan
        $periodePenjualan = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $tsquare = array_map(function($p){ return $p**2; }, $periodePenjualan);
        $penjualan = array();

        $data = Product::find($id);
        $nama_produk = $data->nama_produk;

        for($i = 0 ; $i < sizeof($periodePenjualan); $i++) {
            $m = $getDTM - $periodePenjualan[$i];
            $m = strval($m);
            $y = strval($getDTY);

            if (intval($m) == 0) {
            	$getDTY = $getDTY - 1;
            	$getDTM = 22;

            	$m = $getDTM - $periodePenjualan[$i];
            	$m = strval($m);
            	$y = strval($getDTY);
            }

            $enumData = DB::select("SELECT COUNT(qty) as total FROM details WHERE nama_produk='$nama_produk' AND predict_dt_m='$m' AND predict_dt_y='$y'");
            
            if ($enumData == "") {
            	array_push($penjualan, 0);
            } else {
            	array_push($penjualan, $enumData[0]->total);
            }
        }

        $count_periode = array_sum($periodePenjualan);
        $count_periode_square = $count_periode ** 2;
        $count_tsquare = array_sum($tsquare);
        $count_penjualan = array_sum($penjualan);
        $count_t_y = $count_periode * $count_penjualan;
        $n = 12;
        $b = (($n*$count_t_y) - ($count_periode * $count_penjualan)) / (($n*$count_tsquare) - ($count_periode_square));
        $a = (($count_penjualan - ($b * $count_periode)) / $n);
        $t = 13;
        $prediction_formula = $a + ($b * $t);

        $result = [
            'value' => $prediction_formula,
            'nama_produk' => $nama_produk,
            'isNotif' => $isNotif
        ];

        return view('/auth/admin/predict_result', $result);
    }
}
