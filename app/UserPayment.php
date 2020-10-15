<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $fillable = ['user_id', 'package_id', 'expiration_date', 'token', 'payment_method'];
}
