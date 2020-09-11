<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PolicyHolderController extends Controller
{
    public function index()
    {
        return view('policyholder.home');
    }

    public function login(Request $request)
    {
        $postData = $request->input();
        $where = array(
            'email' => $postData['email'],
            'password' => md5($postData['password'])
        );
        $admin = User::where($where)->first();
        if(empty($admin)) {
            $errors = array('error' => "Oops, wrong credentials supplied!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Authenticate user here
        Auth::login($admin);
        return redirect()->intended();
    }
}
