<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'BaseController@index');
Route::get('/login', function() {
    return view('login');
})->name('login');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
    Route::get('/', 'AdminController@index');
    Route::get('/policyHolders', 'AdminController@policyHolder');
    Route::get('/policyHolders', 'AdminController@policyHolder')->name('policyHoldersDetail');
    Route::get('/edit-policyHolder', 'AdminController@editPolicyHolder')->name('policyHoldersEdit');
    Route::get('/beneficiaries', 'AdminController@beneficiaries');
    Route::get('/pending-claims', 'AdminController@pendingClaims');
    Route::get('/approved-claims', 'AdminController@approvedClaims');
    Route::get('/declined-claims', 'AdminController@declinedClaims');
    Route::get('/updateClaim', 'AdminController@updateBeneficiaryClaimStatus')->name('update-claim');
    Route::get('/user-feedback', 'AdminController@contactRequests');
    Route::get('/what-we-do', 'AdminController@whatWeDo');
    Route::post('/what-we-do', 'AdminController@updateWhatWeDo');
    Route::get('/blogs', 'AdminController@blogs')->name('blogs');
    Route::get('/logout', 'AdminController@logout');
    Route::get('/policyHolders/manualPaymentRequests', 'AdminController@manualPaymentRequests');
    Route::post('/verifyPayment', 'AdminController@verifyPayment');
    Route::get('/delete-policyholder', 'AdminController@deletePolicyHolder')->name('deletePolicyHolder');
    Route::get('/add-blog', function() {
        return view('admin.add_blog');
    });
    Route::post('/add-blog', 'AdminController@addBlog');
    Route::get('/delete-blog', 'AdminController@deleteBlog')->name('deleteBlog');
    Route::get('/addPolicy', 'AdminController@addPolicyView')->name('addPolAdmin');
    Route::post('/addPolicy', 'PolicyHolderController@addPolicy');
    //Route::get('/blogs', 'AdminController@blogs')->name('blogs');
    Route::get('/deletedPolicyHolders', 'AdminController@deletedPolicyHolder');
    Route::get('/permanently-delete-policyholder', 'AdminController@permanentlyDeletePolicyHolder')->name('permanentlyDeletePolicyHolder');
    Route::get('/expired-subscriptions', 'AdminController@expiredSubscriptionView')->name('expiredSubscriptionView');
    Route::get('/expired-subscriptions-send-sms', 'AdminController@expiredSubscriptionSendSMS')->name('expiredSubscriptionSendSMS');

});

Route::group(['prefix' => 'policyHolder', 'middleware' => 'policyholder'], function() {
    Route::get('/', 'PolicyHolderController@index');
    Route::get('/addPolicy', 'PolicyHolderController@addPolicyView');
    Route::post('/addPolicy', 'PolicyHolderController@addPolicy');
    Route::get('/editPolicy', 'PolicyHolderController@editPolicyView')->name('editPolicy');;
    Route::post('/editPolicy', 'PolicyHolderController@editPolicy');
    Route::post('/deletePolicyDocument', 'PolicyHolderController@deletePolicyDocument');
    Route::get('/edit', function() {
        $packages = \App\PaymentPackages::orderBy('amount','ASC')->get();
        /*foreach ($packages as $key => $package)
        {
            if($package->type === 'free_trail')
            {
                unset($packages[$key]);
                break;
            }
        }*/
        return view('policyholder.edit_profile', ['userData' => \Illuminate\Support\Facades\Auth::user(),'packages' => $packages]);
    });
    Route::post('add-beneficiary-or-policy', 'PolicyHolderController@addBeneficiaryOrPolicy');

});

/*Route::group(['prefix' => 'beneficiary', 'middleware' => 'beneficiary'], function() {
    Route::get('/', 'BeneficiaryController@index');
});*/
Route::post('policyHolder/edit', 'PolicyHolderController@editProfile');

Route::get('/admin/login', function() {
    return view('admin.login');
})->name('adminLogin');
Route::post('/admin/login', 'AdminController@login');

Route::get('/policyHolder/delete', 'PolicyHolderController@deletePolicy')->name('deletePolicy');
Route::get('/policyHolder/login', function() {
    return view('policyholder.login');
})->name('policyLogin');

Route::get('/beneficiary/login', function() {
    return view('beneficiary.login');
})->name('beneficiaryLogin');

Route::get('/beneficiary/delete', 'BaseController@deleteBeneficiary')->name('deleteBeneficiary');
Route::get('/beneficiary/edit', 'BaseController@editBeneficiary')->name('editBeneficiary');
Route::post('/beneficiary/edit', 'BaseController@updateBen');
Route::get('/beneficiary', 'BaseController@beneficiary');
Route::post('/beneficiary/find-policy', 'BaseController@findPolicy');
Route::post('/beneficiary/policy-request', 'BaseController@policyRequest');
/*Route::get('/beneficiary/add', function(){
    return view('beneficiary.add_beneficiary');
});*/
Route::get('/beneficiary/add', function(){
    return view('beneficiary.add_beneficiary');
});
Route::post('/beneficiary/add', 'BaseController@addBeneficiary');

Route::post('/policyHolder/complete-registration', 'PolicyHolderController@completeRegistration');
Route::post('/policyHolder/login', 'PolicyHolderController@login');
Route::get('/logout', 'BaseController@logout');
Route::get('/what-we-do', 'BaseController@whatWeDo');

Route::get('/blog', 'BaseController@blog');
Route::get('/blog', 'BaseController@blog')->name('blog-detail');

Route::get('/contact-us', function(){
    return view('contact_us');
});
Route::post('/contact-us', 'BaseController@contactUs');

/*Route::get('/policyHolder/register/', function () {
    return view('policyholder.register');
});*/

Route::get('/policyHolder/register/', 'PolicyHolderController@registerView');

Route::get('/forgot-password/', function () {
    return view('policyholder.forgot_password');
});
Route::post('/policyHolder/forgotPassword/', "PolicyHolderController@forgotPassword");
Route::post('/policyHolder/verifyToken/', "PolicyHolderController@verifyToken");
Route::post('/policyHolder/updatePassword/', "PolicyHolderController@updatePassword");

Route::post('/policyHolder/register/', "PolicyHolderController@register");
Route::post('/policyHolder/checkCell/', "PolicyHolderController@checkCell");

Route::get('/payfast-success', 'PolicyHolderController@paymentSuccess');
Route::get('/payfast-cancel', 'PolicyHolderController@paymentCancel');
Route::post('/payfast-notify', 'PolicyHolderController@paymentNotify');

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});

