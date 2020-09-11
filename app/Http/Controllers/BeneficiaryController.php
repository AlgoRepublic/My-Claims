<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        return view('beneficiary.home');
    }
}
