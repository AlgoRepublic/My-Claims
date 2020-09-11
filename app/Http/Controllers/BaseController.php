<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BaseController extends Controller
{
    public function index(Request $request)
    {
        //Auth::logout();
        //Session::flush();
        //$data = $request->session()->all();
        //echo "<pre>";print_r($data);die;
        // Check user session here also to show logout button dynamically
        $loggedIn = false;
        if(Auth::check())
            $loggedIn = true;

        $data = array();
        $data['is_loggedin'] = $loggedIn;

        return view('home')->with('loggedIn', $loggedIn);
    }

    public function logout(Request $request) {

        Auth::logout();
        Session::flush();
        return Redirect::to('/');
    }
}
