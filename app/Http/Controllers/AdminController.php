<?php

namespace App\Http\Controllers;

use App\Beneficiaries;
use App\Blogs;
use App\BuRole;
use App\BuUser;
use App\Claims;
use App\Company;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Get data for counter cards
        $users = User::with('roles')->where('archived', 0)->whereHas('roles', function ($q) {
            $q->where('role_name', '=', 'policyholder');
        })->get();

        //Both Active and Inactive PolicyHolder...
        /*$users = User::with('roles')->whereHas('roles', function($q){
            $q->where('role_name','=','policyholder');
        })->get();*/

        // Get total monthly claims
        $claims = Beneficiaries::whereBetween('beneficiary_request_date', [date('Y-m-01'), date('Y-m-t')])->get();

        // Get total active users; Users that are not dead yet
        $activeUsers = User::with('beneficiaries')->whereHas('beneficiaries', function ($q) {
            $q->where('is_approved', 0); // this means that there death is not approved in the system hence we take them as an active user
        })->get();

        // Get total monthly users
        $monthlyUsers = User::whereBetween('created_at', [date('Y-m-01'), date('Y-m-t')])->get();

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
        if (empty($postData) || empty($postData['email']) || empty($postData['password'])) {
            $errors = array('error' => "Oops, invalid request!");
            return redirect('/');
        }

        // First get the admin role and its corresponding id
        $role = Roles::where('role_name', 'admin')->first();
        if (empty($role))
            return redirect('/');

        $where = array(
            'email' => $postData['email'],
            'password' => md5($postData['password']),
            'role_id' => $role->id
        );

        $user = User::where($where)->first();
        if (empty($user)) {
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
        if (!empty($postData['id'])) { // Detail Page Request
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
        $policyHolders = User::with('roles')->where('archived', 0)->whereHas('roles', function ($q) {
            $q->where('role_name', '=', 'policyholder');
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

    public function expiredSubscriptionView(Request $request)
    {

        $expired_subscription_users = User::with('roles', 'payment')->where('archived', 0)->whereHas('roles', function ($q) {
            $q->where('role_name', '=', 'policyholder');
        })->get();

        foreach ($expired_subscription_users as $k => $u) {
            if (!empty($u->payment)) {
                $expiration_date = $u->payment->expiration_date;
                $expiryDate = date('Y-m-d', strtotime($expiration_date));
                if (strtotime(date('Y-m-d')) <= strtotime($expiryDate)) {
                    unset($expired_subscription_users[$k]);
                }

            } else {
                unset($expired_subscription_users[$k]);
            }

        }

        /*foreach ($expired_subscription_users as $key => $value)
        {
            echo '<pre>';
            echo $value->payment->expiration_date;
            echo '<br>';

        }
        die;*/
        $data = array(
            'expired_subscription_users' => $expired_subscription_users
        );
        return view('admin.subscriptions_expired')->with($data);
    }

    public function expiredSubscriptionSendSMS(Request $request)
    {
        $postData = $request->input();
        if (empty($postData['id'])) {
            $errors = array('error' => "Oops, wrong user id supplied!");
            return redirect()->back()->withInput()->withErrors($errors);
        } else {
            $userData = User::find($postData['id']);
            /*--------------------------SENDING SMS---------------------------------*/
            $message = "Dear Policyholder,\n
                        Please note that your monthly or annual subscription has not been paid to Show My Claims and as a result, your account has been suspended.\n
                        Please remember to pay your subscription at your earliest convenience because your beneficiaries will not be able to access your policy information until your subscription has been paid.\n
                        Thank you,\n
                        Show My Claims Team";
            $message = urlencode($message);

            $postFields = array(
                'key' => 'gHWVUW15',
                "type" => "text",
                'contacts' => ($userData->mobile[0] == "0" ? "27" . substr($userData->mobile, 1) : "27" . $userData->mobile),
                'senderid' => 'Witsprep',
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
            curl_setopt($ch, CURLOPT_URL, "http://148.251.196.36/app/smsjsonapi");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response, true);

            if (empty($response[0]->status) || $response[0]->status !== 'success') {
                Session::flash('message', 'Oops, something went wrong!');
                Session::flash('alert-class', 'alert-danger');
            } else {
                Session::flash('message', 'SMS successfully sent!');
                Session::flash('alert-class', 'alert-success');
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
        if (empty($postData['id']) || empty($postData['type'])) {

            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $status = 0;
        if ($postData['type'] == 'approved')
            $status = 1;
        elseif ($postData['type'] == 'declined')
            $status = 2;

        $updateData = array(
            'is_approved' => $status,
            'approved_by' => Auth::user()->id,
            'approved_date' => date("Y-m-d H:i:s")
        );

        $ben = Claims::where('id', $postData['id'])->update($updateData);
        if ($ben) {
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
        if (empty($postData['id'])) {
            $settings = Settings::create($data);
        } else {
            $settings = Settings::where('id', $postData['id'])->update($data);
        }

        if ($settings) {
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
        if (!empty($postData['id'])) { // Blog detail request
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
        if (empty($postData['id'])) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        return view('admin.add_policy')->with(['policyholder_id' => $postData['id']]);
    }

    public function editPolicyView(Request $request)
    {
        $postData = $request->input();
        if (!empty($postData['id'])) {
            $policy = Policies::find($postData['id']);
            return view('admin.edit_policy')->with('policy', $policy);
        }
        Session::flash('message', 'Oops, invalid request!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function manualPaymentRequests(Request $request)
    {
        $users = User::with('payment')->where('payment_verified', 0)->whereHas('payment', function ($q) {
            $q->where('payment_method', '=', 'manual');
        })->get();
        return view('admin.manual_payments', ['policyHolders' => $users]);
    }

    public function verifyPayment(Request $request)
    {
        $postData = $request->input();
        if (empty($postData['policyholder_id'])) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        // Get user selected payment package first
        $user = User::find($postData['policyholder_id']);
        $package = PaymentPackages::find($user->payment->package_id);
        if (empty($user) || empty($package)) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $newExpirationDate = $this->createExpirationDate($postData['received_date'], $package['period']);

        $userUpd = User::where('id', $postData['policyholder_id'])->update(['payment_verified' => 1]);
        $payUpd = UserPayment::where('user_id', $postData['policyholder_id'])->update(['expiration_date' => $newExpirationDate]);
        if ($userUpd && $payUpd) {
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
        if (empty($request->id)) {
            Session::flash('message', 'Oops, invalid request!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $user = User::where('id', $request->id)->update(['archived' => 1]);
        if ($user) {
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

        if (!empty($_FILES['image']['name']))
            $blogImage = $request->file('image')->store('public/blogs'); //$blogImage = Storage::putFile('public/blogs', $request->file('image'));

        $data = array(
            'title' => $postData['title'],
            'content' => $postData['content'],
            'added_by' => Auth::user()->id,
            'status' => ($postData['status'] == '1' ? 1 : 0)

        );

        if (!empty($blogImage))
            $data['image'] = $blogImage;

        if (!empty($postData['id'])) {
            $blog = Blogs::where('id', $postData['id'])->update($data);
            $verb = 'updated';
        } else
            $blog = Blogs::create($data);

        if ($blog) {
            Session::flash('message', "Blog $verb successfully!");
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', "Oops, something went wrong!");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect('admin/blogs');
    }

    public function logout(Request $request)
    {

        Auth::logout();
        Session::flush();
        return redirect('admin/');
    }

    public function deleteBlog(Request $request)
    {
        $postData = $request->input();
        if (!empty($postData['id'])) {
            $blog = Blogs::find($postData['id']);
            $blog->delete();
        }

        Session::flash('message', 'The selected blog has been deleted successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('/admin/blogs');
    }

    private function createExpirationDate($currentExpiry, $period)
    {
        return date("Y-m-d", strtotime($currentExpiry . '+' . $period));
    }

    public function company()
    {
        $companies = Company::where(['status' => 1])->get();
        return view('admin.companies')->withCompanies($companies)->withCount(count($companies));
    }

    public function addCompanyView()
    {
        return view('admin.add_company');
    }

    public function addCompany(Request $request)
    {

        $postData = $request->input();

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'manager_name' => 'required',
            'manager_mobile' => 'required|numeric|digits_between:9,13|unique:bu_users,mobile',
            'manager_email' => 'nullable|email|unique:bu_users,email',
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator->fails()) {
            Session::flash('message', 'Please fill all required fields');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $postData['manager_password'] = strtoupper(substr(md5(rand()), 0, 8));

        if (!empty($_FILES['company_logo']['name']))
            $blogImage = $request->file('company_logo')->store('public/companies');

        if (!empty($blogImage))
            $company_data['logo'] = $blogImage;

        $company_data['name'] = $postData['company_name'];

        $company_result = Company::create($company_data);

        if ($company_result) {

            $role = BuRole::where('role_name', 'manager')->first();
            $user_data['name'] = $postData['manager_name'];
            $user_data['mobile'] =  $postData['manager_mobile'];
            $user_data['email'] =  $postData['manager_email'];
            $user_data['password'] =  Hash::make($postData['manager_password']);
            $user_data['bu_role_id'] = $role->id;
            $user_data['bu_company_id'] = $company_result->id;

            $user_result = BuUser::create($user_data);
            if($user_result){

                // Create a random 4 digit code to send to user for verification
                $token = strtoupper(substr(md5(rand()), 0, 8));
                $upData = array(
                    'reset_password_token' => $token,
                    'reset_password_token_date' => date('Y-m-d H:i:s')
                );
                $user_update = $user_result->update($upData);
                if($user_update){
                    if(!empty($user_result->email)) {
                        $to = $user_result->email;
                        $toName = $user_result->name;
                        Mail::send('business_reset_password', ['current_password' => $postData['manager_password'], 'url' => route('buChangePasswordView', ['reset_token' => $token])], function($message) use ($to, $toName) {
                            $message->to($to, $toName)->subject
                            ('Change Password Details - Show My Claims');
                            $message->from('info@showmyclaims.com','My Claims');
                        });
                    }
                }

                /*--------------------------SENDING SMS ALERT(TEMP CODE) STARTS---------------------------------*/
                /*$message = "Show My Claims: Your Current password is: " . $postData['manager_password'] . " Your password reset code is: $token";
                $message = urlencode($message);

                $postFields = array(
                    'key' => '9kBnMC7U',
                    "type" => "text",
                    'contacts' => ($postData['manager_mobile'][0] == "0" ? "27" . substr($postData['manager_mobile'], 1) : "27" . $postData['manager_mobile']),
                    'senderid' => 'SHOWMYCLAIMS',
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
                    Session::flash('message', 'Oops, something went wrong while sending change password link!');
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->route('companyAdmin');
                }*/
                /*--------------------------SENDING SMS ALERT(TEMP CODE) ENDS-----------------------------------*/

                Session::flash('message', 'Company successfully saved!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('companyAdmin');
            }

        }
        Session::flash('message', 'Oops, failed to create company');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('companyAdmin');
    }

    public function editCompanyView(Request $request)
    {
        $postData = $request->input();

        if (empty($postData['id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $company = Company::find($postData['id'])->first();
        return view('admin.edit_company')->withCompany($company);
    }

    public function editCompany(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'company_name' => 'required'
        ]);

        if ($validator->fails()) {
            Session::flash('message', 'Please fill all required fields');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $postData = $request->input();

        if (!empty($_FILES['company_logo']['name']))
            $blogImage = $request->file('company_logo')->store('public/companies');

        $company = Company::find($postData['id'])->first();

        if (!empty($blogImage))
            $company->logo = $blogImage;

        $company->name = $postData['company_name'];

        $result = $company->save();
        if ($result) {
            Session::flash('message', 'Company successfully saved!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, failed to create company');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('companyAdmin');
    }

    public function deleteCompany(Request $request)
    {
        $postData = $request->input();

        if (empty($postData['id'])) {
            Session::flash('message', 'Oops, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $result = Company::where('id', $postData['id'])->update(['status' => 0]);
        if ($result) {

            BuUser::where('bu_company_id', $postData['id'])->update(['status' => 0]);

            Session::flash('message', 'Company successfully deleted!');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Oops, failed to delete company');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('companyAdmin');
    }

    public function companyUser(Request $request, $company)
    {
        $company_result = Company::find($company);
        if($company_result) {
            return view('admin.company_users')
                ->withUsers($company_result->users->where('status', 1))
                ->withCompany($company_result);
        }
        Session::flash('message', 'Oop, something went wrong!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('companyAdmin');
    }

    public function companyAddUserView(Request $request, $company){
        $company_result = Company::find($company);
        if($company_result) {
            return view('admin.add_company_user')
                ->withCompany($company_result);
        }
        Session::flash('message', 'Oop, something went wrong!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('companyAdmin');
    }

    public function companyAddUser(Request $request, $company)
    {
        if(empty($company)){
            Session::flash('message', 'Oop, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $postData = $request->input();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'nullable|email|unique:bu_users,email',
            'mobile' => 'required|numeric|digits_between:9,13|unique:bu_users,mobile',
        ]);

        if ($validator->fails()) {
            Session::flash('message', 'Please fill all required fields');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        $postData['password'] = strtoupper(substr(md5(rand()), 0, 8));
        $company_result = Company::find($company);
        if($company_result) {
            $role = BuRole::where('role_name', 'admin')->first();
            $user_data['name'] = $postData['name'];
            $user_data['mobile'] =  $postData['mobile'];
            $user_data['email'] =  $postData['email'];
            $user_data['password'] =  Hash::make($postData['password']);
            $user_data['bu_role_id'] = $role->id;
            $user_data['bu_company_id'] = $company;

            $user_result = BuUser::create($user_data);
            if($user_result){
                // Create a random 4 digit code to send to user for verification
                $token = strtoupper(substr(md5(rand()), 0, 8));
                $upData = array(
                    'reset_password_token' => $token,
                    'reset_password_token_date' => date('Y-m-d H:i:s')
                );
                $user_update = $user_result->update($upData);
                if($user_update){
                    if(!empty($user_result->email)) {
                        $to = $user_result->email;
                        $toName = $user_result->name;
                        Mail::send('business_reset_password', ['current_password' => $postData['password'], 'url' => route('buChangePasswordView', ['reset_token' => $token])], function($message) use ($to, $toName) {
                            $message->to($to, $toName)->subject
                            ('Change Password Details - Show My Claims');
                            $message->from('info@showmyclaims.com','My Claims');
                        });
                    }
                }
                Session::flash('message', 'User successfully saved!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('companyUsersAdmin', ['company'=> $company]);
            }
        }
        Session::flash('message', 'Oop, something went wrong!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->back();
    }

    public function companyEditUserView(Request $request, $company, $user){

        if(empty($user)){
            Session::flash('message', 'Oop, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $user_result = BuUser::find($user);
        $company_result = Company::find($company);
        if($user_result) {
            return view('admin.edit_company_user')
                ->withUser($user_result)
                ->withCompany($company_result);
        }
        Session::flash('message', 'Oop, something went wrong!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('companyUsersAdmin', ['company'=>$company]);
    }

    public function companyEditUser(Request $request, $company, $user){

        if(empty($user)){
            Session::flash('message', 'Oop, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $user_result = BuUser::find($user);
        if($user_result) {
            $postData = $request->input();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'nullable|email|unique:bu_users,email,'.$user_result->id,
                'mobile' => 'required|numeric|digits_between:9,13|unique:bu_users,mobile,'.$user_result->id,
            ]);

            if ($validator->fails()) {
                Session::flash('message', 'Please fill all required fields');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $user_data['name'] = $postData['name'];
            $user_data['mobile'] =  $postData['mobile'];
            $user_data['email'] =  $postData['email'];
            $user_result->update($user_data);
            Session::flash('message', 'User successfully updated!');
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('companyUsersAdmin', ['company'=>$company]);
        }
        Session::flash('message', 'Oop, user not found!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('companyUsersAdmin', ['company'=>$company]);
    }

    public function companyDeleteUser(Request $request, $company, $user)
    {
        if(empty($user)){
            Session::flash('message', 'Oop, something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
        $user_result = BuUser::find($user);
        if($user_result) {
            $user_data['status'] = 0;
            $user_result->update($user_data);
            Session::flash('message', 'User successfully deleted!');
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('companyUsersAdmin', ['company'=>$company]);
        }
        Session::flash('message', 'Oop, user not found!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('companyUsersAdmin', ['company'=>$company]);
    }



}
