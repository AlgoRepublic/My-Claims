<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claims extends Model
{
    protected $fillable = ['policyholder_id', 'beneficiary_identity', 'policyholder_death_proof', 'is_approved', 'approved_by', 'email_preference', 'approved_date', 'beneficiary_request_date'];

    public function user()
    {
        return $this->belongsTo('App\User', 'policyholder_id', 'id');
    }
}
