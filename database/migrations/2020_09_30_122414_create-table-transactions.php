<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('transactions', function(Blueprint $table) {
            $table->id();
            $table->string('kota_penerima');
            $table->string('provinsi_penerima');
            $table->integer('total_transaksi');
            $table->string('bukti_pembayaran');
            $table->string('biaya_kirim');
            $table->string('tgl_transaksi');
            $table->string('kode_pos_p');
            $table->string('kelurahan_p');
            $table->string('nama_penerima');
            $table->string('no_telepon');
            $table->integer('status_pesanan');
            $table->string('alamat_penerima');
            $table->string('no_resi');
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
        Schema::dropIfExists('transactions');
    }
}
