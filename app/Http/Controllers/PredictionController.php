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

	/**
	* @param int $periode
	* @param int $countTSquare
	* @param array $penjualan
	* @return array
	*/
	public function linearRegression($totalPeriode = 0, $countTSquare = 0, $penjualan = []){
		$count_periode_square = $totalPeriode ** 2;	
        $count_penjualan = array_sum($penjualan);
		$count_t_y = $totalPeriode * $count_penjualan;
		$rightCalculation = ($totalPeriode*$countTSquare) - ($count_periode_square);
		$b = (($totalPeriode*$count_t_y) - ($totalPeriode * $count_penjualan)) / (($rightCalculation == 0) ? 1 : $rightCalculation);
		$a = (($count_penjualan - ($b * $totalPeriode)) / $totalPeriode);
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

		return array($prediction_formula, $mad_result, $mse);
	}

    public function predictByPeriods(Request $request) {
		Validator::make($request->all(), 
		[
			'from' => 'required',
			'to' => 'required',
		], 
		[
			'from.required' => 'Dari tidak boleh kosong',
			'to.required' => 'Sampai tidak boleh kosong'
		])->validate(); 

    	$from = $request->input('from');
       	$to = $request->input('to');
		$produk_id = $request->input('id');

		// Change date format from mm/dd/yyyy to yyyy-mm-dd
		$customFormatDate = function($date) {
			$newDate = str_replace('/', '-', $date);
			$newDate = substr($newDate, 6).'-'.substr($newDate, 0, 5);

			return $newDate;
		};

		$from = $customFormatDate($from);
		$to = $customFormatDate($to);

		// initial value
		$penjualan = array();
		$totalPeriode = 0;
		$count_tsquare = 0;
		$data = Produk::find($produk_id);
		$nama_produk = $data->nama_produk;

		$queryGetData = "SELECT qty FROM detailorders WHERE created_at BETWEEN '$from' and '$to' and nama_produk='$nama_produk' order by created_at desc";
		$result = DB::select($queryGetData);
		$totalPeriode = sizeof($result);
		for ($i = 1 ; $i <= $totalPeriode ; $i++) {
			$count_tsquare += $i ** 2; 
		}
		foreach($result as $key => $value) {
			array_push($penjualan, $value->qty);
		}

		$linearRegress = $this->linearRegression($totalPeriode, $count_tsquare, $penjualan);

		$result = [
            'value' => floor($linearRegress[0]),
            'nama_produk' => $nama_produk,
            'isNotif' => parent::getNotif(),
            'mad' => $linearRegress[1],
            'mse' => $linearRegress[2],
        ];

        return view('/auth/admin/predict_result', $result);
    }

    public function predictByMonths(Request $request) {
		Validator::make($request->all(), 
		[
			'from_m_m' => 'required',
			'from_m_y' => 'required',
			'to_m_y' => 'required',
			'to_y_m' => 'required',
		], 
		[
			'from_m_m.required' => 'Bulan dari tidak boleh kosong',
			'from_m_y.required' => 'Tahun dari tidak boleh kosong',
			'to_m_y.required' => 'Bulan sampai tidak boleh kosong',
			'to_y_m.required' => 'Tahun sampai tidak boleh kosong'
		])->validate(); 

    	$fromMonth = $request->input('from_m_m');
       	$fromYear = $request->input('from_m_y');
       	$toMonth = $request->input('to_m_y');
		$toYear = $request->input('to_y_m');
		$produk_id = $request->input('id');

        $periode = array();
        $penjualan = array();
		$periodeTimesPenjualan = array();
		
		$fromYearSplit = substr($fromYear, 2);
		$toYearSplit = substr($toYear, 2);

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

		$count_tsquare = 0;
		for ($i = 1 ; $i <= $totalPeriode ; $i++) {
			$count_tsquare += $i ** 2; 
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
		
		$prediction_result = $this->linearRegression($totalPeriode, $count_tsquare, $penjualan);

		$result = [
            'value' => floor($prediction_result[0]),
            'nama_produk' => $nama_produk,
            'isNotif' => parent::getNotif(),
            'mad' => $prediction_result[1],
            'mse' => $prediction_result[2],
        ];

        return view('/auth/admin/predict_result', $result);
    }

    public function periods($id) {
		$data = Produk::find($id);
		$monthPredictionRange = 2000;

        return view('/auth/admin/periods', ['id' => $id, 'isNotif' => parent::getNotif(), 'data' => $data, 'monthPredictionRange' => $monthPredictionRange]);
    }
}
