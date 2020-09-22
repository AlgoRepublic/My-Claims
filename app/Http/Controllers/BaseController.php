<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\beneficiary_policy;
use App\Policies;
use App\User;
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

    public function beneficiary(Request $request)
    {
        return view('beneficiary.home');
    }

    public function findPolicy(Request $request)
    {
        $postData = $request->input();

        // Get policy holder information
        $user = User::with('Policies')->where('identity_document_number', $postData['policyholder_number'])->first();
        if(empty($user)) {
            Session::flash('message', 'Sorry, no policy holder having this Identity number exists in our system !');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }

        // Now check the beneficiary
        $beneficiary = Beneficiaries::where('identity_document_number', $postData['beneficiary_number'])->first();
        if(empty($beneficiary)) {
            Session::flash('message', 'Sorry, no beneficiary with this Identity number exists in our system !');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }

        $policyType = array();

        // As both of the users are verified, now check link of the document
        foreach($user->policies as $policy) {
            $linked = beneficiary_policy::where(['policy_id' => $policy->id,'beneficiary_id' => $beneficiary->id])->first();
            if(!empty($linked)) {
                // Add the policy type to show the user
                $policyType[] = $policy->type;
            }
        }

        if(empty($policyType)) {
            Session::flash('message', 'Sorry, this policy holder have not registered you as a beneficiary!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }

        $data = array(
            'policy_type' => $policyType,
            'name' => $user->name .' '. $user->surname,
            'policyholder_number' => $postData['policyholder_number'],
            'beneficiary_number' => $postData['beneficiary_number']
        );
        return view('beneficiary.check_policies')->with($data);


    }
}
