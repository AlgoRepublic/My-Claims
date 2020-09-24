<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\beneficiary_policy;
use App\Policies;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PolicyHolderController extends Controller
{
    public function index()
    {
        // Get the logged in user data to show in it the view
        $userData = Auth::user();
        $username = $userData->name .' '. $userData->surname;
        $documentNumber = $userData->identity_document_number;

        // Get list of added policies by this user
        $policies = Policies::where('added_by', $userData->id)->get();
        $beneficiaries = Beneficiaries::where('added_by', $userData->id)->get();
        $data = array(
            'username' => $username,
            'documentNumber' => $documentNumber,
            'policies' => $policies,
            'beneficiaries' => $beneficiaries
        );
        return view('policyholder.home')->with($data);
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
        return redirect('/policyHolder');
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
            'password' => md5($postData['password']),
            'identity_document_number' => $postData['identity_document_number']
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
        $postData = $request->input();
        $path = '';
        $beneficiaryData = $policyBen = array();
        $benIDs = array();

        if(!empty($_FILES['doc_file']['name']))
            $path = Storage::putFile('public/policies', $request->file('doc_file'));

        if(!empty($postData['bene_name'])) {

            for($i=0; $i < count($postData['bene_name']); $i++) {
                $beneficiaryData['name'] = $postData['bene_name'][$i];
                $beneficiaryData['surname'] = $postData['bene_surname'][$i];
                $beneficiaryData['identity_document_number'] = $postData['bene_document_number'][$i];
                $beneficiaryData['cell_number'] = $postData['bene_cell_number'][$i];
                $beneficiaryData['added_by'] = Auth::user()->id;
                $beneficiary = Beneficiaries::create($beneficiaryData);
                $benIDs[] = $beneficiary->id;
            }
        }

        $allBeneficiaries = array_merge($postData['beneficiaries'],$benIDs);
        $data = array(
            'name' => $postData['doc_name'],
            'type' => $postData['policy_type'],
            'document' => $path,
            'document_original_name' => $_FILES['doc_file']['name'],
            'added_by' => Auth::user()->id
        );

        $newPolicy = Policies::create($data);
        $newPolicy->save();

        $policyID = $newPolicy->id;
        $n = 0;
        foreach ($allBeneficiaries as $ben) {
            $policyBen[$n]['policy_id'] = $policyID;
            $policyBen[$n]['beneficiary_id'] = $ben;
            $n++;
        }

        if(!empty($policyBen))
            beneficiary_policy::insert($policyBen);

        Session::flash('message', 'Policy added successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('policyHolder/');
    }

    public function addPolicyView()
    {
        // Get the beneficiaries list to show to user
        $benList = Beneficiaries::where('added_by', Auth::user()->id)->get();
        return view('policyholder.add_policy')->with('benList', $benList);
    }

    public function deletePolicy(Request $request)
    {
        $postData = $request->input();
        if(!empty($postData['id'])){
            $policy = Policies::find($postData['id']);
            $policy->delete();
        }
        Session::flash('message', 'The selected policy has been deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    private function createFileUrl($path)
    {
        return URL::to('/').Storage::url($path);
    }
}
