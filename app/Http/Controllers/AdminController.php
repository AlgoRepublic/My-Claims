<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\Blogs;
use App\Claims;
use App\Contact;
use App\PaymentPackages;
use App\Policies;
use App\Roles;
use App\Settings;
use App\User;
use App\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Get data for counter cards
        $users = User::with('roles')->where('archived', 0)->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->get();

        //Both Active and Inactive PolicyHolder...
        /*$users = User::with('roles')->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->get();*/

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
        $postData = $request->input();
        if(!empty($postData['id'])) { // Detail Page Request
            $policyHolder = User::find($postData['id']);
            // Get the list of beneficiaries
            $beneficiaries = Beneficiaries::where('added_by', $postData['id'])->get();
            //Get the list of policies added by this policyholder
            $policies = Policies::where('added_by', $postData['id'])->get();

            $data = array(
                'username' => $policyHolder['name'] . ' ' . $policyHolder['surname'],
                'documentNumber' => $policyHolder['identity_document_number'],
                'beneficiaries' => $beneficiaries,
                'policies' => $policies,
                'policyHolderID' => $postData['id']
            );
            return view('admin.policyholder_detail')->with($data);
        }
        $policyHolders = User::with('roles')->where('archived', 0)->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->get();
        $data = array(
            'policyHolders' => $policyHolders
        );
        return view('admin.policy_holders')->with($data);
    }
    
    public function deletedPolicyHolder(Request $request)
    {

        $deletedPolicyHolders = User::with('roles')->where('archived', 1)->whereHas('roles', function ($q) {
            $q->where('role_name', '=', 'policyholder');
        })->get();

        $data = array(
            'deletedPolicyHolders' => $deletedPolicyHolders
        );
        return view('admin.deleted_policy_holders')->with($data);
    }

    public function permanentlyDeletePolicyHolder(Request $request)
    {
        $postData = $request->input();

        if (empty($postData['id'])) {
            $errors = array('error' => "Oops, wrong user id supplied!");
            return redirect()->back()->withInput()->withErrors($errors);
        } else {
            $result = User::find($postData['id'])->delete();
            if ($result) {
                Session::flash('message', 'User successfully deleted!');
                Session::flash('alert-class', 'alert-success');
            } else {
                Session::flash('message', 'Oops, something went wrong!');
                Session::flash('alert-class', 'alert-danger');
            }
        }
        return redirect()->back();
    }

    public function beneficiaries(Request $request)
    {
        $beneficiaries = Beneficiaries::all();
        $data = array(
            'beneficiaries' => $beneficiaries
        );

        return view('admin.beneficiaries')->with($data);
    }

    public function pendingClaims(Request $request)
    {
        //$claims = Beneficiaries::whereNotNull('beneficiary_request_date')->where('is_approved', 0)->get();
        $claims = Claims::whereNotNull('beneficiary_request_date')->where('is_approved', 0)->get();
        return view('admin.pending_claims')->with(array('claims' => $claims));
    }

    public function approvedClaims(Request $request)
    {
        //$claims = Beneficiaries::where('is_approved', 1)->get();
        $claims = Claims::where('is_approved', 1)->get();
        return view('admin.approved_claims')->with(array('claims' => $claims));
    }

    public function declinedClaims(Request $request)
    {
        //$claims = Beneficiaries::where('is_approved', 2)->get();
        $claims = Claims::where('is_approved', 2)->get();
        return view('admin.declined_claims')->with(array('claims' => $claims));
    }

    public function updateBeneficiaryClaimStatus(Request $request)
    {
        $postData = $request->input();
        if(empty($postData['id']) || empty($postData['type'])) {

            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $status = 0;
        if($postData['type'] == 'approved')
            $status = 1;
        elseif ($postData['type'] == 'declined')
            $status = 2;

        $updateData = array(
            'is_approved' => $status,
            'approved_by' => Auth::user()->id,
            'approved_date' => date("Y-m-d H:i:s")
        );

        $ben = Claims::where('id',$postData['id'])->update($updateData);
        if($ben) {
            Session::flash('message', 'Claims status updated successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

    public function contactRequests(Request $request)
    {
        $contactRequests = Contact::orderBy('id', 'DESC')->get();
        return view('admin.contact_requests')->with(array('requests' => $contactRequests));
    }

    public function whatWeDo(Request $request)
    {
        $settings = Settings::first();
        return view('admin.what_we_do')->with(array('settings' => $settings));
    }

    public function updateWhatWeDo(Request $request)
    {
        $postData = $request->input();
        $data = array('what_we_do' => $postData['what_we_do']);
        if(empty($postData['id'])) {
            $settings = Settings::create($data);
        }else {
            $settings = Settings::where('id',$postData['id'])->update($data);
        }

        if($settings) {
            Session::flash('message', 'Content updated successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->back();
    }

    public function blogs(Request $request)
    {
        $postData = $request->input();
        if(!empty($postData['id'])) { // Blog detail request
            // Get blog other details
            $blog = Blogs::find($postData['id']);
            return view('admin.add_blog')->with(['blog' => $blog]);
        }

        $blogs = Blogs::orderBy('id', 'DESC')->get();
        return view('admin.blogs')->with(array('blogs' => $blogs));
    }

    public function addPolicyView(Request $request)
    {
        $postData = $request->input();
        if(empty($postData['id'])) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        return view('admin.add_policy')->with(['policyholder_id' => $postData['id']]);
    }

    public function manualPaymentRequests(Request $request)
    {
        $users = User::with('payment')->where('payment_verified', 0)->whereHas('payment', function($q){
            $q->where('payment_method','=','manual');
        })->get();
        return view('admin.manual_payments', ['policyHolders' => $users]);
    }

    public function verifyPayment(Request $request)
    {
        $postData = $request->input();
        if(empty($postData['policyholder_id'])) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        // Get user selected payment package first
        $user = User::find($postData['policyholder_id']);
        $package = PaymentPackages::find($user->payment->package_id);
        if(empty($user) || empty($package)) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $newExpirationDate = $this->createExpirationDate($postData['received_date'], $package['period']);

        $userUpd = User::where('id',$postData['policyholder_id'])->update(['payment_verified' => 1]);
        $payUpd = UserPayment::where('user_id',$postData['policyholder_id'])->update(['expiration_date' => $newExpirationDate]);
        if($userUpd && $payUpd) {
            Session::flash('message', 'Policyholder payment verified successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return redirect()->back();
    }

    public function editPolicyHolder(Request $request)
    {
        $user = User::find($request->id);
        return view('admin.edit_policyholder', ['userData' => $user]);
    }

    public function deletePolicyHolder(Request $request)
    {
        if(empty($request->id)) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $user = User::where('id', $request->id)->update(['archived' => 1]);
        if($user) {
            Session::flash('message', 'Policyholder deleted successfully!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->back();
    }

    public function addBlog(Request $request)
    {
        $postData = $request->input();
        $blogImage = '';
        $verb = 'created';

        if(!empty($_FILES['image']['name']))
            $blogImage = $request->file('image')->store('public/blogs'); //$blogImage = Storage::putFile('public/blogs', $request->file('image'));

        $data = array(
            'title' => $postData['title'],
            'content' => $postData['content'],
            'added_by' => Auth::user()->id,
            'status' => ($postData['status'] == '1' ? 1 : 0)

        );

        if(!empty($blogImage))
            $data['image'] = $blogImage;

        if(!empty($postData['id'])) {
            $blog = Blogs::where('id', $postData['id'])->update($data);
            $verb = 'updated';
        } else
            $blog = Blogs::create($data);

        if($blog) {
            Session::flash('message', "Blog $verb successfully!");
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', "Oops, something went wrong!");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect('admin/blogs');
    }

    public function logout(Request $request) {

        Auth::logout();
        Session::flush();
        return redirect('admin/');
    }

    public function deleteBlog(Request $request)
    {
        $postData = $request->input();
        if(!empty($postData['id'])){
            $blog = Blogs::find($postData['id']);
            $blog->delete();
        }

        Session::flash('message', 'The selected blog has been deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('/admin/blogs');
    }

    private function createExpirationDate($currentExpiry, $period) {
        return date("Y-m-d", strtotime($currentExpiry . '+' .$period));
    }
}
