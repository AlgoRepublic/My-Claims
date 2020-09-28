<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\Roles;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Get data for counter cards
        $users = User::with('roles')->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->get();

        // Get total monthly claims
        $claims = Beneficiaries::whereBetween('beneficiary_request_date',[date('Y-m-01'), date('Y-m-t')])->get();

        // Get total active users; Users that are not dead yet
        $activeUsers = User::with('beneficiaries')->whereHas('beneficiaries', function($q){
            $q->where('is_approved', 0); // this means that there death is not approved in the system hence we take them as an active user
        })->get();

        // Get total monthly users
        $monthlyUsers = User::whereBetween('created_at',[date('Y-m-01'), date('Y-m-t')])->get();

        $data = array(
            'policyHolderCount' => count($users),
            'claimsCount' => count($claims),
            'activeCount' => count($activeUsers),
            'monthlyUsers' => count($monthlyUsers)
        );
        return view('admin.dashboard')->with($data);
    }

    public function login(Request $request)
    {
        $postData = $request->input();
        if(empty($postData) || empty($postData['email']) || empty($postData['password'])) {
            $errors = array('error' => "Oops, invalid request!");
            return redirect('/');
        }

        // First get the admin role and its corresponding id
        $role = Roles::where('role_name', 'admin')->first();
        if(empty($role))
            return redirect('/');

        $where = array(
            'email' => $postData['email'],
            'password' => md5($postData['password']),
            'role_id' => $role->id
        );

        $user = User::where($where)->first();
        if(empty($user)) {
            $errors = array('error' => "Oops, wrong credentials supplied!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Authenticate user here
        Auth::login($user);
        return redirect('/admin');
    }

    public function policyHolder(Request $request)
    {
        $policyHolders = User::with('roles')->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->get();

        $data = array(
            'policyHolders' => $policyHolders
        );
        return view('admin.policy_holders')->with($data);
    }

    public function beneficiaries(Request $request)
    {
        $beneficiaries = Beneficiaries::all();
        $data = array(
            'beneficiaries' => $beneficiaries
        );

        return view('admin.beneficiaries')->with($data);
    }
}
