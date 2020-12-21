<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('carts', function(Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_produk');
            $table->string('nama_produk');
            $table->integer('berat_produk');
            $table->string('harga_produk');
            $table->text('deskripsi');
            $table->string('color');
            $table->string('kategori');
            $table->string('total_harga');
            $table->string('jumlah');
            $table->string('foto');
            $table->string('bahan');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('carts');
    }
}
