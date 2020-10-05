<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTtransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ttransactions', function(Blueprint $table) {
            $table->id();
            $table->string('kota_penerima');
            $table->integer('id_user');
            $table->string('provinsi_penerima');
            $table->integer('total_transaksi');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('biaya_kirim')->default('0');
            $table->string('tgl_transaksi');
            $table->string('kode_pos_p');
            $table->string('kelurahan_p');
            $table->string('nama_penerima');
            $table->string('no_telepon');
            $table->integer('status_pesanan');
            $table->text('alamat_penerima');
            $table->string('no_resi')->nullable();
            $table->text('items');
            $table->string('kurir')->nullable();
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
        Schema::dropIfExists('ttransactions');
    }
}
