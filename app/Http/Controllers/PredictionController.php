<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Detailorder;

use App\Prediksi;

use Carbon\Carbon;

use App\Produk;

use DB;

use App\Notif;

use Auth;

class PredictionController extends GeneralController
{
    //
    public function index() {
        $products = Produk::paginate(10);

        return view('/auth/admin/prediction', ['products' => $products, 'isNotif' => parent::getNotif()]);
    }

    public function predict(Request $request){
        $id = $request->input('id');
        
        $currentDate = Carbon::now();
        $currentDate = $currentDate->toDateTimeString();
        $getDTM = Date('n');
        $getDTY = Date('y');

        // perhitungan
        $periodePenjualan = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $tsquare = array_map(function($p){ return $p**2; }, $periodePenjualan);
        $penjualan = array();

        $data = Produk::find($id);
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

            $enumData = DB::select("SELECT COUNT(qty) as total FROM detailorders WHERE nama_produk='$nama_produk' AND predict_dt_m='$m' AND predict_dt_y='$y'");
            
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
        $mad = function($array, $prediction) {
            $result = array();

            foreach($array as $a) {
                array_push($result, round(abs($a - $prediction), 6));
            }
            
            return $result;
        };

        $mad_map = $mad($penjualan, $prediction_formula);
        $mad_result = array_sum($mad_map);
        $mad_result = $mad_result / $n;

        $mse_squaring = array_map(function($val) { return $val ** 2; },$mad_map);
        $mse_result = array_sum($mse_squaring);
        $mse = round( $mse_result / $n , 6);

        $prediction = new Prediksi();
        $prediction->tgl_prediksi = date('Y-m-d H:i:s');
        $prediction->hasil = $nama_produk.'#'.strval(floor($prediction_formula));
        $prediction->mse = $mse;
        $prediction->mad = $mad_result;
        $prediction->save();

        $result = [
            'value' => floor($prediction_formula),
            'nama_produk' => $nama_produk,
            'isNotif' => parent::getNotif(),
            'mad' => $mad_result,
            'mse' => $mse,
        ];

        return view('/auth/admin/predict_result', $result);
    }

    public function predictByPeriods(Request $request) {
		Validator::make($request->all(), 
		[
			'from' => 'required',
			'to' => 'required'
		], 
		[
				'from.required' => 'Dari tidak boleh kosong',
				'to.required' => 'Sampai tidak boleh kosong'
		])->validate(); 

    	$from = $request->input('from');
       	$to = $request->input('to');
       	$produk_id = $request->input('id');
       	// $produk_id = 4;

        $periode = array();
        $penjualan = array();
        $periodeTimesPenjualan = array();

       	$from = explode('/', $from);
       	$to = explode('/', $to);

       	$fromMonth = $from[0];
       	$toMonth = $to[0];

		$fromYear = $from[2];
		$fromYearSplit = $fromYear[2].$fromYear[3];
		
		$toYear = $to[2];
		$toYearSplit = $toYear[2].$toYear[3];

		$totalPeriode = 1;

		for ($y = $fromYearSplit ; $y <= $toYearSplit ; $y++) {
			for ($m = $fromMonth; $m <= 12 ; $m++) {
				if ($m == $toMonth && $y == $toYearSplit) {
					array_push($periode, $m.'-'.$y);
					break;
				} else {
					array_push($periode, $m.'-'.$y);
				}
				$totalPeriode++;
			}
			$fromMonth = 1;
			$fromYear++;
		}

		//Total periode (n)
		$sizeOfPeriode = sizeof($periode);
		$data = Produk::find($produk_id);
        $nama_produk = $data->nama_produk;

		for ($i = 0 ; $i < $sizeOfPeriode ; $i++) {
			$splitPeriode = explode('-', $periode[$i]);
			$m = $splitPeriode[0];
			$y = $splitPeriode[1];
			$enumData = DB::select("SELECT COUNT(qty) as total FROM detailorders WHERE nama_produk='$nama_produk' AND predict_dt_m='$m' AND predict_dt_y='$y'");

			if ($enumData == "") {
            	array_push($penjualan, 0);
            } else {
            	array_push($penjualan, $enumData[0]->total);
            }
		}

		$count_periode = $sizeOfPeriode;
		$count_periode_square = $count_periode ** 2;
		$count_tsquare = 0;
		for ($i = 1 ; $i <= $totalPeriode ; $i++) {
			$count_tsquare += $i ** 2; 
		}
        $count_penjualan = array_sum($penjualan);
		$count_t_y = $count_periode * $count_penjualan;
		$rightCalculation = ($totalPeriode*$count_tsquare) - ($count_periode_square);
		$b = (($totalPeriode*$count_t_y) - ($count_periode * $count_penjualan)) / (($rightCalculation == 0) ? 1 : $rightCalculation);
		$a = (($count_penjualan - ($b * $count_periode)) / $totalPeriode);
		$t = $totalPeriode + 1;
		$prediction_formula = $a + ($b * $t);
		
		$mad = function($array, $prediction) {
            $result = array();

            foreach($array as $a) {
                array_push($result, round(abs($a - $prediction), 6));
            }
            
            return $result;
        };

        $mad_map = $mad($penjualan, $prediction_formula);
        $mad_result = array_sum($mad_map);
        $mad_result = $mad_result / $totalPeriode;

        $mse_squaring = array_map(function($val) { return $val ** 2; },$mad_map);
        $mse_result = array_sum($mse_squaring);
		$mse = round( $mse_result / $totalPeriode , 6);

		$result = [
            'value' => floor($prediction_formula),
            'nama_produk' => $nama_produk,
            'isNotif' => parent::getNotif(),
            'mad' => $mad_result,
            'mse' => $mse,
        ];

        return view('/auth/admin/predict_result', $result);
    }

    public function periods($id) {
        $data = Produk::find($id);

        return view('/auth/admin/periods', ['id' => $id, 'isNotif' => parent::getNotif(), 'data' => $data]);
    }
}
