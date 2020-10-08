<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claims extends Model
{
    protected $fillable = ['beneficiary_identity', 'policyholder_death_proof', 'is_approved', 'approved_by', 'email_preference', 'approved_date', 'beneficiary_request_date'];
}
