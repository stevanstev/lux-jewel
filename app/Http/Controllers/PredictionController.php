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
    public function index() {
        $products = Product::paginate(10);

        return view('/auth/admin/prediction', ['products' => $products]);
    }

    public function predict(Request $request){
        $id = $request->input('id');
        
        $currentDate = Carbon::now();
        $currentDate = $currentDate->toDateTimeString();
        $getDTM = Date('n');
        $getDTY = Date('y');

        // perhitungan
        $periodePenjualan = array(1,2);
        $tsquare = array_map(function($p){ return $p**2; }, $periodePenjualan);
        $penjualan = array();

        $data = Product::find($id);
        $nama_produk = $data->nama_produk;

        foreach($periodePenjualan as $d) {
            $m = $getDTM - $d;
            $m = strval($m);
            $y = strval($getDTY);
            $enumData = DB::select("SELECT COUNT(qty) as total FROM details WHERE nama_produk='$nama_produk' AND predict_dt_m='$m' AND predict_dt_y='$y'");
            array_push($penjualan, $enumData[0]->total);
        }

        $count_periode = array_sum($periodePenjualan);
        $count_periode_square = $count_periode ** 2;
        $count_tsquare = array_sum($tsquare);
        $count_penjualan = array_sum($penjualan);
        $count_t_y = $count_periode * $count_penjualan;
        $n = 2;
        $b = (($n*$count_t_y) - ($count_periode * $count_penjualan)) / (($n*$count_tsquare) - ($count_periode_square));
        $a = (($count_penjualan - ($b * $count_periode)) / $n);
        $t = 3;
        $prediction_formula = $a + ($b * $t);

        $result = [
            'value' => $prediction_formula,
            'nama_produk' => $nama_produk,
        ];

        return view('/auth/admin/predict_result', $result);
    }
}
