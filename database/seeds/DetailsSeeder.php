<?php

use Illuminate\Database\Seeder;

class DetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//
		$ifSingleValue = function($value) {
			$newFormattedValue = $value < 10 ? '0'.strval($value) : strval($value);
			return $newFormattedValue;
		};
    	$nama_produk = ['Barrel Clasp Snake Chain','Butterfly Clasp Moments', 'Daisy Flower', 'Me Link', 'Mesh Bracelet', 'Moments Crown', 'Pave Heart Clasp', 'Sparkling Slidder Tennis', 'Beads and Pave' , 'Moments T-Bar Snake', 'Stem Slidder', 'Crown n Interwined Hearts','Curb Chain', 'Double Hoop T-Bar', 'Elevated Heart', 'Logo Pave Circle', 'Logo Circle', 'Sparkling Stones', 'Sparkling Wishbones', 'Circle of Sparkle'];
    	$qty = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
    	$m = [1,2,3,4,5,6,7,8,9,10,11,12];
    	$y = [20,19,18];
    	$detail_harga = [560000, 520000,550000,500000,610000,590000,650000, 600000, 570000, 600000, 700000, 500000, 760000, 670000, 530000, 660000, 700000, 720000, 650000, 660000];
		$berat_produk = [1.3,4.5,1.2,1.1,6.7,4.5,2.3];

        for($i = 1; $i <= 500 ; $i ++) {
        	$index = rand(0, 19);
        	$index_qty = rand(0, 19);
        	$r_y = rand(0,1);
        	$r_m = rand(0,11);
			$r_b = rand(0,5);
			$randYear = strval(rand(2000, 2020));
			$randMonth = $ifSingleValue(rand(1,12));
			$randDay = $ifSingleValue(rand(1, 31));
			$randHour = $ifSingleValue(rand(0, 23));
			$randMinute = $ifSingleValue(rand(0, 59));
			$randSecond = $ifSingleValue(rand(0, 59));
			$created_at = date('Y-m-d H:i:s',strtotime("$randYear/$randMonth/$randDay $randHour:$randMinute:$randSecond"));

        	DB::table('detailorders')->insert([
        		'nama_produk' => $nama_produk[$index],
        		'qty' => $qty[$index_qty],
        		'berat_produk' => $berat_produk[$r_b],
        		'detail_harga' => $detail_harga[$index],
        		'predict_dt_m' => $m[$r_m],
				'predict_dt_y' => $y[$r_y],
				'created_at' => $created_at,
				'updated_at' => $created_at,
        	]);
        }
    }
}
