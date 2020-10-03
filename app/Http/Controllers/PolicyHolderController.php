<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\beneficiary_policy;
use App\PaymentPackages;
use App\Policies;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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

    public function registerView()
    {
        // Get list of packages added in the system
        $packages = PaymentPackages::all();
        return view('policyholder.register')->with(['packages' => $packages]);
    }

    public function register(Request $request)
    {
        $postData = $request->input();
        if($postData['password'] !== $postData['re_pwd']) {
            $errors = array('error' => "Password and Confirm Password fields doesn't match!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $package = PaymentPackages::find($postData['package']);
        if(empty($package)) {
            $errors = array('error' => "Oops, invalid package selected!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $data = array(
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'mobile' => $postData['mobile'],
            'email' => !empty($postData['email']) ? $postData['email'] : NULL,
            'role_id' => 2,
            'password' => md5($postData['password']),
            'identity_document_number' => $postData['identity_document_number'],
            'payment_verified' => 0
        );

        $user = User::create($data);
        $user->save();

        $htmlForm = $this->payfastPayment($package['amount'], $postData['name'], $postData['surname'], $postData['mobile'], 'Shoow My Claims', $package['frequency'], $user->id);
        return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm]);

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

    public function editProfile(Request $request)
    {
        $postData = $request->input();
        // First of all check if the provided password matches or not
        if(empty($postData['old_password'])) {
            $errors = array('error' => "Please provide password!");
            return redirect()->back()->withInput()->withErrors($errors);
        }
        if(empty($postData['id'])) {
            $errors = array('error' => "Oops, incomplete information provided!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        if(!empty($postData['new_password'])) {
            if($postData['new_password'] !== $postData['re_pwd']) {
                $errors = array('error' => "Password and Confirm Password fields doesn't match!");
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }

        $where = array(
            'id' => $postData['id'],
            'password' => md5($postData['old_password'])
        );

        $user = User::where($where)->first();
        if(empty($user)) {
            $errors = array('error' => "Oops, wrong password provided!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $data = array(
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'mobile' => $postData['mobile'],
            'email' => !empty($postData['email']) ? $postData['email'] : NULL,
            'identity_document_number' => $postData['identity_document_number']
        );

        if(!empty($postData['new_password']))
            $data['password'] = md5($postData['new_password']);

        $user = User::where('id',$postData['id'])->update($data);

        Session::flash('message', 'Your profile has been updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('policyHolder/edit');
    }

    public function forgotPassword(Request $request)
    {
        $postData = $request->input();
        $policyHolder = User::where('mobile', $postData['cell_number'])->with('roles')->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->first();

        if(empty($policyHolder)) {

            $errors = array('error' => "Oops, wrong cell number provided!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Create a random 4 digit code to send to user for verification
        $token = strtoupper(substr(md5(rand()), 0, 5));

        /*--------------------------SENDING SMS ALERT(TEMP CODE) STARTS---------------------------------*/
        $message = "Show My Claims: Your password reset code is: $token";
        $message = urlencode($message);

        $postFields = array(
            'key' => 'gHWVUW15',
            'type' => 'text',
            'contacts' => $postData['cell_number'],
            'senderid' => 'WITSPREP',
            'msg' => $message
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://148.251.196.36/app/smsjsonapi");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);
        $response = json_decode($response, true);

        if(empty($response[0]) || $response[0]->status !== 'success') {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        /*--------------------------SENDING SMS ALERT(TEMP CODE) ENDS-----------------------------------*/

        // Send this token to the user and also save it in the system for verification
        $upData = array(
            'reset_password_token' => $token,
            'reset_password_token_date' => date('Y-m-d H:i:s')
        );
        $update = User::where('mobile', $postData['cell_number'])->update($upData);
        if($update) {
            Session::flash('message', 'A code has been sent to your cell number. Please enter it in the below field');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }

        return view('policyholder.reset_password')->with(['id' => $policyHolder['id']]);
    }

    /*public function verifyToken(Request $request)
    {
        $postData = $request->input();
        $where = array('id' => $postData['user_id'], 'reset_password_token' => $postData['verification_code']);
        $user = User::where($where)->first();
        if(empty($user)) {
            $errors = array('error' => "Oops, wrong token provided!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $tokenTime = new \DateTime($user['reset_password_token_date']);
        $difference = $tokenTime->diff(new \DateTime(date('Y-m-d H:i:s')));
        if($difference->i > 15) {
            $errors = array('error' => "Oops, your token has been expired. Please request it again!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        return view('policyholder.change_password')->with(['id' => $user['id']]);
    }*/

    public function updatePassword(Request $request)
    {

        $postData = $request->input();

        if(empty($postData['user_id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('policyHolder/');
        }

        $user = User::where('id', $postData['user_id'])->update(array('password' => md5($postData['password'])));
        if($user) {
            Session::flash('message', 'Your password has been updated successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect('policyHolder/login');
    }

    public function verifyToken(Request $request)
    {
        $postData = $request->input();
        if(empty($postData) || empty($postData['token'])) {
            print_r(json_encode(array('status' => 'error', 'msg' => 'Token field is required!')));
            die;
        }

        $where = array('id' => $postData['user_id'], 'reset_password_token' => $postData['token']);
        $user = User::where($where)->first();
        if(empty($user)) {
            print_r(json_encode(array('status' => 'error', 'msg' => 'Oops, wrong token provided!')));
            die;
        }

        $tokenTime = new \DateTime($user['reset_password_token_date']);
        $difference = $tokenTime->diff(new \DateTime(date('Y-m-d H:i:s')));
        if($difference->i > 15 || $difference->d > 0) { // Token expiration time is set for 15 minutes
            print_r(json_encode(array('status' => 'error', 'msg' => 'Oops, your token has been expired. Please request a new one!')));
            die;
        }

        print_r(json_encode(array('status' => 'success', 'msg' => 'Token verified!')));
        die;
    }

    public function paymentCancel()
    {
        Session::flash('message', 'User not registered due to payment cancellation!');
        Session::flash('alert-class', 'alert-danger');
        return redirect('policyHolder/');
    }

    public function paymentSuccess()
    {
        Session::flash('message', 'User registered successfully. You can now login!');
        Session::flash('alert-class', 'alert-danger');
        return redirect('policyHolder/');
    }

    public function paymentNotify(Request $request)
    {
        $lineBreak = "\n\r";
        //$fileTxt = $lineBreak . "**************** SP-ID[". $_REQUEST['custom_int1'] ."] *******************";
        $fileTxt = $lineBreak;
        $fileTxt .= 'Date: '.date('Y-m-d H:i:s');
        $fileTxt .= $lineBreak;
        $fileTxt .= 'Request Content: '. $lineBreak . json_encode($_REQUEST);
        $fileTxt .= $lineBreak;
        $fileTxt .= '***********************************' . $lineBreak;

        file_put_contents(base_path().'\storage\app\public\myclaims-payfast-logs-sandbox.txt', $fileTxt, FILE_APPEND);
    }

    private function payfastPayment($cartTotal, $name, $surname,$cellNumber,$productName, $frequency, $userID)
    {

        $baseUrl = URL::to('/');
        //$cartTotal = 10.00;// This amount needs to be sourced from your application
        $data = array(
            // Merchant details
            'merchant_id' => '10012141',
            'merchant_key' => '7goueleoh3b0m',
            'return_url' => $baseUrl . '/payfast-success',
            'cancel_url' => $baseUrl . '/payfast-cancel',
            'notify_url' => $baseUrl . '/payfast-notify',
            // Buyer details
            'name_first' => $name,
            'name_last'  => $surname,
            'cell_number'=> $cellNumber,
            // Transaction details
            'm_payment_id' => rand(100,1000), //Unique payment ID to pass through to notify_url
            'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
            'item_name' => $productName,
            'custom_int1' => (int) $userID,
            'payment_method' => 'eft',
            'subscription_type' => 1,
            'billing_date' => date('Y-m-d'),
            'frequency' => (int) $frequency,
            'cycles' => 0
        );

        $signature = $this->generateSignature($data, 'Testpassphrase123');
        $data['signature'] = $signature;

        // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
        $testingMode = true;
        $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
        $htmlForm = '<form id="myForm" action="https://'.$pfHost.'/eng/process" method="post">';
        foreach($data as $name=> $value)
        {
            $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.$value.'" />';
        }
        $htmlForm .= '<input type="submit" class="btn custom_btn_form" value="Pay Now" /></form>';
        return $htmlForm;
        //echo $htmlForm;die;
    }

    private function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if(!empty($val)) {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( $passPhrase !== null ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        return md5( $getString );
    }

    private function createFileUrl($path)
    {
        return URL::to('/').Storage::url($path);
    }
}
