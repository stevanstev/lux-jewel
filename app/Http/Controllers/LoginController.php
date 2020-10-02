<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Auth;

use App\Customer;

class LoginController extends Controller
{
    public function index() {
        return view('guest/login');
    }

    public function action(Request $request) {
        Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ], 
            [
                'email.required' => 'email tidak boleh kosong',
                'email.email' => 'format email harus benar',
                'password' => 'password tidak boleh kosong'
            ]
        )->validate();

        $email = $request->input('email');
        $password = $request->input('password');

        if(Auth::attempt(['email'=>$email,'password'=>$password,'role'=>1])){
            return redirect('/admin-dash');
        }else if(Auth::attempt(['email'=>$email,'password'=>$password,'role'=>2])){
            return redirect('/dashboard');
        }else{	
            return redirect('/login');
        }
    }
}
