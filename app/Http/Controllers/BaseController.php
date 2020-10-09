<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\beneficiary_policy;
use App\Blogs;
use App\Claims;
use App\Contact;
use App\Mail\BeneficiaryVerification;
use App\Policies;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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

        // Get policyholder information
        $user = User::with('Policies')->where('identity_document_number', $postData['policyholder_number'])->first();
        if(empty($user)) {
            Session::flash('message', 'Please note that the ID number entered for the Policyholder is not registered on our system, if you think this is a mistake, please call our support team for assistance.');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }

        /*$benWhere = array(
            'identity_document_number' => $postData['beneficiary_number'],
            'added_by' => $user->id
        );
        // Now check the beneficiary
        $beneficiary = Beneficiaries::where($benWhere)->first();
        if(empty($beneficiary)) {
            Session::flash('message', 'Please note that the ID number entered of Beneficiary is not registered on our system, if you think this is a mistake, please call our support team for assistance.');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }*/

        $policyType = array();

        // As both of the users are verified, now check link of the document
        foreach($user->policies as $policy) {
            /*$linked = beneficiary_policy::where(['policy_id' => $policy->id,'beneficiary_id' => $beneficiary->id])->first();
            if(!empty($linked)) {
                // Add the policy type to show the user
                $policyType[] = $policy->type;
            }*/

            if(!in_array($policy->type, $policyType))
                $policyType[] = $policy->type;
        }

        /*if(empty($policyType)) {
            Session::flash('message', 'Sorry, this policyholder have not registered you as a beneficiary!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }*/

        $data = array(
            'policy_type' => $policyType,
            'name' => $user->name .' '. $user->surname,
            'policyholder_number' => $postData['policyholder_number']
            //'beneficiary_number' => $postData['beneficiary_number'],
            //'ben_id' => $benID
        );
        return view('beneficiary.check_policies')->with($data);
    }

    public function policyRequest(Request $request)
    {
        $postData = $request->input();
        /*if(empty($postData['ben_id'])) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/beneficiary');
        }*/

        $benDoc = $holderDoc = $benFileName = $polFileName = $benFileExt = $polFileExt = '';
        if ($request->hasFile('beneficiary_identity')) {
            //$file= $request->file('beneficiary_identity');
            $benDoc = $request->file('beneficiary_identity')->store('public/beneficiaries_uploads/');
            $benFileName = basename($benDoc);
            $benFileExt = $request->file('beneficiary_identity')->extension();
        }

        /*if(!empty($_FILES['beneficiary_identity']['name'])) {
            echo $benDoc = Storage::putFile('public/beneficiaries_uploads', $request->file('beneficiary_identity'));
            $benFileName = basename($benDoc);
            $benFileExt = $request->file('beneficiary_identity')->extension();
        }*/

        if ($request->hasFile('policy_identity')) {
            //$file= $request->file('policy_identity');
            $holderDoc = $request->file('policy_identity')->store('public/beneficiaries_uploads/');
            $polFileName = basename($holderDoc);
            $polFileExt = $request->file('policy_identity')->extension();
        }

        /*if(!empty($_FILES['policy_identity']['name'])) {
            $holderDoc = Storage::putFile('public/beneficiaries_uploads', $request->file('policy_identity'));
            $polFileName = basename($holderDoc);
            $polFileExt = $request->file('policy_identity')->extension();
        }*/

        $data = array(
            'beneficiary_identity' => $benDoc,
            'policyholder_death_proof' => $holderDoc,
            'email_preference' => $postData['email_preference'],
            'beneficiary_request_date' => date('Y-m-d H:i:s')
        );

        $claim = Claims::create($data);
        //$beneficiary = Beneficiaries::where('id', $postData['ben_id'])->update($data);
        if($claim) {
            $data['policyholder_idn'] = $postData['policyholder_idn'];
            //$data['beneficiary_idn'] = $postData['beneficiary_idn'];
            $data['beneficiary_identity_file'] = $benFileName;
            $data['policyholder_death_proof_file'] = $polFileName;
            $data['ben_file_ext'] = $benFileExt;
            $data['pol_file_ext'] = $polFileExt;

            Mail::to('mashood.ali@algorepublic.com')->send(new BeneficiaryVerification($data));

            Session::flash('message', 'Request submitted successfully!. Please note that verification will take 1 to 24 hours. Once verification is complete, you will receive an email with all the documents.');
            Session::flash('alert-class', 'alert-success');
        }else {
            Session::flash('message', 'Oops. Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect('/beneficiary');
    }

    public function addBeneficiary(Request $request)
    {
        $postData = $request->input();
        $beneficiaryData['name'] = $postData['bene_name'];
        $beneficiaryData['surname'] = $postData['bene_surname'];
        $beneficiaryData['identity_document_number'] = $postData['bene_document_number'];
        $beneficiaryData['cell_number'] = $postData['bene_cell_number'];
        $beneficiaryData['added_by'] = Auth::user()->id;
        $beneficiary = Beneficiaries::create($beneficiaryData);
        if($beneficiary) {
            Session::flash('message', 'Beneficiary has been created successfully!');
            Session::flash('alert-class', 'alert-success');
        }else {
            Session::flash('message', 'Oops. Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect('/policyHolder');
    }

    public function deleteBeneficiary(Request $request)
    {
        $postData = $request->input();
        if(!empty($postData['id'])){
            $ben = Beneficiaries::find($postData['id']);
            $ben->delete();
        }

        Session::flash('message', 'The selected beneficiary has been deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('/policyHolder');
    }

    public function contactUs(Request $request)
    {
        $postData = $request->input();

        $data = array(
            'user_name' => $postData['user_name'],
            'email' => $postData['user_email'],
            'contact_number' => $postData['contact_number'],
            'message' => $postData['msg'],
            'send_to' => 'salman.rahimi@algorepublic.com',
        );

        Contact::create($data);
        Mail::send('mail_contact_us', $postData, function($message) {
            $message->to('salman.rahimi@algorepublic.com', 'Show My Claims')->subject
            ('Contact Request - Show My Claims');
            $message->from('info@myclaims.com','My Claims');
        });

        Session::flash('message', 'Your contact request has been sent!');
        Session::flash('alert-class', 'alert-success');
        return redirect('/contact-us');
    }

    public function editBeneficiary(Request $request)
    {
        $postData = $request->input();
        if(empty($postData['id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
        // Now get data from this table for this id
        $beneficiary = Beneficiaries::find($postData['id']);
        return view('beneficiary.edit_beneficiary')->with('beneficiary',$beneficiary);
    }

    public function updateBen(Request $request)
    {
        $postData = $request->input();
        if(empty($postData['ben_id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect('policyHolder/');
        }

        $data = array(
            'name' => $postData['bene_name'],
            'surname' => $postData['bene_surname'],
            'cell_number' => $postData['bene_cell_number'],
            'identity_document_number' => $postData['bene_document_number']
        );

        Beneficiaries::where('id',$postData['ben_id'])->update($data);

        Session::flash('message', 'Beneficiary has been updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('policyHolder/');
    }

    public function whatWeDo()
    {
        $settings = Settings::first();
        return view('what_we_do')->with(['settings' => $settings]);
    }

    public function blog(Request $request)
    {
        $postData = $request->input();
        if(!empty($postData['id'])) {
            $blog = Blogs::find($postData['id']);
            return view('blog_detail')->with(['blog' => $blog]);
        }
        // Get blog list from db
        $blogs = Blogs::orderBy('id', 'DESC')->get();
        return view('blog')->with(['blogs' => $blogs]);
    }

    public function info()
    {
        phpinfo();
    }
}
