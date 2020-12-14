<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\beneficiary_policy;
use App\PaymentDetails;
use App\PaymentLogs;
use App\PaymentPackages;
use App\Policies;
use App\Settings;
use App\User;
use App\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use function Psy\bin;

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
        /*if(!empty($userData->payment))
        {
            if($userData->payment->payment_method == 'free_trail')
            {
                $data['trail_expiration_date'] = $userData->payment->expiration_date;
            }
        }*/
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

        /*if(!empty($user->payment))
        {
            if($user->payment->payment_method == 'free_trail')
            {
                $expiryDate = date('Y-m-d', strtotime($user->payment->expiration_date. ' + 1 days'));
                if(strtotime(date('Y-m-d')) >= strtotime($expiryDate))
                { // Free trail has been expired
                    $msg = 'Oops, Your free trail period is over. To use the application, please proceed to payment.';
                    $packages = PaymentPackages::orderBy('amount','ASC')->get();
                    foreach ($packages as $key => $package)
                    {
                        if($package->type === 'free_trail')
                        {
                            unset($packages[$key]);
                            break;
                        }
                    }
                    return view('policyholder.update_payment')->with(['packages' => $packages, 'msg' => $msg, 'user_id' => $user['id']]);
                }
            }
        }*/

        // Now check if user payment has been made
        /*if(empty($user->payment)) { // Take user to the payment page for missed payment

            $msg = "Your monthly/annual subscription has not been paid. Please keep in mind that your beneficiaries will not be able to access your information if your subscription has not been paid.";
            $packages = PaymentPackages::orderBy('amount','ASC')->get();
            return view('policyholder.update_payment')->with(['packages' => $packages, 'msg' => $msg, 'user_id' => $user['id']]);
        }*/

        /*$expiryDate = date('Y-m-d', strtotime($user->payment->expiration_date. ' + 1 days'));
        // Check if user is manual payment user
        if($user->payment->payment_method == 'manual' && $user->payment_verified == 0) {
            if(strtotime(date('Y-m-d')) >= strtotime($expiryDate)) { // Manual Payment has been expired
                $errorMsg = "Oops, your manual payment has not been verified yet. Please make sure that you have sent us the payment slip at `billing@showmyclaims.com`.";
                $errorMsg .= " Please contact us in case of any confusion.";
                $errors = array('error' => $errorMsg);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }*/

        /*if(strtotime(date('Y-m-d')) >= strtotime($expiryDate)) {

            $msg = "Your monthly/annual subscription has not been paid. Please keep in mind that your beneficiaries will not be able to access your information if your subscription has not been paid.";
            $packages = PaymentPackages::orderBy('amount','ASC')->get();
            return  view('policyholder.update_payment')->with(['packages' => $packages, 'msg' => $msg, 'user_id' => $user['id'], 'sub_again' => 1]);
        }*/

        // Authenticate user here
        Auth::login($user);
        return redirect('/policyHolder');
    }

    public function completeRegistration(Request $request)
    {
        $postData = $request->input();
        if(empty($postData['user_id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('policyHolder/login');
        }

        $user = User::find($postData['user_id']);
        if(empty($user)) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('policyHolder/login');
        }

        $package = PaymentPackages::find($postData['package']);
        if(empty($package)) {
            $errors = array('error' => "Oops, invalid package selected!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        if($postData['payment_method'] == 'manual') { // If user has selected manual payment then send him an email with account details

            if(!empty($user->email)) {
                $settings = Settings::first();
                $bankDetails = $settings['bank_details'];
                $to = $user->email;
                $toName = $user->name.' '.$user->surname;
                Mail::send('mail_manual_payment', ['bankDetails' => $bankDetails], function($message) use ($to, $toName) {
                    $message->to($to, $toName)->subject
                    ('Banking Details for Manual Payment - Show My Claims');
                    $message->from('info@showmyclaims.com','My Claims');
                });
            }

            // Substracting one day from current date because we have added cushion of one day while logging in
            $expiryDate = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days'));
            // Add record in user payment table
            $userPayment = array(
                'user_id' => $user->id,
                'package_id' => $package['id'],
                'expiration_date' => $expiryDate,
                'token' => 0,
                'payment_method' => 'manual'
            );

            UserPayment::create($userPayment);
            Session::flash('message', 'An email with the banking details has been sent successfully. Please pay the manual fee to proceed!');
            Session::flash('alert-class', 'alert-success');
            return redirect('policyHolder/');
        }

        $subAgain = !empty($postData['sub_again']) ? 1 : 0;
        $package = PaymentPackages::find($postData['package']);
        $htmlForm = $this->payfastPayment($package['amount'], $user['name'], $user['surname'], $user['mobile'], 'Show My Claims', $package['frequency'], $user['id'], $package['id'], $package['period'], $postData['payment_method'], $subAgain);
        $msg = "Your monthly/annual subscription has not been paid. Please keep in mind that your beneficiaries will not be able to access your information if your subscription has not been paid.";
        return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm, 'msg' => $msg]);
    }

    public function registerView()
    {
        // Get list of packages added in the system
        $packages = PaymentPackages::orderBy('amount','ASC')->get();
        // Sorting type 'free_trail' to first index...
        /*foreach ($packages as $key => $package)
        {
            if($package->type === 'free_trail')
            {
                $temp_package = $package;
                unset($packages[$key]);
                $packages->prepend($temp_package);
                break;
            }
        }*/
        return view('policyholder.register')->with(['packages' => $packages]);
    }

    public function register(Request $request)
    {
        $postData = $request->input();
        if($postData['password'] !== $postData['re_pwd']) {
            $errors = array('error' => "Password and Confirm Password fields doesn't match!");
            return redirect()->back()->withInput()->withErrors($errors);
        }

        /*$package = PaymentPackages::find($postData['package']);
        if(empty($package)) {
            $errors = array('error' => "Oops, invalid package selected!");
            return redirect()->back()->withInput()->withErrors($errors);
        }*/

        $data = array(
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'mobile' => $postData['mobile'],
            'email' => !empty($postData['email']) ? $postData['email'] : NULL,
            'role_id' => 2,
            'password' => md5($postData['password']),
            'identity_document_number' => $postData['identity_document_number'],
            'payment_verified' => 0,
//            'package_id' => $package['id']
        );

        $user = User::create($data);
        $user->save();

        /*--------------------------SENDING SMS ALERT(TEMP CODE) STARTS---------------------------------*/
        $message = "Welcome\n
                    Thank you for signing up to Show My Claims. By signing up, you acknowledge that you signed up willingly. You also acknowledge that any policy information you add is true, correct and updated. Show my claims will not be held accountable for inactive policies.";
        $message = urlencode($message);

        $postFields = array(
            'key' => '9kBnMC7U',
            "type" => "text",
            'contacts' => ($postData['mobile'][0] == "0" ? "27" . substr($postData['mobile'], 1) : "27" . $postData['mobile']),
            'senderid' => 'SHOWMYCLAIMS',
            'msg' => $message
        );

        //Old
        /*$postFields = array(
            'key' => 'gHWVUW15',
            "type" => "text",
            'contacts' => $postData['cell_number'],
            'senderid' => 'WITSPREP',
            'msg' => $message
        );*/

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://148.251.196.36/app/smsjsonapi");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);
        $response = json_decode($response, true);

        return redirect('policyHolder/');

        /*if($package['type'] === 'free_trail')
        {
            $expiryDate = date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 days'));
            // Add record in user payment table
            $userPayment = array(
                'user_id' => $user->id,
                'package_id' => $package['id'],
                'expiration_date' => $expiryDate,
                'token' => 0,
                'payment_method' => 'free_trail'
            );

            UserPayment::create($userPayment);
            Session::flash('message', 'You are using free trail of 7 days!');
            Session::flash('alert-class', 'alert-success');
            return redirect('policyHolder/');
        }*/

//        if($postData['payment_method'] == 'manual') { // If user has selected manual payment then send him an email with account details
//
//            if(!empty($postData['email'])) {
//                $settings = Settings::first();
//                $bankDetails = $settings['bank_details'];
//                $to = $postData['email'];
//                $toName = $postData['name'].' '.$postData['surname'];
//
//                Mail::send('mail_manual_payment', ['bankDetails' => $bankDetails], function($message) use ($to, $toName) {
//                    $message->to($to, $toName)->subject
//                    ('Banking Details for Manual Payment - Show My Claims');
//                    $message->from('info@showmyclaims.com','My Claims');
//                });
//                /*Mail::send('mail_manual_payment', ['bankDetails' => $bankDetails], function($message) use ($to, $toName) {
//                    $message->to($to, $toName)->subject
//                    ('Banking Details for Manual Payment - Show My Claims');
//                    $message->from('info@myclaims.com','My Claims');
//                });*/
//            }
//
//            // Substracting one day from current date because we have added cushion of one day while logging in
//            $expiryDate = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days'));
//            // Add record in user payment table
//            $userPayment = array(
//                'user_id' => $user->id,
//                'package_id' => $package['id'],
//                'expiration_date' => $expiryDate,
//                'token' => 0,
//                'payment_method' => 'manual'
//            );
//
//            UserPayment::create($userPayment);
//            Session::flash('message', 'An email with the banking details has been sent successfully. Please pay the manual fee to proceed!');
//            Session::flash('alert-class', 'alert-success');
//            return redirect('policyHolder/');
//        }

        /*$htmlForm = $this->payfastPayment($package['amount'], $postData['name'], $postData['surname'], $postData['mobile'], 'Show My Claims', $package['frequency'], $user->id, $package['id'], $package['period'], $postData['payment_method']);
        return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm]);*/
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

    public function checkBeneficiary(Request $request)
    {
        $postData = $request->input();

        if(empty($postData) || empty($postData['type']) || empty($postData['col_value'])) {
            print_r(json_encode(array('status' => 'error', 'msg' => 'Required!')));
            die;
        }

        $benCol = ($postData['type'] == 'mobile') ? 'cell_number' : $postData['type'];

        $user = Auth::user();

        if($postData['col_value'] == $user[$postData['type']] ) {
            print_r(json_encode(array('status' => 'error', 'msg' => 'Sorry, you can not add yourself as Beneficiary!')));
            die;
        }

        $beneficiaries = $user->beneficiaries()->where([$benCol => $postData['col_value']])->get();

        if(count($beneficiaries) > 0)
            print_r(json_encode(array('status' => 'error', 'msg' => 'User with this number already exists!')));
        else
            print_r(json_encode(array('status' => 'success', 'msg' => 'Verified!')));
        die;
    }

    public function addBeneficiaryOrPolicy(Request $request)
    {
        $user = Auth::user();
        $postData = $request->input();
        // Now check if user payment has been made
        if(empty($user->payment)) { // Take user to the payment page for missed payment
            $msg = "To upload a policy document or add a beneficiary, please pay your monthly or annual subscription.";
            $packages = PaymentPackages::orderBy('amount','ASC')->get();
            return view('policyholder.update_payment')->with(['packages' => $packages, 'msg' => $msg, 'user_id' => $user['id']]);
        }

        $expiryDate = date('Y-m-d', strtotime($user->payment->expiration_date. ' + 1 days'));
        // Check if user is manual payment user
        if($user->payment->payment_method == 'manual' && $user->payment_verified == 0) {
            if(strtotime(date('Y-m-d')) >= strtotime($expiryDate)) { // Manual Payment has been expired
                $errorMsg = "Oops, your manual payment has not been verified yet. Please make sure that you have sent us the payment slip at `billing@showmyclaims.com`.";
                $errorMsg .= " Please contact us in case of any confusion.";
                $errors = array('error' => $errorMsg);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }

        if(strtotime(date('Y-m-d')) >= strtotime($expiryDate)) {
            $msg = "To upload a policy document or add a beneficiary, please pay your monthly or annual subscription.";
            $packages = PaymentPackages::orderBy('amount','ASC')->get();
            return  view('policyholder.update_payment')->with(['packages' => $packages, 'msg' => $msg, 'user_id' => $user['id'], 'sub_again' => 1]);
        }

        if($postData['request_for'] == 'policy') {
            return view('policyholder.add_policy');
        }else {
            if($postData['request_for'] == 'beneficiary'){
                return view('beneficiary.add_beneficiary');
            }
        }
        return 0;
    }

    public function addPolicy(Request $request)
    {
        $postData = $request->input();
        $path = '';
        $beneficiaryData = $policyBen = array();
        $benIDs = array();

        if(empty($postData['institute_name']) || empty($postData['policy_type']))
        {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('policyHolder');
        }

        if(!empty($postData['source']) && $postData['source'] == 'admin') {
            if(empty($postData['policyholder_id'])) {
                Session::flash('message', 'Oops, something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return redirect('admin/addPolicy');
            }
            $addedBy = $postData['policyholder_id'];
            $addedByType = 'admin';
            $redirect = 'admin/policyHolders';
        }
        else {
            $addedBy = Auth::user()->id;
            $addedByType = 'policyholder';
            $redirect = 'policyHolder/';
        }

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
            'name' => 'null',
            'institute_name' => $postData['institute_name'],
            'type' => $postData['policy_type'],
            'policy_number' => $postData['policy_number'],
            'document' => $path,
            'document_original_name' => $_FILES['doc_file']['name'],
            'added_by' => $addedBy,
            'added_by_type' => $addedByType
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
        return redirect($redirect);
    }

//    public function addPolicy(Request $request)
//    {
//        $postData = $request->input();
//        $path = '';
//        $beneficiaryData = $policyBen = array();
//        $benIDs = array();
//
//        if(!empty($postData['source']) && $postData['source'] == 'admin') {
//            if(empty($postData['policyholder_id'])) {
//                Session::flash('message', 'Oops, something went wrong!');
//                Session::flash('alert-class', 'alert-danger');
//                return redirect('admin/addPolicy');
//            }
//            $addedBy = $postData['policyholder_id'];
//            $addedByType = 'admin';
//            $redirect = 'admin/policyHolders';
//        }
//        else {
//            $addedBy = Auth::user()->id;
//            $addedByType = 'policyholder';
//            $redirect = 'policyHolder/';
//        }
//
//        if(!empty($_FILES['doc_file']['name']))
//            $path = $request->file('doc_file')->store('public/policies');//$path = Storage::putFile('public/policies', $request->file('doc_file'));
//
//        if(!empty($postData['bene_name'])) {
//
//            for($i=0; $i < count($postData['bene_name']); $i++) {
//                $beneficiaryData['name'] = $postData['bene_name'][$i];
//                $beneficiaryData['surname'] = $postData['bene_surname'][$i];
//                $beneficiaryData['identity_document_number'] = $postData['bene_document_number'][$i];
//                $beneficiaryData['cell_number'] = $postData['bene_cell_number'][$i];
//                $beneficiaryData['added_by'] = Auth::user()->id;
//                $beneficiary = Beneficiaries::create($beneficiaryData);
//                $benIDs[] = $beneficiary->id;
//            }
//        }
//
//        //$allBeneficiaries = array_merge($postData['beneficiaries'],$benIDs);
//        $data = array(
//            'name' => $postData['doc_name'],
//            'type' => $postData['policy_type'],
//            'document' => $path,
//            'document_original_name' => $_FILES['doc_file']['name'],
//            'added_by' => $addedBy,
//            'added_by_type' => $addedByType
//        );
//
//        $newPolicy = Policies::create($data);
//        $newPolicy->save();
//
//        /*$policyID = $newPolicy->id;
//        $n = 0;
//        foreach ($allBeneficiaries as $ben) {
//            $policyBen[$n]['policy_id'] = $policyID;
//            $policyBen[$n]['beneficiary_id'] = $ben;
//            $n++;
//        }
//
//        if(!empty($policyBen))
//            beneficiary_policy::insert($policyBen);*/
//
//        Session::flash('message', 'Policy added successfully!');
//        Session::flash('alert-class', 'alert-success');
//        return redirect($redirect);
//    }

    public function addPolicyView()
    {
        // Get the beneficiaries list to show to user
        $benList = Beneficiaries::where('added_by', Auth::user()->id)->get();
        return view('policyholder.add_policy')->with('benList', $benList);
    }

    public function editPolicyView(Request $request)
    {
        $postData = $request->input();
        if(!empty($postData['id'])){
            $policy = Policies::find($postData['id']);
            return view('policyholder.edit_policy')->with('policy', $policy);
        }
        Session::flash('message', 'The selected policy has been deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function editPolicy(Request $request)
    {
        $postData = $request->input();

        if(!empty($postData['id']) && !empty($postData['institute_name']) && !empty($postData['policy_type']))
        {
            $path = '';
            $beneficiaryData = $policyBen = array();
            $benIDs = array();

            if(!empty($postData['source']) && $postData['source'] == 'admin') {
                $redirect = 'admin/policyHolders';
            }
            else {
                $redirect = 'policyHolder/';
            }

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

            $policy = Policies::find($postData['id']);
            $policy->institute_name = $postData['institute_name'];
            $policy->type = $postData['policy_type'];
            $policy->policy_number = $postData['policy_number'];
            if(!empty($path) && !empty($_FILES['doc_file']['name'])){
                $policy->document = $path;
                $policy->document_original_name = $_FILES['doc_file']['name'];
            }
            $policy->save();

            /*$policyID = $newPolicy->id;
            $n = 0;
            foreach ($allBeneficiaries as $ben) {
                $policyBen[$n]['policy_id'] = $policyID;
                $policyBen[$n]['beneficiary_id'] = $ben;
                $n++;
            }

            if(!empty($policyBen))
                beneficiary_policy::insert($policyBen);*/

            Session::flash('message', 'Policy edit successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect($redirect);
        }else{
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('policyHolder');
        }
    }

    public function deletePolicyDocument(Request $request)
    {
        $postData = $request->input();
        $policy = Policies::find($postData['policy_id']);
        $policy->document = "";
        $policy->document_original_name = "";
        $policy->save();
        return response()->json(["message" => "Document Deleted"]);
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
        if(!empty($postData['package']) && ($postData['package'] != $userData->payment->package_id || $postData['payment_method'] != $userData->payment->payment_method)) {

            // As user have changed the package call the payfast update api
            // Get package details
            $package = PaymentPackages::find($postData['package']);
            if(empty($package) || empty($userData->payment)){
                Session::flash('message', 'Oops, could not update package!');
                Session::flash('alert-class', 'alert-danger');
                return redirect('policyHolder/edit');
            }

            if($postData['payment_method'] == 'manual') { // If user has selected manual payment then send him an email with account details

                if(!empty($postData['email'])) {
                    $settings = Settings::first();
                    $bankDetails = $settings['bank_details'];
                    $to = $postData['email'];
                    $toName = $postData['name'].' '.$postData['surname'];

                    Mail::send('mail_manual_payment', ['bankDetails' => $bankDetails], function($message) use ($to, $toName) {
                        $message->to($to, $toName)->subject
                        ('Banking Details for Manual Payment - Show My Claims');
                        $message->from('info@myclaims.com','My Claims');
                    });
                }

                // Add record in user payment table
                $userPayment = array(
                    'package_id' => $package['id'],
                    'token' => 0,
                    'payment_method' => 'manual'
                );

                UserPayment::where('user_id', $postData['id'])->update($userPayment);
                User::where('id', $postData['id'])->update(['payment_verified' => 0]);
                Session::flash('message', 'An email with the banking details has been sent successfully. Please pay the manual fee to proceed!');
                Session::flash('alert-class', 'alert-success');
                return redirect('policyHolder/');
            }

            if($postData['payment_method'] == 'eft') { // Take user to payfast payment gateway

                $htmlForm = $this->payfastPayment($package['amount'], $userData->name, $userData->surname, $userData->mobile, 'Show My Claims', $package['frequency'], $userData->id, $package['id'], $package['period'], $postData['payment_method']);
                $msg = "";
                return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm, 'msg' => $msg]);
            }

            // Here check if user has previously payed eft payment than create user subscription for the first time
            if($userData->payment->payment_method == 'eft') {

                $htmlForm = $this->payfastPayment($package['amount'], $userData->name, $userData->surname, $userData->mobile, 'Show My Claims', $package['frequency'], $userData->id, $package['id'], $package['period'], $postData['payment_method'], 1);
                return view('policyholder.payfast_pay')->with(['htmlForm' => $htmlForm, 'msg' => '']);
            }

            // Just update the payment here because user is already subscribed
            $response = $this->updatePayfastSubscription('update', $package['amount'], 'Show My Claims', $userData->payment->token, $package['period'], $package['frequency'], $userData->id, $package['id']);
            if($response) { // Update user package in user payment

                // Update expiration date as well
                $user = UserPayment::where('user_id', $postData['id'])->first();
                $currentExpirationDate = $user['expiration_date'];
                $newExpirationDate = $this->createExpirationDate($currentExpirationDate, $package['period']);
                UserPayment::where('user_id', $userData->id)->update(['package_id' => $postData['package'], 'expiration_date' => $newExpirationDate]);
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
            'key' => '9kBnMC7U',
            "type" => "text",
            'contacts' => ($postData['cell_number'][0] == "0" ? "27" . substr($postData['cell_number'], 1) : "27" . $postData['cell_number']),
            'senderid' => 'SHOWMYCLAIMS',
            'msg' => $message
        );

        //Old
        /*$postFields = array(
            'key' => 'gHWVUW15',
            "type" => "text",
            'contacts' => $postData['cell_number'],
            'senderid' => 'WITSPREP',
            'msg' => $message
        );*/

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
        if (empty($postData['user_id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/forgot-password');
        }

        if($postData['password'] != $postData['cn_password']){
            Session::flash('message', 'Oops, password and confirm password not matching!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/forgot-password');
        }

        $where = array('id' => $postData['user_id'], 'reset_password_token' => $postData['verification_code']);
        $user = User::where($where)->first();
        if(empty($user)) {
            $errors = array('error' => "Oops, wrong token provided!");
            return redirect()->to('/forgot-password')->withInput()->withErrors($errors);
        }

        $tokenTime = new \DateTime($user['reset_password_token_date']);
        $difference = $tokenTime->diff(new \DateTime(date('Y-m-d H:i:s')));
        if($difference->i > 15) {
            $errors = array('error' => "Oops, your token has been expired. Please request it again!");
            return redirect()->to('/forgot-password')->withInput()->withErrors($errors);
        }

        $user = User::where('id', $postData['user_id'])->update(array('password' => md5($postData['password'])));
        if ($user) {
            Session::flash('message', 'Your password has been updated successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect('policyHolder/login');
    }

    /*public function updatePassword(Request $request)
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
    }*/

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
        Session::flash('message', 'Payment process completed successfully!');
        Session::flash('alert-class', 'alert-success');
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
        $paymentMethod = (empty($content['billing_date']) && empty($content['token'])) ? 'eft' : 'cc';
        $userToken = empty($content['token']) ? 0 : $content['token']; // Token would be empty in case of eft payments
        $subAgain = empty($content['custom_int3']) ? false : true;

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
            'token' => $userToken,
            'payment_method' => $paymentMethod,
            'billing_date' => $billingDate,
            'created_at' => date("Y-m-d H:i:s")
        );

        PaymentDetails::create($paymentDetailArr);

        $userExists = false;
        $user = UserPayment::where('user_id', $userID)->first();
        if(!empty($user)) {
            $currentExpirationDate = $user['expiration_date'];
            if($subAgain) // Set expiration_date from today's date because its the again subscription case
                $currentExpirationDate = date('Y-m-d');

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
            UserPayment::where(['user_id' => $userID])->update(['package_id' => $packageID, 'expiration_date' => $newExpirationDate, 'payment_method' => $paymentMethod,'updated_at' => date('Y-m-d')]);
        } else {
            $userPaymentData = array(
                'user_id' => $userID,
                'package_id' => $packageID,
                'expiration_date' => $newExpirationDate,
                'token' => $userToken,
                'payment_method' => $paymentMethod
            );
            UserPayment::create($userPaymentData);
        }

        // Update user table
        User::where('id', $userID)->update(['payment_verified' => 1]);
    }

    private function createExpirationDate($currentExpiry, $period) {
        return date("Y-m-d", strtotime($currentExpiry . '+' .$period));
    }

    private function payfastPayment($cartTotal, $name, $surname,$cellNumber,$productName, $frequency, $userID, $packageID, $period, $paymentMethod = "", $subAgain = 0)
    {

        $baseUrl = URL::to('/');
        //$cartTotal = 10.00;// This amount needs to be sourced from your application
        $data = array(
            // Merchant details
            'merchant_id' => '16311179',
            'merchant_key' => 'moxa3jyzm5ubx',
//            'merchant_id' => '10012141', // test
//            'merchant_key' => '7goueleoh3b0m', // test
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
            'custom_int3' => (int) $subAgain,
            'custom_str1' => $period
            //'' => 'eft'
            /*'subscription_type' => 1,
            'billing_date' => date('Y-m-d'),
            'frequency' => (int) $frequency,
            'cycles' => 0*/
        );

        if($paymentMethod == 'eft') {
            $data['payment_method'] = 'eft';
        }else {
            $data['subscription_type'] = 1;
            $data['billing_date'] = date('Y-m-d');
            $data['frequency'] = (int) $frequency;
            $data['cycles'] = 0;
        }

//        $signature = $this->generateSignature($data, 'Testpassphrase123');
        $signature = $this->generateSignature($data);
        $data['signature'] = $signature;

        // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
        $testingMode = false;
        //$testingMode = false;
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
                'merchant-id' => '16311179',
//                'merchant-id' => '10012141', // Sandbox Account Merchant
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
                'merchant-id' => '16311179',
//                'merchant-id' => '10012141', // Sandbox Account Merchant
                'token' => $token,
                'version' => 'v1',
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
