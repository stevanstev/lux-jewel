<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OthersController extends GeneralController
{
    public function about() {
        return view('guest/about');
    }

    public function index() {
        return view('guest/index');
    }

    public function contact() {
        return view('guest/contact', ['isNotif' => parent::getNotif()]);
    }
}
