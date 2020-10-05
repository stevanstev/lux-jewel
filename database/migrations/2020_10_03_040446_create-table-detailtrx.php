<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDetailtrx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('details', function(Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->integer('qty');
            $table->integer('berat_produk');
            $table->string('detail_harga');
            $table->string('predict_dt_m');
            $table->string('predict_dt_y');
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
        Schema::dropIfExists('details');
    }
}
