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
    Route::get('/beneficiaries', 'AdminController@beneficiaries');
    Route::get('/pending-claims', 'AdminController@pendingClaims');
});

Route::group(['prefix' => 'policyHolder', 'middleware' => 'policyholder'], function() {
    Route::get('/', 'PolicyHolderController@index');
    Route::get('/addPolicy', 'PolicyHolderController@addPolicyView');
    Route::post('/addPolicy', 'PolicyHolderController@addPolicy');
    Route::post('/edit', 'PolicyHolderController@editProfile');
    Route::get('/edit', function() {
        return view('policyholder.edit_profile', ['userData' => \Illuminate\Support\Facades\Auth::user()]);
    });
});

/*Route::group(['prefix' => 'beneficiary', 'middleware' => 'beneficiary'], function() {
    Route::get('/', 'BeneficiaryController@index');
});*/

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
Route::get('/beneficiary/add', function(){
    return view('beneficiary.add_beneficiary');
});
Route::post('/beneficiary/add', 'BaseController@addBeneficiary');

Route::post('/policyHolder/login', 'PolicyHolderController@login');
Route::get('/logout', 'BaseController@logout');
Route::get('/what-we-do', function(){
    return view('what_we_do');
});

Route::get('/blog', function(){
    return view('blog');
});

Route::get('/contact-us', function(){
    return view('contact_us');
});
Route::post('/contact-us', 'BaseController@contactUs');

Route::get('/policyHolder/register/', function () {
    return view('policyholder.register');
});

Route::post('/policyHolder/register/', "PolicyHolderController@register");
Route::post('/policyHolder/checkCell/', "PolicyHolderController@checkCell");
