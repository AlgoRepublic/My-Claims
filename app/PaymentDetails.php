<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $fillable = ['user_id','m_payment_id','pf_payment_id','payment_status','item_name','item_description',
        'amount_gross','amount_fee','amount_net','merchant_id','token','billing_date','created_at'];
}
