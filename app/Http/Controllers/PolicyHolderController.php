<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PolicyHolderController extends Controller
{
    public function index()
    {
        // Get the logged in user data to show in it the view
        $userData = Auth::user();
        $username = $userData->name .' '. $userData->surname;

        return view('policyholder.home')->with(['username' => $username]);
    }

    public function login(Request $request)
    {
        $postData = $request->input();
        $where = array(
            'mobile' => $postData['cell_number'],
            'password' => md5($postData['password'])
        );
        $user = User::where($where)->first();
        if(empty($user)) {
            $errors = array('error' => "Oops, wrong credentials supplied!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Authenticate user here
        Auth::login($user);
        return redirect()->intended();
    }

    public function register(Request $request)
    {
        $postData = $request->input();
        if($postData['password'] !== $postData['re_pwd']) {
            $errors = array('error' => "Password and Confirm Password fields doesn't match!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $data = array(
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'mobile' => $postData['mobile'],
            'email' => !empty($postData['email']) ? $postData['email'] : NULL,
            'role_id' => 2,
            'password' => md5($postData['password'])
        );

        $user = User::create($data);
        $user->save();

        Session::flash('message', 'User registered successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('policyHolder/login');
    }

    public function checkCell(Request $request)
    {
        $postData = $request->input();
        if(empty($postData) || empty($postData['cell_number'])) {
            print_r(json_encode(array('status' => 'error', 'msg' => 'Invalid request!')));
            die;
        }

        $user = User::where('mobile', $postData['cell_number'])->get();
        if(count($user) > 0)
            print_r(json_encode(array('status' => 'error', 'msg' => 'User with this cell number already exists!')));
        else
            print_r(json_encode(array('status' => 'success', 'msg' => 'Cell Number Verified!')));
        die;
    }

    public function addPolicy(Request $request)
    {
        die('we do');
    }
}
