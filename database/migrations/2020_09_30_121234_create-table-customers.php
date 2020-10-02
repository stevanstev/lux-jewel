<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customers', function(Blueprint $table){
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('password');
            $table->string('kode_pos');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kelurahan');
            $table->integer('role');
            $table->string('remember_token')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
