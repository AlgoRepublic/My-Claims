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
});

Route::group(['prefix' => 'policyHolder', 'middleware' => 'policyholder'], function() {
    Route::get('/', 'PolicyHolderController@index');
    /*Route::get('/addPolicy', function() {
        return view('policyholder.add_policy');
    });*/
    Route::get('/addPolicy', 'PolicyHolderController@addPolicyView');
    Route::post('/addPolicy', 'PolicyHolderController@addPolicy');
});

/*Route::group(['prefix' => 'beneficiary', 'middleware' => 'beneficiary'], function() {
    Route::get('/', 'BeneficiaryController@index');
});*/

Route::get('/admin/login', function() {
    return view('admin.login');
})->name('adminLogin');

Route::get('/policyHolder/login', function() {
    return view('policyholder.login');
})->name('policyLogin');

Route::get('/beneficiary/login', function() {
    return view('beneficiary.login');
})->name('beneficiaryLogin');

Route::get('/beneficiary', 'BaseController@beneficiary');
Route::post('/beneficiary/find-policy', 'BaseController@findPolicy');

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

Route::get('/policyHolder/register/', function () {
    return view('policyholder.register');
});

Route::post('/policyHolder/register/', "PolicyHolderController@register");
Route::post('/policyHolder/checkCell/', "PolicyHolderController@checkCell");
