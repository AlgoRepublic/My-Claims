<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficiary_policy extends Model
{
    protected $fillable = ['policy_id', 'beneficiary_id'];
}
