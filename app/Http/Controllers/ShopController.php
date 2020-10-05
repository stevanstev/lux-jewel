<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function getNotif() {   
        $isNotif = Notif::where('user_id', Auth::user()->id)->where('notif_active', 1)->first();

        if($isNotif) {
            return true;
        }
        
        return false;
    }

    public function index() {
        $isNotif = $this->getNotif();
        return view('guest/shop',['isNotif' => $isNotif]);
    }
}
