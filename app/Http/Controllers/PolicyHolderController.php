<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\beneficiary_policy;
use App\PaymentDetails;
use App\PaymentLogs;
use App\PaymentPackages;
use App\Policies;
use App\User;
use App\UserPayment;
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

        if($user->archived) {
            $errors = array('error' => "Oops, seems like your profile was de-activated by admin. For any confusion please contact us.");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Now check if user payment has been made
        if(empty($user->payment)) { // Take user to the payment page for missed payment

            $package = PaymentPackages::find($user->package_id);
            $htmlForm = $this->payfastPayment($package['amount'], $user['name'], $user['surname'], $user['mobile'], 'Show My Claims', $package['frequency'], $user['id'], $package['id'], $package['period']);
            $msg = "Your payment is missing. Keep in mind that beneficiaries will not be able to any documents if your subscription has not been paid.";
            return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm, 'msg' => $msg]);
        }

        $expiryDate = date('Y-m-d', strtotime($user->payment->expiration_date. ' + 1 days'));
        if(strtotime(date('Y-m-d')) > strtotime($expiryDate)) {

            // Make Tokenization Payment now
            $package = PaymentPackages::find($user->package_id);
            $response = $this->updatePayfastSubscription('adhoc', $package['amount'], 'Show My Claims', $user->payment->token, $package['period'], $package['frequency'], $user->id, $package['id']);
            if($response) { // Update user package in user payment
                Session::flash('message', 'Your missing payment request has been made. Please try to login after few seconds.');
                Session::flash('alert-class', 'alert-success');
                return redirect('policyHolder/login');
            }else {
                Session::flash('message', 'Oops, we could not request due payment of your subscription. Please contact us in case of any confusion.');
                Session::flash('alert-class', 'alert-success');
                return redirect('policyHolder/login');
            }
        }

        // Authenticate user here
        Auth::login($user);
        return redirect('/policyHolder');
    }

    public function registerView()
    {
        // Get list of packages added in the system
        $packages = PaymentPackages::orderBy('amount','ASC')->get();
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
            'payment_verified' => 0,
            'package_id' => $package['id']
        );

        $user = User::create($data);
        $user->save();

        $htmlForm = $this->payfastPayment($package['amount'], $postData['name'], $postData['surname'], $postData['mobile'], 'Show My Claims', $package['frequency'], $user->id, $package['id'], $package['period']);
        return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm]);
    }

    public function checkCell(Request $request)
    {
        $postData = $request->input();
        if(empty($postData) || empty($postData['type']) || empty($postData['col_value'])) {
            print_r(json_encode(array('status' => 'error', 'msg' => 'Required!')));
            die;
        }

        $benCol = ($postData['type'] == 'mobile') ? 'cell_number' : $postData['type'];
        $ben = array();
        $user = User::where($postData['type'], $postData['col_value'])->get();
        if(!empty($postData['ben']))
            $ben = Beneficiaries::where($benCol, $postData['col_value'])->get();

        if(count($user) > 0 || count($ben) > 0 )
            print_r(json_encode(array('status' => 'error', 'msg' => 'User with this number already exists!')));
        else
            print_r(json_encode(array('status' => 'success', 'msg' => 'Verified!')));
        die;
    }

    public function addPolicy(Request $request)
    {
        $postData = $request->input();
        $path = '';
        $beneficiaryData = $policyBen = array();
        $benIDs = array();

        if(!empty($_FILES['doc_file']['name']))
            $path = $request->file('doc_file')->store('public/policies');//$path = Storage::putFile('public/policies', $request->file('doc_file'));

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

        //$allBeneficiaries = array_merge($postData['beneficiaries'],$benIDs);
        $data = array(
            'name' => $postData['doc_name'],
            'type' => $postData['policy_type'],
            'document' => $path,
            'document_original_name' => $_FILES['doc_file']['name'],
            'added_by' => Auth::user()->id
        );

        $newPolicy = Policies::create($data);
        $newPolicy->save();

        /*$policyID = $newPolicy->id;
        $n = 0;
        foreach ($allBeneficiaries as $ben) {
            $policyBen[$n]['policy_id'] = $policyID;
            $policyBen[$n]['beneficiary_id'] = $ben;
            $n++;
        }

        if(!empty($policyBen))
            beneficiary_policy::insert($policyBen);*/

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
        $userData = Auth::user();

        $isAdmin = (!empty($postData['source']) && $postData['source'] == 'admin') ? true : false;

        // First of all check if the provided password matches or not
        if(!$isAdmin) {
            if(empty($postData['old_password'])) {
                $errors = array('error' => "Please provide password!");
                return redirect()->back()->withInput()->withErrors($errors);
            }
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

        if(!$isAdmin) {
            $where = array(
                'id' => $postData['id'],
                'password' => md5($postData['old_password'])
            );

            $user = User::where($where)->first();
            if(empty($user)) {
                $errors = array('error' => "Oops, wrong password provided!");
                return redirect()->back()->withInput()->withErrors($errors);
            }
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

        if($isAdmin) { // Return from here
            Session::flash('message', 'Policyholder has been updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/policyHolders');
        }

        // Handle payment update(if any)
        if(!empty($postData['package']) && $postData['package'] != $userData->payment->package_id) {

            // As user have changed the package call the payfast update api
            // Get package details
            $package = PaymentPackages::find($postData['package']);
            if(empty($package) || empty($userData->payment)){
                Session::flash('message', 'Oops, could not update package!');
                Session::flash('alert-class', 'alert-danger');
                return redirect('policyHolder/edit');
            }

            $response = $this->updatePayfastSubscription('update', $package['amount'], 'Show My Claims', $userData->payment->token, $package['period'], $package['frequency'], $userData->id, $package['id']);
            if($response) { // Update user package in user payment
                UserPayment::where('user_id', $userData->id)->update(['package_id' => $postData['package']]);
            }
        }

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

        if(empty($response[0]->status) || $response[0]->status !== 'success') {
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

        PaymentLogs::create(array('request' => $fileTxt));

        file_put_contents(base_path().'/storage/app/public/img/myclaims-payfast-logs-sandbox.txt', $fileTxt, FILE_APPEND);

        $jsonCont = json_encode($_REQUEST);
        $content = json_decode($jsonCont, true);

        $userID = $content['custom_int1'];
        $packageID = $content['custom_int2'];
        /*$newPayment = empty($content['custom_int3']) ? false : true;
        if($newPayment) { // This is the case where a user prev subscription was expired, so we have to add a new one
            // So in this case delete the old subscription first
            User::where('id', $userID)->delete();
        }*/

        //$nextPayAmount = !empty($content['custom_int3']) ? $content['custom_int3'] : $content['amount_gross'];
        //$newpackageAmount = !empty($content['custom_int4']) ? $content['custom_int4'] : null;
        $period = $content['custom_str1'];
        //$packageSlug = $content['custom_str2'];
        //$paymentType = !empty($content['custom_str3']) ? $content['custom_str3'] : null;
        //$action = !empty($content['custom_str4']) ? $content['custom_str4'] : null;
        $billingDate = empty($content['billing_date']) ? date('Y-m-d') : date('Y-m-d', strtotime($content['billing_date']));

        // First add all details in the table
        $paymentDetailArr = array(
            'user_id' => $userID,
            'm_payment_id' => $content['m_payment_id'],
            'pf_payment_id' => $content['pf_payment_id'],
            'payment_status' => $content['payment_status'],
            'item_name' => $content['item_name'],
            'item_description' => $content['item_description'],
            'amount_gross' => $content['amount_gross'],
            'amount_fee' => $content['amount_fee'],
            'amount_net' => $content['amount_net'],
            'merchant_id' => $content['merchant_id'],
            'token' => $content['token'],
            'billing_date' => $billingDate,
            'created_at' => date("Y-m-d H:i:s")
        );

        PaymentDetails::create($paymentDetailArr);

        $userExists = false;
        $user = UserPayment::where('user_id', $userID)->first();
        if(!empty($user)) {
            $currentExpirationDate = $user['expiration_date'];
            $userExists = true;
        } else {
            $currentExpirationDate = $billingDate;
        }

        /****************************************************************************/
        //This is temporary hack to the update case because of issue on payfast end
        $grossAmount = (int) $content['amount_gross'];
        if($grossAmount == 23)
            $period = "1 Month";
        elseif($grossAmount == 250)
            $period = "1 Year";
        /****************************************************************************/

        $newExpirationDate = $this->createExpirationDate($currentExpirationDate, $period);

        $fileTxt .= $lineBreak;
        $fileTxt .= "New Expiration for this user: " . $newExpirationDate;
        $fileTxt .= $lineBreak;

        if($userExists) {
            UserPayment::where(['user_id' => $userID])->update(['expiration_date' => $newExpirationDate, 'updated_at' => date('Y-m-d')]);
        } else {
            $userPaymentData = array(
                'user_id' => $userID,
                'package_id' => $packageID,
                'expiration_date' => $newExpirationDate,
                'token' => $content['token']
            );
            UserPayment::create($userPaymentData);
        }

        // Update user table
        User::where('id', $userID)->update(['payment_verified' => 1]);
    }

    private function createExpirationDate($currentExpiry, $period) {
        return date("Y-m-d", strtotime($currentExpiry . '+' .$period));
    }

    private function payfastPayment($cartTotal, $name, $surname,$cellNumber,$productName, $frequency, $userID, $packageID, $period)
    {

        $baseUrl = URL::to('/');
        //$cartTotal = 10.00;// This amount needs to be sourced from your application
        $data = array(
            // Merchant details
            'merchant_id' => '16311179',
            'merchant_key' => 'moxa3jyzm5ubx',
            //'merchant_id' => '10012141', // test
            //'merchant_key' => '7goueleoh3b0m', // test
            'return_url' => $baseUrl . '/payfast-success',
            'cancel_url' => $baseUrl . '/payfast-cancel',
            'notify_url' => $baseUrl . '/payfast-notify',
            // Buyer details
            'name_first' => $name,
            'name_last'  => $surname,
            //'cell_number'=> $cellNumber,
            // Transaction details
            'm_payment_id' => rand(100,1000), //Unique payment ID to pass through to notify_url
            'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
            'item_name' => $productName,
            'custom_int1' => (int) $userID,
            'custom_int2' => (int) $packageID,
            'custom_str1' => $period,
            'payment_method' => 'eft',
            'subscription_type' => 1,
            'billing_date' => date('Y-m-d'),
            'frequency' => (int) $frequency,
            'cycles' => 0
        );

        //$signature = $this->generateSignature($data, 'Testpassphrase123');
        $signature = $this->generateSignature($data);
        $data['signature'] = $signature;

        // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
        $testingMode = false;
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

    private function updatePayfastSubscription($action, $newAmount, $itemName, $token, $period, $frequency, $userID, $newPackageID)
    {
        // Add 00 as the provided amount is in cents. DONT DO THIS if you already providing correct values
        $amount = $newAmount . '00'; // Converts amount in RAND from ZAR(cents)

        if($action == 'update') {
            $pfData = array(
                'merchant-id' => '10012141', // Sandbox Account Merchant
                'amount' => (int) $amount,
                'item_name' => $itemName,
                'item_description' => '',
                'token' => $token,
                'version' => 'v1',
                'passphrase' => 'Testpassphrase123',
                'api_action' => $action,
                'cycles' => 0,
                'frequency' => (int) $frequency,
                'custom_int1' => (int) $userID,
                'custom_int2' => (int) $newPackageID,
                //'custom_int3' => $newAmount,
                'custom_str1' => $period
                //'run_date' => date("Y-m-d", strtotime(date('Y-m-d').$period))
            );
        }
        elseif($action == 'cancel') {
            $pfData = array(
                'merchant-id' => '10012141', // Sandbox Account Merchant
                'token' => $token,
                'version' => 'v1',
                'passphrase' => 'Testpassphrase123',
                'api_action' => $action
            );
        }
        elseif($action == 'adhoc') {
            $pfData = array(
                'merchant-id' => '10012141', // Sandbox Account Merchant
                'token' => $token,
                'item_name' => $itemName,
                'version' => 'v1',
                'amount' => (int) $amount,
                'passphrase' => 'Testpassphrase123',
                'api_action' => $action
            );
        }

        //****************************************************************
        // Set timestamp
        $timestamp = date( 'Y-m-d' ) . 'T' . date( 'H:i:s' );
        $pfData['timestamp'] = $timestamp;

        // Sort the array alphabetically by key
        ksort( $pfData );

        // Normalise the array into a parameter string
        $pfParamString = '';
        foreach( $pfData as $key => $val )
        {
            if( !empty($val) && $key != 'api_action' && $key != 'submit' && $key != 'token' )
            {
                $pfParamString .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }

        // Remove the last '&amp;' from the parameter string
        $pfParamString = substr( $pfParamString, 0, -1 );

        // Create the hashed signature from the url-encoded string
        $signature = md5( $pfParamString );

        // Set and display action
        $action = '';

        if ( $pfData['api_action'] )
        {
            $action = $pfData['api_action'];
        }

        $method = $this->setMethod( $action );
        // Check for token
        $token = ( $pfData['token'] ? $pfData['token'] . '/' : '' );

        // Ensure POSTFIELDS does not include unnecessary fields
        $payload = '';
        $exclude = array( 'api_action', 'submit', 'token', 'passphrase', 'version', 'merchant-id', 'timestamp');
        foreach( $pfData as $key => $val )
        {
            if( !empty($val) && !in_array($key, $exclude))
            {
                $payload .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }

        // Remove the last '&amp;' from the payload string
        $payload = substr( $payload, 0, -1 );

        /*$response = Http::withHeaders([
            'X-version' => $pfData['version'],
            'merchant-id' => $pfData['merchant-id'],
            'signature' => $signature,
            'timestamp' => $timestamp
        ])->patch('https://api.payfast.co.za/subscriptions/' . $token . $action . '?testing=true', $payload);*/

        // Configure curl
        $ch = curl_init( 'https://api.payfast.co.za/subscriptions/' . $token . $action . '?testing=true' );
        //$ch = curl_init( 'https://api.payfast.co.za/subscriptions/' . $token . $action);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
        if(!empty($payload)) // In case of cancel,pause & unpause, we don't need to send postfields
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'version: ' . $pfData['version'],
            'merchant-id: ' . $pfData['merchant-id'],
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        ) );

        // Execute and close cURL
        $response = curl_exec($ch);
        curl_close($ch);

        // Display response
        /*echo '<strong>CURL Response: </strong><br>';
        var_dump( $response->body() );
        echo '<br><br><br><br><br><br><br><br><br><br>';
        die;*/
        $response = json_decode($response);
        PaymentLogs::create(['request' => json_encode($response)]);
        if($response->status == 'success')
            return true;
        else
            return false;
    }

    private function setMethod( $action )
    {
        switch ( $action )
        {
            case 'fetch':
                return 'GET';
                break;
            case 'pause':
            case 'unpause':
            case 'cancel':
                return 'PUT';
                break;
            case 'update':
                return 'PATCH';
                break;
            case 'adhoc':
                return 'POST';
                break;
            default:
                break;
        }
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
