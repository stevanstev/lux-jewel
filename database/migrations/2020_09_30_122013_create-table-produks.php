<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProduks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('produks', function(Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->integer('berat_produk');
            $table->string('foto');
            $table->integer('stok');
            $table->string('harga_produk');
            $table->string('deskripsi');
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
        Schema::dropIfExists('produks');
    }
}
