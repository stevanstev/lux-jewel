<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Hash;

use App\Customer;

class RegisterController extends Controller
{
    public function index() {
        return view('guest/register');
    }

    public function action(Request $request) {
        Validator::make($request->all(), 
            [
                'nama_lengkap'=>'required',
                'email'=>'required|email|min:5|unique:customers,email',
                'password'=>'required|min:6|alpha_num',
                'kode_pos'=>'required|numeric|min:5',
                'no_hp'=>'required',
                'kota'=>'required',
                'provinsi'=>'required',
                'kelurahan'=>'required',
                'alamat'=>'required',
            ], 
            [
                'nama_lengkap.required' => 'nama tidak boleh kosong',
                'email.required' => 'email tidak boleh kosong',
                'email.email' => 'format email harus benar',
                'email.min' => 'email harus minimal memiliki panjang 8',
                'email.unique' => 'mohon gunakan email yang lain',
                'password.required' => 'password tidak boleh kosong',
                'password.min' => 'minimal password 6 karakter',
                'password.alpha_num' => 'password harus mengandung angka dan huruf',
                'kode_pos.required' => 'kode pos tidak boleh kosong',
                'kode_pos.numeric' => 'kode pos harus berupa angka',
                'kode_pos.max' => 'kode pos maximal 5 angka',
                'no_hp.required' => 'nomor hp tidak boleh kosong',
                'kota.required' => 'kota tidak boleh kosong',
                'provinsi.required' => 'provinsi tidak boleh kosong',
                'kelurahan.required' => 'kelurahan tidak boleh kosong',
                'alamat.required' => 'alamat tidak boleh kosong',
            ]
        )->validate();

        $nama_lengkap = $request->input('nama_lengkap');
        $email = $request->input('email');
        $password = $request->input('password');
        $kode_pos = $request->input('kode_pos');
        $no_hp = $request->input('no_hp');
        $kota = $request->input('kota');
        $provinsi = $request->input('provinsi');
        $kelurahan = $request->input('kelurahan');
        $alamat = $request->input('alamat');

        $model = new Customer;
        $model->nama_lengkap = $nama_lengkap;
        $model->email = $email;
        $model->password = Hash::make($password);
        $model->kode_pos = $kode_pos;
        $model->no_hp = $no_hp;
        $model->kota = $kota;
        $model->provinsi = $provinsi;
        $model->kelurahan = $kelurahan;
        $model->alamat = $alamat;
        $model->role = 2;
        $model->save();

        return redirect('/login');
    }
}
