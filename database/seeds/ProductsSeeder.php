<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $products = ['Barrel Clasp Snake Chain','Butterfly Clasp Moments', 'Daisy Flower', 'Me Link', 'Mesh Bracelet', 'Moments Crown', 'Pave Heart Clasp', 'Sparkling Slidder Tennis', 'Beads and Pave' , 'Moments T-Bar Snake', 'Stem Slidder', 'Crown n Interwined Hearts','Curb Chain', 'Double Hoop T-Bar', 'Elevated Heart', 'Logo Pave Circle', 'Logo Circle', 'Sparkling Stones', 'Sparkling Wishbones', 'Circle of Sparkle'];
        $berat_produk = [1.3,4.5,1.2,1.1,6.7,4.5,2.3];
        $stok = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $detail_harga = [560000, 520000,550000,500000,610000,590000,650000, 600000, 570000, 600000, 700000, 500000, 760000, 670000, 530000, 660000, 700000, 720000, 650000, 660000];
        $colors = ['Silver', 'Gold', 'RoseGold'];
        $kategoris = ['Ring', 'Necklace'];
        $bahan = ['Bahan1', 'Bahan2'];

        for ($i = 0 ; $i < 20 ; $i++) {
        	DB::table('produks')->insert([
        		'nama_produk' => $products[$i],
        		'berat_produk' => $berat_produk[rand(0, 6)],
        		'foto' => 'banner2.jpg',
        		'stok' => $stok[rand(0,19)],
        		'harga_produk' => $detail_harga[$i],
        		'deskripsi' => 'ASD',
        		'color' => $colors[rand(0,2)],
                'kategori' => $kategoris[rand(0,1)],
                'bahan' => $bahan[rand(0,1)],
        	]);
        }
    }
}
