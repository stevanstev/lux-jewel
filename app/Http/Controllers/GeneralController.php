<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Customer;

use Auth;

use App\Notif;

class GeneralController extends Controller
{
    //
    public function getNotif() {   
        $isNotif = Notif::where('user_id', Auth::user()->id)->where('notif_active', 1)->first();

        if($isNotif) {
            return true;
        }
        
        return false;
    }

    public function userProfile() {
        $isNotif = $this->getNotif();

        return view('auth/user_profile', ['isNotif' => $isNotif]);
    }

    public function updateUser(Request $request) {
        Validator::make($request->all(), 
            [
                'nama_lengkap'=>'required',
                'kode_pos'=>'required|numeric|min:5',
                'no_hp'=>'required',
                'kota'=>'required',
                'provinsi'=>'required',
                'kelurahan'=>'required',
                'alamat'=>'required',
            ], 
            [
                'nama_lengkap.required' => 'nama tidak boleh kosong',
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
        $kode_pos = $request->input('kode_pos');
        $no_hp = $request->input('no_hp');
        $kota = $request->input('kota');
        $provinsi = $request->input('provinsi');
        $kelurahan = $request->input('kelurahan');
        $alamat = $request->input('alamat');

        $model = Customer::find(Auth::user()->id);
        $model->nama_lengkap = $nama_lengkap;
        $model->kode_pos = $kode_pos;
        $model->no_hp = $no_hp;
        $model->kota = $kota;
        $model->provinsi = $provinsi;
        $model->kelurahan = $kelurahan;
        $model->alamat = $alamat;
        $model->save();

        return redirect('/');

    }

    public function redirection() {
        if(Auth::user()->role == 1){
			return redirect('/admin-dash');
		}else if(Auth::user()->role == 2){
			return redirect('/dashboard');	
		}
    }

    public function notifikasi(){
        $isNotif = $this->getNotif();
        $notif_data = Notif::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        $total = sizeof($notif_data);

        if (Auth::user()->role == 1) {
            return view('auth/admin/notif', ['total' => $total,'notif_data' => $notif_data,'isNotif' => $isNotif]);
        } else {
            return view('auth/customer/notif', ['total' => $total, 'notif_data' => $notif_data, 'isNotif' => $isNotif]);
        }
    }

    public function logout() {
        Auth::logout();

        return redirect('/login');
    }
}
