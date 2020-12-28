<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class OthersController extends GeneralController
{
    public function about() {
        return view('guest/about');
    }

    public function index() {
        return view('guest/index');
    }

    public function contact() {
    	$isAuth = !Auth::guest() ? parent::getNotif() : '';
        return view('guest/contact', ['isNotif' => $isAuth]);
    }
}
